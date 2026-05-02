<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Product;
use App\Models\GuestOrder;
use App\Models\GuestOrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GuestOrderController extends Controller
{
    /**
     * Simpan pesanan baru dari pelanggan (dari halaman menu publik).
     */
    public function store(Request $request, string $tableCode): JsonResponse
    {
        $tableModel = Table::where('table_code', $tableCode)
            ->where('is_active', true)
            ->first();

        if (!$tableModel) {
            return response()->json(['success' => false, 'message' => 'Meja tidak valid.'], 404);
        }

        $validated = $request->validate([
            'items'                  => 'required|array|min:1',
            'items.*.product_id'     => 'required|integer|exists:products,id',
            'items.*.quantity'       => 'required|integer|min:1|max:99',
            'items.*.notes'          => 'nullable|string|max:255',
            'payment_method'         => 'required|in:CASHIER,QRIS',
        ]);

        $ownerId = $tableModel->user_id;
        $taxRate = 0.11;

        try {
            $guestOrder = DB::transaction(function () use ($validated, $tableModel, $ownerId, $taxRate) {
                $orderItems = [];
                $subtotal = 0;

                foreach ($validated['items'] as $item) {
                    $product = Product::where('id', $item['product_id'])
                        ->where('user_id', $ownerId)
                        ->where('is_available_online', true)
                        ->where('current_stock', '>=', $item['quantity'])
                        ->first();

                    if (!$product) {
                        throw new \Exception("Produk tidak tersedia atau stok tidak cukup.");
                    }

                    $itemSubtotal = $product->sell_price * $item['quantity'];
                    $subtotal += $itemSubtotal;

                    $orderItems[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'quantity'     => $item['quantity'],
                        'unit_price'   => $product->sell_price,
                        'subtotal'     => $itemSubtotal,
                        'notes'        => $item['notes'] ?? null,
                    ];
                }

                $taxAmount = round($subtotal * $taxRate, 2);
                $total     = $subtotal + $taxAmount;

                // Buat guest order
                $guestOrder = GuestOrder::create([
                    'user_id'        => $ownerId,
                    'table_id'       => $tableModel->id,
                    'order_code'     => GuestOrder::generateOrderCode(),
                    'subtotal'       => $subtotal,
                    'tax_rate'       => $taxRate,
                    'tax_amount'     => $taxAmount,
                    'total'          => $total,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $validated['payment_method'] === 'QRIS' ? 'PAID' : 'UNPAID',
                    'order_status'   => 'PENDING',
                    'expires_at'     => now()->addMinutes(45),
                ]);

                // Buat item-item pesanan
                foreach ($orderItems as $item) {
                    $guestOrder->items()->create($item);
                }

                return $guestOrder;
            });

            return response()->json([
                'success'    => true,
                'order_code' => $guestOrder->order_code,
                'total'      => $guestOrder->total,
                'message'    => 'Pesanan berhasil dibuat!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Cek status pesanan (polling dari halaman konfirmasi pelanggan).
     */
    public function status(string $orderCode): JsonResponse
    {
        $order = GuestOrder::where('order_code', strtoupper($orderCode))
            ->select('order_code', 'order_status', 'payment_status', 'payment_method', 'total')
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        return response()->json([
            'success'        => true,
            'order_status'   => $order->order_status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'total'          => $order->total,
        ]);
    }
}
