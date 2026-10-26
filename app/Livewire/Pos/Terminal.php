<?php

namespace App\Livewire\Pos;

use App\Models\GuestOrder;
use App\Models\PosOrder;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\PosOrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;
use RuntimeException;

class Terminal extends Component
{
    public array $cart = [];
    public string $search = '';
    public string $activeCategory = 'Semua';
    public string $orderNumber = '';
    public float $tax = 0.11;

    public string $activeTab = 'manual';
    public string $guestOrderCode = '';
    #[Locked]
    public ?int $activeGuestOrderId = null;
    public ?array $loadedGuestOrder = null;
    public bool $qrPaymentVerified = false;

    public function mount(): void
    {
        $this->orderNumber = PosOrder::generateOrderNumber();
    }

    protected function getOwnerUserId(): int
    {
        $user = Auth::user();
        if ($user->role === 'admin') return $user->id;

        // Legacy account linkage: a dedicated owner_id is recommended before
        // enabling multi-owner stores with identical business names.
        $owner = User::query()->where('role', 'admin')
            ->where('business_name', $user->business_name)->first();

        return $owner ? $owner->id : $user->id;
    }

    public function getCategoriesProperty(): array
    {
        return Product::where('user_id', $this->getOwnerUserId())
            ->whereNotNull('category')->where('category', '!=', '')
            ->distinct()->orderBy('category')->pluck('category')->prepend('Semua')->all();
    }

    public function getProductsProperty()
    {
        return Product::where('user_id', $this->getOwnerUserId())
            ->when($this->activeCategory !== 'Semua',
                fn($q) => $q->where('category', $this->activeCategory))
            ->when(trim($this->search) !== '',
                fn($q) => $q->where('name', 'like', '%'.trim($this->search).'%'))
            ->orderBy('name')->get();
    }

    public function setCategory(string $category): void
    {
        $this->activeCategory = $category;
    }

    public function switchTab(string $tab): void
    {
        if (!in_array($tab, ['manual', 'online'], true)) return;
        if ($tab !== $this->activeTab && $this->activeGuestOrderId) $this->cancelGuestOrder();
        if ($tab !== $this->activeTab) $this->cart = [];
        $this->activeTab = $tab;
    }

    public function loadGuestOrder(): void
    {
        $code = strtoupper(trim($this->guestOrderCode));
        if ($code === '') {
            $this->dispatch('notify', message: 'Masukkan kode pesanan dari pelanggan.', type: 'error');
            return;
        }

        $order = GuestOrder::where('user_id', $this->getOwnerUserId())
            ->where('order_code', $code)->first();

        if (!$order) {
            $this->dispatch('notify', message: 'Kode pesanan tidak ditemukan di usaha ini.', type: 'error');
            return;
        }

        $this->loadGuestOrderById($order->id);
    }

    public function loadGuestOrderById(int $id): void
    {
        if ($this->activeGuestOrderId) $this->cancelGuestOrder();

        $ownerId = $this->getOwnerUserId();
        try {
            $order = DB::transaction(function () use ($id, $ownerId) {
                $order = GuestOrder::where('user_id', $ownerId)->whereKey($id)
                    ->lockForUpdate()->firstOrFail();

                if ($order->order_status !== 'PENDING' || $order->isExpired()) {
                    throw new RuntimeException('Pesanan sudah diambil kasir lain atau kedaluwarsa.');
                }

                $order->load('items.product', 'table');
                foreach ($order->items as $item) {
                    if (!$item->product || $item->product->user_id !== $ownerId ||
                        $item->product->current_stock < $item->quantity) {
                        throw new RuntimeException('Stok pesanan tidak mencukupi. Periksa inventaris.');
                    }
                }

                $order->update(['order_status' => 'PROCESSING']);
                return $order;
            });

            $this->cart = [];
            foreach ($order->items as $item) {
                $this->cart[$item->product_id] = [
                    'name' => $item->product_name,
                    'price' => (float) $item->unit_price,
                    'image' => $item->product->image ?? '',
                    'qty' => (int) $item->quantity,
                    'stock' => $item->product->current_stock,
                ];
            }

            $this->activeGuestOrderId = $order->id;
            $this->guestOrderCode = $order->order_code;
            $this->loadedGuestOrder = [
                'order_code' => $order->order_code,
                'table' => $order->table->table_number ?? 'Meja',
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'total' => $order->total,
            ];
            $this->qrPaymentVerified = false;
            $this->activeTab = 'online';
            $this->dispatch('notify', message: 'Pesanan '.$order->order_code.' siap diperiksa.', type: 'success');
        } catch (\Throwable $exception) {
            $this->dispatch('notify', message: $exception->getMessage(), type: 'error');
        }
    }

