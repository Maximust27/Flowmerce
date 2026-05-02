<div>
    <!-- Top Navigation Bar -->
    <header class="fixed top-0 w-full z-50 flex justify-between items-center px-8 h-16 bg-[#0a0e1a] bg-opacity-50 backdrop-blur-xl border-b border-white/5 shadow-[0_8px_32px_rgba(5,7,10,0.4)]">
        <div class="flex items-center gap-2">
            <span class="text-2xl font-bold tracking-tight text-[#10b981] dark:text-[#4edea3]">FlowmercePos</span>
        </div>
        <div class="flex items-center gap-6">
            <!-- Search Bar -->
            <div class="relative w-72">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-on-surface-variant text-[20px]" data-icon="search">search</span>
                </div>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Cari produk..." 
                    class="block w-full pl-12 pr-4 py-2.5 bg-surface-container-highest border border-white/5 rounded-xl text-sm text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:ring-2 focus:ring-[#10b981]/40 transition-all shadow-sm"
                />
            </div>
            <div class="h-6 w-[1px] bg-white/10"></div>
            <!-- User Info -->
            <div class="flex items-center gap-2 text-on-surface-variant text-sm">
                <span class="material-symbols-outlined text-[18px]" data-icon="person">person</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium">{{ Auth::user()->name }}</span>
            </div>
            <div class="h-6 w-[1px] bg-white/10"></div>
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-container-highest border border-white/5 text-error hover:bg-error/10 transition-all duration-200 active:scale-95 font-medium">
                    <span class="material-symbols-outlined text-[18px]" data-icon="logout">logout</span>
                    <span class="font-['Plus_Jakarta_Sans'] text-sm tracking-wide">Logout</span>
                </button>
            </form>
        </div>
    </header>

    <main class="h-[calc(100vh-4rem)] mt-16 flex overflow-hidden">
        <!-- Main Product Section -->
        <section class="flex-1 flex flex-col p-6 overflow-hidden">
            <!-- Categories / Filters -->
            <div class="flex-none flex items-center gap-3 mb-6 overflow-x-auto pb-2 custom-scrollbar">
                @foreach($categories as $cat)
                    <button 
                        wire:click="setCategory('{{ $cat }}')"
                        class="px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap
                            {{ $activeCategory === $cat 
                                ? 'bg-primary-container text-on-primary-container shadow-lg shadow-primary/20' 
                                : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}"
                    >{{ $cat }}</button>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="flex-1 min-h-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-5 overflow-y-auto p-2 pr-4 custom-scrollbar content-start pb-8 -ml-2 -mt-2">
                @forelse($products as $product)
                    <div wire:click="addToCart({{ $product->id }})" class="group bg-surface-container rounded-2xl overflow-hidden cursor-pointer transition-all duration-300 hover:ring-2 hover:ring-primary/40 hover:-translate-y-1 flex flex-col h-[280px] {{ $product->current_stock <= 0 ? 'opacity-50 pointer-events-none' : '' }}">
                        <div class="relative flex-1 bg-surface-container-high flex items-center justify-center overflow-hidden">
                            <div class="absolute top-4 right-4 text-[10px] font-mono font-bold @if($product->current_stock < 5) text-error @else text-primary @endif tracking-wider z-10">
                                {{ $product->current_stock }} IN STOCK
                            </div>
                            @if($product->image)
                                <img alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-500" src="{{ $product->image }}"/>
                            @else
                                <span class="material-symbols-outlined text-[80px] text-on-surface-variant/20" data-icon="inventory_2">inventory_2</span>
                            @endif
                        </div>
                        <div class="p-4 bg-surface-container flex flex-col justify-end">
                            <h3 class="text-on-surface font-semibold text-base mb-0.5 line-clamp-1">{{ $product->name }}</h3>
                            <p class="text-xs text-on-surface-variant/70 mb-4">{{ $product->category ?? 'Uncategorized' }}</p>
                            <div class="flex justify-between items-center">
                                <span class="font-mono text-base font-bold text-primary">IDR {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                                <div class="w-8 h-8 rounded-lg bg-surface-container-highest flex items-center justify-center group-hover:bg-primary group-hover:text-on-primary transition-colors shadow-sm">
                                    <span class="material-symbols-outlined text-lg" data-icon="add">add</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-24 text-on-surface-variant/40">
                        <span class="material-symbols-outlined text-[64px] mb-4" data-icon="inventory_2">inventory_2</span>
                        <p class="text-lg font-semibold">Belum ada produk</p>
                        <p class="text-sm mt-1">Tambahkan produk melalui panel Inventaris</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Right Side: Order Summary -->
        <aside class="w-[420px] bg-surface-container flex flex-col border-l border-white/5 shadow-2xl relative z-10">

            <!-- ===== TAB TOGGLE ===== -->
            <div class="flex border-b border-white/5">
                <button wire:click="switchTab('manual')"
                    class="flex-1 py-3 text-sm font-semibold flex items-center justify-center gap-2 transition-colors
                        {{ $activeTab === 'manual' ? 'text-primary border-b-2 border-primary bg-surface-container-high' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[18px]">point_of_sale</span>
                    Manual
                </button>
                <button wire:click="switchTab('online')"
                    class="flex-1 py-3 text-sm font-semibold flex items-center justify-center gap-2 transition-colors
                        {{ $activeTab === 'online' ? 'text-primary border-b-2 border-primary bg-surface-container-high' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span>
                    Scan to Order
                </button>
            </div>

            @if($activeTab === 'manual')
            {{-- =================== TAB MANUAL =================== --}}
            <!-- Summary Header -->
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold tracking-tight">Order Summary</h2>
                    <p class="text-xs text-on-surface-variant mt-1 font-mono">ORDER #{{ $orderNumber }}</p>
                </div>
                <button wire:click="clearCart" class="p-2 text-on-surface-variant hover:text-error transition-colors">
                    <span class="material-symbols-outlined" data-icon="delete">delete</span>
                </button>
            </div>
            <!-- Items List -->
            <div class="flex-grow overflow-y-auto p-4 custom-scrollbar space-y-4">
                @forelse($cart as $id => $item)
                    <div class="flex gap-4 bg-surface-container-high/50 p-3 rounded-xl border border-white/5 group">
                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-surface-container-highest shrink-0">
                            @if(!empty($item['image']))
                                <img alt="Item" class="w-full h-full object-cover" src="{{ $item['image'] }}"/>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant/30">
                                    <span class="material-symbols-outlined text-[24px]">inventory_2</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between">
                                <h4 class="text-sm font-semibold text-on-surface line-clamp-1">{{ $item['name'] }}</h4>
                                <span class="font-mono text-sm font-bold text-primary">IDR {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-xs text-on-surface-variant">IDR {{ number_format($item['price'], 0, ',', '.') }} / unit</span>
                                <div class="flex items-center gap-3 bg-surface-container-highest rounded-lg p-1 px-2 border border-white/5">
                                    <button wire:click.stop="decreaseQty({{ $id }})" class="text-on-surface-variant hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-sm">remove</span>
                                    </button>
                                    <span class="font-mono text-sm font-bold">{{ $item['qty'] }}</span>
                                    <button wire:click.stop="increaseQty({{ $id }})" class="text-on-surface-variant hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex h-full items-center justify-center text-on-surface-variant/50">
                        <p class="text-sm font-mono">Cart is empty</p>
                    </div>
                @endforelse
            </div>

            @else
            {{-- =================== TAB SCAN TO ORDER =================== --}}
            <div class="p-5 border-b border-white/5">
                <p class="text-xs text-on-surface-variant mb-3 font-mono uppercase tracking-widest">Kode Pesanan Pelanggan</p>
                <div class="flex gap-2">
                    <input wire:model="guestOrderCode"
                           wire:keydown.enter="loadGuestOrder"
                           type="text"
                           placeholder="Contoh: KSR-8B3F2"
                           class="flex-1 bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-sm font-mono uppercase text-on-surface placeholder:text-on-surface-variant/40 focus:outline-none focus:ring-2 focus:ring-primary/40"/>
                    <button wire:click="loadGuestOrder"
                            class="px-4 py-3 rounded-xl bg-primary-container text-white font-semibold text-sm flex items-center gap-1 hover:bg-[#059669] transition-colors active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </button>
                </div>
            </div>

            <div class="flex-grow overflow-y-auto p-4 custom-scrollbar">
                @if($loadedGuestOrder)
                    <!-- Info Pesanan -->
                    <div class="mb-4 bg-surface-container-high rounded-2xl p-4 border border-primary/20">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[18px]">table_restaurant</span>
                                <span class="font-semibold text-sm">{{ $loadedGuestOrder['table'] }}</span>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-mono
                                {{ $loadedGuestOrder['payment_method'] === 'QRIS' ? 'bg-green-900/40 text-green-400' : 'bg-surface-container-highest text-on-surface-variant' }}">
                                {{ $loadedGuestOrder['payment_method'] === 'QRIS' ? '✅ QRIS Lunas' : '💵 Bayar di Kasir' }}
                            </span>
                        </div>
                        <p class="text-xs text-on-surface-variant font-mono">{{ $loadedGuestOrder['order_code'] }}</p>
                    </div>

                    <!-- Items dari Guest Order -->
                    <div class="space-y-3">
                        @forelse($cart as $id => $item)
                            <div class="bg-surface-container-high/50 p-3 rounded-xl border border-white/5">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-semibold text-on-surface line-clamp-1">{{ $item['name'] }}</span>
                                    <span class="font-mono text-sm font-bold text-primary">IDR {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-on-surface-variant">x{{ $item['qty'] }} @ IDR {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </div>
                                @if(!empty($item['notes']))
                                    <p class="text-xs text-primary/70 mt-1.5 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">sticky_note_2</span>
                                        {{ $item['notes'] }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-on-surface-variant/50 text-center py-8">Tidak ada item</p>
                        @endforelse
                    </div>

                    <button wire:click="cancelGuestOrder"
                            class="mt-4 w-full py-2 rounded-xl border border-error/30 text-error text-sm hover:bg-error/10 transition-colors">
                        Batalkan & Kembali
                    </button>
                @else
                    <div class="flex flex-col items-center justify-center h-48 text-on-surface-variant/40">
                        <span class="material-symbols-outlined text-[48px] mb-3">qr_code_scanner</span>
                        <p class="text-sm">Masukkan kode pesanan pelanggan</p>
                        <p class="text-xs mt-1">Contoh: KSR-8B3F2</p>
                    </div>
                @endif
            </div>
            @endif

            <!-- Calculation Section (shared) -->
            <div class="p-6 bg-surface-container-high border-t border-white/10 rounded-t-3xl shadow-[-20px_0_40px_rgba(0,0,0,0.3)]">
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">Subtotal</span>
                        <span class="font-mono text-on-surface">IDR {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">Tax (11%)</span>
                        <span class="font-mono text-on-surface">IDR {{ number_format($this->taxAmount, 0, ',', '.') }}</span>
                    </div>
                    <div class="h-px bg-white/5 my-2"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold">Total Bill</span>
                        <span class="font-mono text-2xl font-black text-primary">IDR {{ number_format($this->total, 0, ',', '.') }}</span>
                    </div>
                </div>
                <button 
                    wire:click="checkout" 
                    wire:loading.attr="disabled"
                    class="w-full py-5 rounded-2xl bg-[#10b981] hover:bg-[#059669] text-white font-bold text-lg tracking-widest shadow-xl shadow-[#10b981]/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:hover:scale-100"
                >
                    <span wire:loading.remove wire:target="checkout" class="material-symbols-outlined text-2xl" data-icon="payments" data-weight="fill">payments</span>
                    <span wire:loading wire:target="checkout" class="material-symbols-outlined text-2xl animate-spin" data-icon="progress_activity">progress_activity</span>
                    <span wire:loading.remove wire:target="checkout">
                        {{ $activeTab === 'online' && $loadedGuestOrder ? 'PROSES PESANAN' : 'BAYAR SEKARANG' }}
                    </span>
                    <span wire:loading wire:target="checkout">MEMPROSES...</span>
                </button>
            </div>
        </aside>
    </main>

    <!-- Payment Success Modal -->
    <div 
        x-data="{ show: false }"
        x-on:payment-successful.window="show = true; setTimeout(() => show = false, 3500)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-90 translate-y-4"
        style="display: none;"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm"
    >
        <div class="bg-surface-container rounded-3xl p-8 flex flex-col items-center shadow-2xl border border-white/10 max-w-sm w-full mx-4">
            <div class="w-20 h-20 bg-primary-container rounded-full flex items-center justify-center mb-6 shadow-[0_0_20px_#10b981]">
                <span class="material-symbols-outlined text-[40px] text-on-primary-container">check_circle</span>
            </div>
            <h2 class="text-2xl font-bold text-on-surface mb-2">Pembayaran Berhasil!</h2>
            <p class="text-on-surface-variant text-center mb-6">Terima kasih, data pesanan telah tersimpan.</p>
            <button @click="show = false" class="w-full py-3 rounded-xl bg-surface-container-high hover:bg-surface-highest transition-colors font-semibold">Tutup</button>
        </div>
    </div>

    <!-- Notification Toast -->
    <div
        x-data="{ show: false, message: '', type: 'error' }"
        x-on:notify.window="message = $event.detail.message; type = $event.detail.type; show = true; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        style="display: none;"
        class="fixed bottom-6 right-6 z-[100]"
    >
        <div class="px-6 py-4 rounded-xl shadow-2xl border border-white/10 flex items-center gap-3 bg-surface-container-high"
             :class="type === 'error' ? 'border-error/30' : 'border-primary/30'">
            <span class="material-symbols-outlined" :class="type === 'error' ? 'text-error' : 'text-primary'" x-text="type === 'error' ? 'error' : 'check_circle'"></span>
            <span class="text-sm font-medium text-on-surface" x-text="message"></span>
        </div>
    </div>
</div>
