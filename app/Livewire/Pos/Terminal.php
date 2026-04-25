<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Product;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Transaction;
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

    public function mount()
    {
        $this->orderNumber = PosOrder::generateOrderNumber();
    }

    /**
     * Resolve the owner (admin) user ID for the current user.
     * - If current user is admin: return their own ID.
     * - If current user is cashier: find the admin with the same business_name.
     */
    protected function getOwnerUserId(): int
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $user->id;
        }

        // Cashier: find the admin sharing the same business_name
        $owner = \App\Models\User::where('role', 'admin')
            ->where('business_name', $user->business_name)
            ->first();

        return $owner ? $owner->id : $user->id;
    }

    /**
     * Get dynamic category list from DB products
     */
    public function getCategoriesProperty()
    {
        return Product::where('user_id', $this->getOwnerUserId())
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->prepend('Semua')
            ->toArray();
    }

    /**
     * Get filtered products from database
     */
    public function getProductsProperty()
    {
        $query = Product::where('user_id', $this->getOwnerUserId());

        // Filter by category
        if ($this->activeCategory !== 'Semua') {
            $query->where('category', $this->activeCategory);
        }

        // Search by name
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query->orderBy('name')->get();
    }

    public function setCategory($category)
    {
        $this->activeCategory = $category;
    }

    public function addToCart($id)
    {
        $product = Product::find($id);
        if (!$product) return;

        $currentInCart = isset($this->cart[$id]) ? $this->cart[$id]['qty'] : 0;

        // Check stock availability
        if ($currentInCart >= $product->current_stock) {
            $this->dispatch('notify', message: 'Stok tidak mencukupi!', type: 'error');
            return;
        }

        if (isset($this->cart[$id])) {
            $this->cart[$id]['qty']++;
        } else {
            $this->cart[$id] = [
                'name' => $product->name,
                'price' => (float) $product->sell_price,
                'image' => $product->image ?? '',
                'qty' => 1,
                'stock' => $product->current_stock,
            ];
        }
    }

    public function increaseQty($id)
    {
        if (!isset($this->cart[$id])) return;

        // Re-check stock from DB
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

        if ($this->cart[$id]['qty'] > 1) {
            $this->cart[$id]['qty']--;
        } else {
            unset($this->cart[$id]);
        }
    }

    public function clearCart()
    {
        $this->cart = [];
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
    }

    public function getTaxAmountProperty()
    {
        return $this->subtotal * $this->tax;
    }

    public function getTotalProperty()
    {
        return $this->subtotal + $this->taxAmount;
    }

    /**
     * Process checkout - ATOMIC TRANSACTION
     * 1. Create POS order
     * 2. Create order items
     * 3. Decrement product stock
     * 4. Create inventory logs (OUT)
     * 5. Create financial transaction (INCOME)
     * 6. Flush cache for dashboard sync
     */
    public function checkout()
    {
        if (count($this->cart) === 0) return;

        try {
            DB::transaction(function () {
                $orderNumber = PosOrder::generateOrderNumber();
                $subtotal = $this->subtotal;
                $taxAmount = $this->taxAmount;
                $total = $this->total;

                // 1. Create POS Order
                $order = PosOrder::create([
                    'user_id' => Auth::id(),
                    'order_number' => $orderNumber,
                    'subtotal' => $subtotal,
                    'tax_rate' => $this->tax,
                    'tax_amount' => $taxAmount,
                    'total' => $total,
                    'payment_method' => 'CASH',
                    'status' => 'COMPLETED',
                ]);

                // 2. Create order items + update stock
                foreach ($this->cart as $productId => $item) {
                    $product = Product::lockForUpdate()->find($productId);

                    if (!$product || $product->current_stock < $item['qty']) {
                        throw new \Exception("Stok '{$item['name']}' tidak mencukupi.");
                    }

                    // Create order item
                    PosOrderItem::create([
                        'pos_order_id' => $order->id,
                        'product_id' => $productId,
                        'product_name' => $item['name'],
                        'quantity' => $item['qty'],
                        'unit_price' => $item['price'],
                        'subtotal' => $item['price'] * $item['qty'],
                    ]);

                    // 3. Decrement stock manually and save quietly to avoid duplicate log from observer
                    $product->current_stock -= $item['qty'];
                    $product->saveQuietly();

                    // 4. Create inventory log (OUT)
                    $product->inventoryLogs()->create([
                        'type' => 'OUT',
                        'quantity' => $item['qty'],
                        'reason' => "Penjualan POS #{$orderNumber}",
                    ]);
                }

                // 5. Create financial transaction (INCOME)
                Transaction::create([
                    'user_id' => Auth::id(),
                    'type' => 'INCOME',
                    'amount' => $total,
                    'category' => 'Penjualan POS',
                    'notes' => "Order #{$orderNumber} — " . count($this->cart) . " produk",
                ]);

                // 6. Flush cache so dashboard sees fresh data
                Cache::forget('dashboard_stats_' . Auth::id());

                // Store for display
                $this->orderNumber = $orderNumber;
            });

            // Success!
            $this->dispatch('payment-successful');
            $this->cart = [];

            // Generate next order number
            $this->orderNumber = PosOrder::generateOrderNumber();

        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Checkout gagal: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.pos.terminal', [
            'products' => $this->products,
            'categories' => $this->categories,
        ])->layout('components.layouts.pos');
    }
}