    public function cancelGuestOrder(): void
    {
        if ($this->activeGuestOrderId) {
            GuestOrder::where('user_id', $this->getOwnerUserId())
                ->whereKey($this->activeGuestOrderId)
                ->where('order_status', 'PROCESSING')
                ->update(['order_status' => 'PENDING']);
        }
        $this->activeGuestOrderId = null;
        $this->loadedGuestOrder = null;
        $this->qrPaymentVerified = false;
        $this->guestOrderCode = '';
        $this->cart = [];
    }

    public function addToCart(int $id): void
    {
        if ($this->activeGuestOrderId) return;
        $product = Product::where('user_id', $this->getOwnerUserId())->find($id);
        if (!$product) return;

        $currentQty = $this->cart[$id]['qty'] ?? 0;
        if ($currentQty >= $product->current_stock) {
            $this->dispatch('notify', message: 'Stok produk tidak mencukupi.', type: 'error');
            return;
        }

        if (isset($this->cart[$id])) $this->cart[$id]['qty']++;
        else $this->cart[$id] = [
            'name' => $product->name,
            'price' => (float) $product->sell_price,
            'image' => $product->image ?? '',
            'qty' => 1,
            'stock' => $product->current_stock,
        ];
    }

    public function increaseQty(int $id): void
    {
        if ($this->activeGuestOrderId || !isset($this->cart[$id])) return;
        $product = Product::where('user_id', $this->getOwnerUserId())->find($id);
        if (!$product || $this->cart[$id]['qty'] >= $product->current_stock) {
            $this->dispatch('notify', message: 'Stok produk tidak mencukupi.', type: 'error');
            return;
        }
        $this->cart[$id]['qty']++;
    }

    public function decreaseQty(int $id): void
    {
        if ($this->activeGuestOrderId || !isset($this->cart[$id])) return;
        if ($this->cart[$id]['qty'] > 1) $this->cart[$id]['qty']--;
        else unset($this->cart[$id]);
    }

