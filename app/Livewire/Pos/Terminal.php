<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Product;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Transaction;
use App\Models\GuestOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Terminal extends Component
{
    public $cart = [];
    public $search = '';
    public $activeCategory = 'Semua';
    public $orderNumber = '';
    public $tax = 0.11;

    // === Scan to Order ===
    public $activeTab = 'manual';       // 'manual' | 'online'
    public $guestOrderCode = '';        // Input kode dari kasir
    public $activeGuestOrderId = null;  // ID guest order yang sedang di-load
    public $loadedGuestOrder = null;    // Data array untuk display

    public function mount()
    {
        $this->orderNumber = PosOrder::generateOrderNumber();
    }

    protected function getOwnerUserId(): int
    {
        $user = Auth::user();
        if ($user->role === 'admin') return $user->id;
        $owner = \App\Models\User::where('role', 'admin')
            ->where('business_name', $user->business_name)
            ->first();
        return $owner ? $owner->id : $user->id;
    }

    public function getCategoriesProperty()
    {
        return Product::where('user_id', $this->getOwnerUserId())
            ->whereNotNull('category')->where('category', '!=', '')
            ->distinct()->pluck('category')->prepend('Semua')->toArray();
    }

    public function getProductsProperty()
    {
        $query = Product::where('user_id', $this->getOwnerUserId());
        if ($this->activeCategory !== 'Semua') $query->where('category', $this->activeCategory);
        if (!empty($this->search)) $query->where('name', 'like', '%' . $this->search . '%');
        return $query->orderBy('name')->get();
    }

    public function setCategory($category) { $this->activeCategory = $category; }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        if ($tab === 'manual') $this->cancelGuestOrder();
    }

    /** Load pesanan dari HP pelanggan menggunakan kode KSR-XXXXX */
    public function loadGuestOrder()
    {
        $code = strtoupper(trim($this->guestOrderCode));
        if (empty($code)) {
            $this->dispatch('notify', message: 'Masukkan kode pesanan!', type: 'error');
            return;
        }

        $guestOrder = GuestOrder::where('order_code', $code)
            ->where('user_id', $this->getOwnerUserId())
            ->whereIn('order_status', ['PENDING', 'PROCESSING'])
            ->with('items.product', 'table')
            ->first();

        if (!$guestOrder) {
            $this->dispatch('notify', message: 'Kode tidak ditemukan atau sudah diproses.', type: 'error');
            return;
        }

        if ($guestOrder->isExpired()) {
            $guestOrder->update(['order_status' => 'CANCELLED']);
            $this->dispatch('notify', message: 'Kode pesanan sudah kadaluarsa (> 45 menit).', type: 'error');
            return;
        }

        // Isi cart dari guest order
        $this->cart = [];
        foreach ($guestOrder->items as $item) {
            $this->cart[$item->product_id] = [
                'name'  => $item->product_name,
                'price' => (float) $item->unit_price,
                'image' => $item->product->image ?? '',
                'qty'   => $item->quantity,
                'stock' => $item->product->current_stock ?? $item->quantity,
                'notes' => $item->notes ?? '',
            ];
        }

        $this->activeGuestOrderId = $guestOrder->id;
        $this->loadedGuestOrder = [
            'table'          => $guestOrder->table->table_number ?? '-',
            'payment_method' => $guestOrder->payment_method,
            'payment_status' => $guestOrder->payment_status,
            'total'          => $guestOrder->total,
            'order_code'     => $guestOrder->order_code,
        ];

        // Tandai sebagai sedang diproses
        $guestOrder->update(['order_status' => 'PROCESSING']);
        $this->dispatch('notify', message: 'Pesanan ' . $code . ' berhasil dimuat!', type: 'success');
    }

    public function cancelGuestOrder()
    {
        if ($this->activeGuestOrderId) {
            GuestOrder::where('id', $this->activeGuestOrderId)
                ->where('order_status', 'PROCESSING')
                ->update(['order_status' => 'PENDING']);
        }
        $this->activeGuestOrderId = null;
        $this->loadedGuestOrder = null;
        $this->guestOrderCode = '';
        $this->cart = [];
    }

    public function addToCart($id)
    {
        $product = Product::find($id);
        if (!$product) return;
        $currentInCart = isset($this->cart[$id]) ? $this->cart[$id]['qty'] : 0;
        if ($currentInCart >= $product->current_stock) {
            $this->dispatch('notify', message: 'Stok tidak mencukupi!', type: 'error');
            return;
        }
        if (isset($this->cart[$id])) {
            $this->cart[$id]['qty']++;
        } else {
            $this->cart[$id] = [
                'name'  => $product->name,
                'price' => (float) $product->sell_price,
                'image' => $product->image ?? '',
                'qty'   => 1,
                'stock' => $product->current_stock,
                'notes' => '',
            ];
        }
    }

    public function increaseQty($id)
    {
        if (!isset($this->cart[$id])) return;
        $product = Product::find($id);
        if (!$product || $this->cart[$id]['qty'] >= $product->current_stock) {
            $this->dispatch('notify', message: 'Stok tidak mencukupi!', type: 'error');
            return;
        }
        $this->cart[$id]['qty']++;
    }

    public function decreaseQty($id)
    {
        if (!isset($this->cart[$id])) return;
        if ($this->cart[$id]['qty'] > 1) $this->cart[$id]['qty']--;
        else unset($this->cart[$id]);
    }

    public function clearCart()
    {
        if ($this->activeGuestOrderId) $this->cancelGuestOrder();
        else $this->cart = [];
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
    }

    public function getTaxAmountProperty() { return $this->subtotal * $this->tax; }
    public function getTotalProperty() { return $this->subtotal + $this->taxAmount; }

    public function checkout()
    {
        if (count($this->cart) === 0) return;
        $guestOrderId = $this->activeGuestOrderId;

        try {
            DB::transaction(function () use ($guestOrderId) {
                $orderNumber = PosOrder::generateOrderNumber();
                $subtotal    = $this->subtotal;
                $taxAmount   = $this->taxAmount;
                $total       = $this->total;

                // Tentukan payment method
                $paymentMethod = 'CASH';
                if ($guestOrderId) {
                    $go = GuestOrder::find($guestOrderId);
                    if ($go) $paymentMethod = $go->payment_method === 'QRIS' ? 'QRIS' : 'CASH';
                }

                $order = PosOrder::create([
                    'user_id'        => Auth::id(),
                    'order_number'   => $orderNumber,
                    'subtotal'       => $subtotal,
                    'tax_rate'       => $this->tax,
                    'tax_amount'     => $taxAmount,
                    'total'          => $total,
                    'payment_method' => $paymentMethod,
                    'status'         => 'COMPLETED',
                    'guest_order_id' => $guestOrderId,
                ]);

                foreach ($this->cart as $productId => $item) {
                    $product = Product::lockForUpdate()->find($productId);
                    if (!$product || $product->current_stock < $item['qty']) {
                        throw new \Exception("Stok '{$item['name']}' tidak mencukupi.");
                    }
                    PosOrderItem::create([
                        'pos_order_id' => $order->id,
                        'product_id'   => $productId,
                        'product_name' => $item['name'],
                        'quantity'     => $item['qty'],
                        'unit_price'   => $item['price'],
                        'subtotal'     => $item['price'] * $item['qty'],
                    ]);
                    $product->decrement('current_stock', $item['qty']);
                    $product->inventoryLogs()->create([
                        'type'     => 'OUT',
                        'quantity' => $item['qty'],
                        'reason'   => "Penjualan POS #{$orderNumber}" . ($guestOrderId ? " (Scan to Order)" : ""),
                    ]);
                }

                Transaction::create([
                    'user_id'  => Auth::id(),
                    'type'     => 'INCOME',
                    'amount'   => $total,
                    'category' => 'Penjualan POS',
                    'notes'    => "Order #{$orderNumber} — " . count($this->cart) . " produk",
                ]);

                Cache::forget('dashboard_stats_' . Auth::id());

                // Selesaikan guest order jika ada
                if ($guestOrderId) {
                    GuestOrder::where('id', $guestOrderId)
                        ->update(['order_status' => 'COMPLETED', 'payment_status' => 'PAID']);
                }

                $this->orderNumber = $orderNumber;
            });

            $this->dispatch('payment-successful');
            $this->cart = [];
            $this->activeGuestOrderId = null;
            $this->loadedGuestOrder = null;
            $this->guestOrderCode = '';
            $this->activeTab = 'manual';
            $this->orderNumber = PosOrder::generateOrderNumber();

        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Checkout gagal: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.pos.terminal', [
            'products'   => $this->products,
            'categories' => $this->categories,
        ])->layout('components.layouts.pos');
    }
}