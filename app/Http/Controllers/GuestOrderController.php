<?php

namespace App\Http\Controllers;

use App\Models\GuestOrder;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class GuestOrderController extends Controller
{
    public function store(Request $request, string $tableCode): JsonResponse
    {
        $table = Table::query()->where('table_code', $tableCode)
            ->where('is_active', true)->with('user')->first();

        if (!$table || !$table->user) {
            return response()->json(['message' => 'Meja tidak tersedia.'], 404);
        }

        $data = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:25'],
            'items.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.notes' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:CASHIER,QRIS'],
        ]);

        if ($data['payment_method'] === 'QRIS' && !$table->user->gopay_qr_image) {
            throw ValidationException::withMessages([
                'payment_method' => 'Pembayaran QRIS belum disediakan toko. Pilih bayar di kasir.',
            ]);
        }

        try {
            $order = DB::transaction(function () use ($data, $table) {
                // All prices, ownership and stock are read from the server.
                $subtotal = 0;
                $items = [];
                foreach ($data['items'] as $item) {
                    $product = Product::query()
                        ->where('user_id', $table->user_id)
                        ->where('is_available_online', true)
                        ->whereKey($item['product_id'])
                        ->lockForUpdate()->first();

                    if (!$product || $product->current_stock < $item['quantity']) {
                        throw new RuntimeException('Produk tidak tersedia atau stok tidak cukup. Perbarui halaman.');
                    }

                    $line = (float) $product->sell_price * $item['quantity'];
                    $subtotal += $line;
                    $items[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->sell_price,
                        'subtotal' => $line,
                        'notes' => $item['notes'] ?? null,
                    ];
                }

                $tax = round($subtotal * 0.11, 2);
                $guestOrder = GuestOrder::create([
                    'user_id' => $table->user_id,
                    'table_id' => $table->id,
                    'order_code' => GuestOrder::generateOrderCode(),
                    'subtotal' => $subtotal,
                    'tax_rate' => 0.11,
                    'tax_amount' => $tax,
                    'total' => $subtotal + $tax,
                    'payment_method' => $data['payment_method'],
                    // A customer's click never proves that QRIS money arrived.
                    'payment_status' => 'UNPAID',
                    'order_status' => 'PENDING',
                    'expires_at' => now()->addMinutes(45),
                ]);
                $guestOrder->items()->createMany($items);
                return $guestOrder;
            });

            return response()->json([
                'success' => true,
                'order_code' => $order->order_code,
                'total' => (float) $order->total,
                'payment_status' => $order->payment_status,
                'message' => 'Pesanan diterima. Tunjukkan kode kepada kasir untuk diproses.',
            ], 201);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function status(string $orderCode): JsonResponse
    {
        $order = GuestOrder::query()->where('order_code', strtoupper($orderCode))->firstOrFail();

        if ($order->isExpired() && $order->order_status === 'PENDING') {
            $order->update(['order_status' => 'CANCELLED']);
        }

        return response()->json([
            'order_code' => $order->order_code,
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
        ]);
    }
}