    public function clearCart(): void
    {
        if ($this->activeGuestOrderId) $this->cancelGuestOrder();
        else $this->cart = [];
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(fn ($item) => $item['price'] * $item['qty']);
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
     * Server-side product lookup and stock locks are authoritative.
     * Guest orders can only be completed once, by an authorized cashier.
     */
    public function checkout(): void
    {
        if (!$this->activeGuestOrderId && !$this->cart) return;

        try {
            $ownerId = $this->getOwnerUserId();
            $guestId = $this->activeGuestOrderId;

            DB::transaction(function () use ($ownerId, $guestId) {
                $guest = null;
                $lines = [];
                $paymentMethod = 'CASH';
                $rate = $this->tax;

                if ($guestId) {
                    $guest = GuestOrder::where('user_id', $ownerId)
                        ->whereKey($guestId)->lockForUpdate()->firstOrFail();

                    if ($guest->order_status !== 'PROCESSING' || $guest->isExpired()) {
                        throw new RuntimeException('Pesanan QR sudah tidak dapat diproses.');
                    }
                    if ($guest->payment_method === 'QRIS' && !$this->qrPaymentVerified) {
                        throw new RuntimeException('Verifikasi penerimaan pembayaran QRIS sebelum checkout.');
                    }
                    $paymentMethod = $guest->payment_method === 'QRIS' ? 'QRIS' : 'CASH';
                    $rate = (float) $guest->tax_rate;
                    foreach ($guest->items as $item) {
                        $lines[] = [
                            'id' => $item->product_id,
                            'qty' => (int) $item->quantity,
                            'price' => (float) $item->unit_price,
                            'name' => $item->product_name,
                        ];
                    }
                } else {
                    foreach ($this->cart as $productId => $item) {
                        $qty = filter_var($item['qty'] ?? null, FILTER_VALIDATE_INT);
                        if (!$qty || $qty < 1 || $qty > 999) {
                            throw new RuntimeException('Jumlah produk tidak valid.');
                        }
                        $lines[] = ['id' => (int) $productId, 'qty' => $qty];
                    }
                }

                $subtotal = 0;
                foreach ($lines as &$line) {
                    $product = Product::where('user_id', $ownerId)
                        ->whereKey($line['id'])->lockForUpdate()->first();
                    if (!$product || $product->current_stock < $line['qty']) {
                        throw new RuntimeException('Stok pesanan tidak mencukupi.');
                    }
                    // Manual basket values are public Livewire state: never trust price.
                    if (!$guest) {
                        $line['price'] = (float) $product->sell_price;
                        $line['name'] = $product->name;
                    }
                    $line['product'] = $product;
                    $subtotal += $line['qty'] * $line['price'];
                }
                unset($line);

                if (!$lines) throw new RuntimeException('Tidak ada item yang bisa diproses.');
                $taxAmount = round($subtotal * $rate, 2);
                $total = $subtotal + $taxAmount;

                $orderNumber = PosOrder::generateOrderNumber();
                $order = PosOrder::create([
                    'user_id' => Auth::id(),
                    'order_number' => $orderNumber,
                    'subtotal' => $subtotal,
                    'tax_rate' => $rate,
                    'tax_amount' => $taxAmount,
                    'total' => $total,
                    'payment_method' => $paymentMethod,
                    'status' => 'COMPLETED',
                    'guest_order_id' => $guest?->id,
                ]);

                foreach ($lines as $line) {
                    PosOrderItem::create([
                        'pos_order_id' => $order->id,
                        'product_id' => $line['id'],
                        'product_name' => $line['name'],
                        'quantity' => $line['qty'],
                        'unit_price' => $line['price'],
                        'subtotal' => $line['qty'] * $line['price'],
                    ]);
                    $line['product']->current_stock -= $line['qty'];
                    $line['product']->saveQuietly();
                    $line['product']->inventoryLogs()->create([
                        'type' => 'OUT',
                        'quantity' => $line['qty'],
                        'reason' => 'Penjualan POS #'.$orderNumber.($guest ? ' (Meja QR)' : ''),
                    ]);
                }

                Transaction::create([
                    'user_id' => $ownerId,
                    'type' => 'INCOME',
                    'amount' => $total,
                    'category' => 'Penjualan POS',
                    'notes' => 'Order #'.$orderNumber.' — '.count($lines).' produk',
                ]);

                if ($guest) {
                    $guest->update(['order_status' => 'COMPLETED', 'payment_status' => 'PAID']);
                }

                Cache::forget('dashboard_stats_'.$ownerId);
            });

            $this->cart = [];
            $this->activeGuestOrderId = null;
            $this->loadedGuestOrder = null;
            $this->guestOrderCode = '';
            $this->qrPaymentVerified = false;
            $this->activeTab = 'manual';
            $this->orderNumber = PosOrder::generateOrderNumber();
            $this->dispatch('payment-successful');
        } catch (\Throwable $exception) {
            $this->dispatch('notify', message: 'Checkout ditolak: '.$exception->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $ownerId = $this->getOwnerUserId();
        $pendingGuestOrders = GuestOrder::where('user_id', $ownerId)
            ->where('order_status', 'PENDING')
            ->where('expires_at', '>', now())
            ->with('table')
            ->latest()->limit(12)->get();

        return view('livewire.pos.terminal', [
            'products' => $this->products,
            'categories' => $this->categories,
            'pendingGuestOrders' => $pendingGuestOrders,
        ])->layout('components.layouts.pos');
    }
}
