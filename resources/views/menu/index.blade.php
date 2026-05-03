<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <title>{{ $owner->business_name ?? 'Menu' }} — {{ $table->table_number }}</title>
    <meta name="description" content="Scan to Order — Pesan langsung dari meja Anda.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { 
                extend: { 
                    colors: {
                        primary: '#4edea3', 'primary-container': '#10b981',
                        surface: '#0f131f', 'surface-container': '#1b1f2c',
                        'surface-container-high': '#262a37', 'surface-container-highest': '#313442',
                        'on-surface': '#dfe2f3', 'on-surface-variant': '#bbcabf',
                        error: '#ffb4ab', 'surface-container-lowest': '#0a0e1a',
                    }, 
                    fontFamily: { 
                        body: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    } 
                } 
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; }
        [x-cloak] { display: none !important; }
        .no-scroll { overflow: hidden; }
        input[type=text]:focus { outline: none; }
        
        /* Custom Scrollbar for desktop */
        @media (min-width: 1024px) {
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #313442; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #4edea3; }
        }
        /* Hide scrollbar for mobile categories */
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-surface text-on-surface font-body min-h-screen flex flex-col selection:bg-primary selection:text-on-primary overflow-hidden" x-data="menuApp()" :class="{'no-scroll': (cartOpen && window.innerWidth < 1024) || qrisOpen || orderDone}">

{{-- ===== HEADER ===== --}}
<header class="fixed top-0 w-full z-50 flex justify-between items-center px-4 lg:px-8 h-16 bg-[#0a0e1a] bg-opacity-50 backdrop-blur-xl border-b border-white/5 shadow-[0_8px_32px_rgba(5,7,10,0.4)]">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-on-primary shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">restaurant_menu</span>
        </div>
        <div>
            <p class="text-[10px] text-on-surface-variant font-medium tracking-wider uppercase">{{ $owner->business_name ?? 'Flowmerce' }}</p>
            <h1 class="text-sm lg:text-base font-bold text-primary tracking-tight">{{ $table->table_number }}</h1>
        </div>
    </div>
    <button @click="cartOpen = true" class="lg:hidden relative p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
        <span class="material-symbols-outlined text-on-surface-variant text-[20px]">shopping_cart</span>
        <span x-show="cartCount > 0" x-cloak x-text="cartCount"
              class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-primary-container text-white text-[9px] font-bold rounded-full flex items-center justify-center shadow-md"></span>
    </button>
</header>

<div class="h-[calc(100vh-4rem)] mt-16 flex flex-col lg:flex-row max-w-[1600px] mx-auto w-full">
    
    {{-- ===== LEFT SIDE: MENU ===== --}}
    <main class="flex-1 flex flex-col min-w-0 lg:p-6 overflow-hidden">
        
        {{-- ===== CATEGORY TABS ===== --}}
        <div class="flex-none flex items-center gap-3 mb-4 lg:mb-6 overflow-x-auto pb-2 scrollbar-none px-4 pt-4 lg:p-0">
            @foreach($categories as $cat)
            <button @click="activeCategory = '{{ $cat }}'"
                    :class="activeCategory === '{{ $cat }}' ? 'bg-primary-container text-white shadow-lg shadow-primary/20' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'"
                    class="px-6 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition-all duration-200">
                {{ $cat }}
            </button>
            @endforeach
        </div>

        {{-- ===== PRODUCT GRID ===== --}}
        <div class="flex-1 min-h-0 grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 lg:gap-5 overflow-y-auto px-4 lg:px-2 pt-3 -mt-3 pb-32 lg:pb-8 content-start custom-scrollbar">
            @foreach($products as $product)
            <div x-show="activeCategory === 'Semua' || activeCategory === '{{ addslashes($product->category ?? '') }}'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->sell_price }}, {{ $product->current_stock }}, '{{ $product->image }}')"
                 class="group bg-surface-container rounded-2xl overflow-hidden cursor-pointer hover:-translate-y-1 hover:ring-2 hover:ring-primary/40 transition-all duration-300 shadow-md flex flex-col h-[260px] lg:h-[280px]">
                
                <div class="relative flex-1 bg-surface-container-high flex items-center justify-center overflow-hidden">
                    <div class="absolute top-2 right-2 lg:top-3 lg:right-3 text-[9px] lg:text-[10px] font-mono font-bold @if($product->current_stock < 5) text-error @else text-primary @endif tracking-wider z-10 bg-surface-container-highest/80 backdrop-blur-sm px-2 py-1 rounded-md shadow-sm">
                        {{ $product->current_stock }} STOCK
                    </div>
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-500"/>
                    @else
                        <span class="material-symbols-outlined text-[64px] text-on-surface-variant/20 group-hover:scale-110 transition-transform duration-500">restaurant</span>
                    @endif
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors duration-300"></div>
                </div>
                
                <div class="p-3 lg:p-4 bg-surface-container flex flex-col justify-end">
                    <h3 class="text-sm lg:text-base font-semibold text-on-surface line-clamp-1 mb-0.5">{{ $product->name }}</h3>
                    <p class="text-[10px] lg:text-xs text-on-surface-variant/70 mb-3 lg:mb-4 uppercase tracking-wide">{{ $product->category ?? 'Lainnya' }}</p>
                    
                    <div class="flex justify-between items-center mt-auto">
                        <span class="text-sm lg:text-base font-bold text-primary font-mono tracking-tight">IDR {{ number_format($product->sell_price * 1.11, 0, ',', '.') }}</span>
                        <div class="w-8 h-8 lg:w-9 lg:h-9 rounded-lg bg-surface-container-highest flex items-center justify-center group-hover:bg-primary group-hover:text-on-primary transition-colors duration-300 shadow-sm">
                            <span class="material-symbols-outlined text-[18px] lg:text-[20px]">add</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>

    {{-- ===== RIGHT SIDE: CART (Desktop Sidebar & Mobile Sheet) ===== --}}
    
    {{-- Mobile Backdrop --}}
    <div x-show="cartOpen" x-cloak 
         class="lg:hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-40" 
         @click="cartOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    {{-- Cart Container --}}
    <aside :class="cartCount === 0 ? 'translate-y-[120%] lg:translate-y-0' : (cartOpen ? 'translate-y-0' : 'translate-y-[calc(100%-84px)] lg:translate-y-0')"
           class="fixed lg:relative top-auto bottom-0 left-0 right-0 z-50 lg:z-10 w-full lg:w-[420px] bg-surface-container flex flex-col rounded-t-[2rem] lg:rounded-none shadow-[0_-10px_40px_rgba(0,0,0,0.3)] lg:shadow-[-20px_0_40px_rgba(0,0,0,0.1)] transition-transform duration-400 ease-[cubic-bezier(0.2,1,0.2,1)] will-change-transform border-t lg:border-t-0 lg:border-l border-white/5 h-[85vh] lg:h-full">
        
        <div class="flex-1 flex flex-col h-full bg-surface-container lg:bg-transparent overflow-hidden rounded-t-[2rem] lg:rounded-none">
            
            {{-- Cart Header --}}
            <div @click="if(window.innerWidth < 1024) cartOpen = !cartOpen" class="flex-none flex items-center justify-between px-6 py-5 lg:py-6 border-b border-white/5 bg-surface-container lg:bg-transparent cursor-pointer lg:cursor-default rounded-t-[2rem] lg:rounded-none">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg lg:text-xl tracking-tight text-on-surface">Pesanan Anda</h2>
                        <p class="text-xs text-on-surface-variant font-mono" x-show="cartCount > 0" x-text="cartCount + ' item terpilih'"></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div x-show="!cartOpen && cartCount > 0" x-cloak class="lg:hidden font-mono font-bold text-primary text-base">
                        IDR <span x-text="formatRp(total)"></span>
                    </div>
                    <button @click.stop="cartOpen = !cartOpen" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-transparent border border-white/10 text-on-surface-variant hover:text-white hover:bg-white/5 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[24px]" x-text="cartOpen ? 'keyboard_arrow_down' : 'keyboard_arrow_up'"></span>
                    </button>
                    <button @click.stop="clearCart()" x-show="cartCount > 0" class="hidden lg:flex p-2 text-on-surface-variant hover:text-error transition-colors" title="Kosongkan Keranjang">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>
            
            {{-- Cart Items --}}
            <div class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-3 lg:space-y-4">
                <template x-if="cartCount === 0">
                    <div class="h-full flex flex-col items-center justify-center text-on-surface-variant/40 space-y-3">
                        <span class="material-symbols-outlined text-[64px] lg:text-[80px]">shopping_basket</span>
                        <p class="text-sm lg:text-base font-medium">Keranjang masih kosong</p>
                        <p class="text-xs lg:text-sm text-center px-8">Silakan pilih menu yang tersedia untuk mulai memesan.</p>
                    </div>
                </template>

                <template x-for="(item, id) in cart" :key="id">
                    <div class="bg-surface-container-high rounded-2xl p-3 lg:p-4 border border-white/5 flex gap-3 lg:gap-4 group">
                        {{-- Thumbnail --}}
                        <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-xl bg-surface-container-highest flex-shrink-0 overflow-hidden flex items-center justify-center">
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.name" class="w-full h-full object-cover"/>
                            </template>
                            <template x-if="!item.image">
                                <span class="material-symbols-outlined text-[24px] lg:text-[32px] text-on-surface-variant/30">restaurant</span>
                            </template>
                        </div>
                        
                        <div class="flex-1 flex flex-col min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <p class="font-semibold text-sm lg:text-base text-on-surface line-clamp-1" x-text="item.name"></p>
                                <button @click="removeItem(id)" class="text-on-surface-variant hover:text-error transition-colors lg:opacity-0 group-hover:opacity-100 flex-shrink-0">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                            </div>
                            
                            <span class="font-mono text-sm lg:text-base font-bold text-primary mb-2" x-text="'IDR ' + formatRp(item.displayPrice * item.qty)"></span>
                            
                            <div class="flex items-center justify-between mt-auto">
                                <div class="flex items-center bg-surface-container-lowest rounded-lg border border-white/5">
                                    <button @click="decreaseQty(id)" class="w-8 h-8 flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors active:scale-95">
                                        <span class="material-symbols-outlined text-[16px]">remove</span>
                                    </button>
                                    <span class="font-mono text-xs lg:text-sm font-bold w-6 text-center" x-text="item.qty"></span>
                                    <button @click="increaseQty(id)" class="w-8 h-8 flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors active:scale-95">
                                        <span class="material-symbols-outlined text-[16px]">add</span>
                                    </button>
                                </div>
                                <span class="text-[10px] lg:text-xs text-on-surface-variant font-mono" x-text="'IDR ' + formatRp(item.displayPrice) + '/ea'"></span>
                            </div>

                            {{-- Notes --}}
                            <div class="mt-3 relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-[14px] text-on-surface-variant/50">edit_note</span>
                                </div>
                                <input type="text" placeholder="Catatan (opsional)" x-model="item.notes"
                                       class="w-full bg-surface-container-lowest border border-white/5 rounded-lg pl-8 pr-3 py-1.5 text-xs lg:text-sm text-on-surface placeholder:text-on-surface-variant/40 focus:ring-1 focus:ring-primary/50 transition-shadow"/>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Cart Footer / Checkout --}}
            <div class="flex-none p-5 lg:p-6 bg-surface-container-high border-t border-white/10 lg:rounded-t-3xl lg:shadow-[-20px_0_40px_rgba(0,0,0,0.3)] z-20 relative">
                <div class="space-y-3 mb-5 lg:mb-6">
                    <div class="flex justify-between items-center text-sm lg:text-base">
                        <span class="text-on-surface-variant">Subtotal</span>
                        <span class="font-mono text-on-surface">IDR <span x-text="formatRp(subtotalDisplay)"></span></span>
                    </div>
                    <div class="flex justify-between items-center text-sm lg:text-base">
                        <span class="text-on-surface-variant flex items-center gap-1">Pajak (11%)</span>
                        <span class="font-mono text-on-surface">IDR <span x-text="formatRp(taxDisplay)"></span></span>
                    </div>
                    <div class="h-px w-full bg-white/5 my-2"></div>
                    <div class="flex justify-between items-end">
                        <span class="text-base lg:text-lg font-bold text-on-surface">Total Bill</span>
                        <span class="font-mono text-xl lg:text-2xl font-black text-primary tracking-tight">IDR <span x-text="formatRp(total)"></span></span>
                    </div>
                </div>

                <div x-show="cartCount > 0" x-transition>
                    <p class="text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Metode Pembayaran</p>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button @click="paymentMethod = 'CASHIER'"
                                :class="paymentMethod === 'CASHIER' ? 'bg-primary-container border-primary-container text-white ring-2 ring-primary/30' : 'bg-surface-container-highest border-white/10 text-on-surface hover:bg-surface-bright'"
                                class="flex flex-col items-center justify-center gap-2 p-3 lg:p-4 rounded-xl border transition-all duration-200">
                            <span class="material-symbols-outlined text-[24px]">point_of_sale</span>
                            <span class="text-xs lg:text-sm font-bold">Bayar di Kasir</span>
                        </button>
                        <button @click="paymentMethod = 'QRIS'"
                                :class="paymentMethod === 'QRIS' ? 'bg-[#00a6e0] border-[#00a6e0] text-white ring-2 ring-[#00a6e0]/30' : 'bg-surface-container-highest border-white/10 text-on-surface hover:bg-surface-bright'"
                                class="flex flex-col items-center justify-center gap-2 p-3 lg:p-4 rounded-xl border transition-all duration-200">
                            <span class="material-symbols-outlined text-[24px]">qr_code_2</span>
                            <span class="text-xs lg:text-sm font-bold">QRIS / e-Wallet</span>
                        </button>
                    </div>

                    <div x-show="errorMsg" x-cloak class="text-xs text-error bg-error/10 border border-error/20 rounded-lg p-2 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        <span x-text="errorMsg"></span>
                    </div>

                    <button @click="handleOrder()" :disabled="loading"
                            class="relative overflow-hidden w-full py-4 lg:py-5 rounded-2xl bg-primary-container hover:bg-[#059669] text-white font-bold text-base lg:text-lg tracking-wide disabled:opacity-70 disabled:cursor-not-allowed active:scale-[0.98] transition-all flex items-center justify-center gap-2 shadow-xl shadow-primary-container/20 group">
                        <span x-show="!loading" class="flex items-center gap-2">
                            Pesan Sekarang 
                            <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </span>
                        <span x-show="loading" x-cloak class="flex items-center gap-2">
                            <span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </aside>
</div>



{{-- ===== QRIS MODAL SCREEN ===== --}}
<div x-show="qrisOpen" x-cloak class="fixed inset-0 z-[60] bg-surface/95 backdrop-blur-md flex flex-col items-center justify-center p-4 lg:p-8"
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
    <div class="w-full max-w-md bg-surface-container rounded-[2rem] p-6 lg:p-8 shadow-2xl border border-white/10 flex flex-col items-center relative overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#00a6e0]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>

        <button @click="qrisOpen = false; cartOpen = true" class="absolute top-6 left-6 w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:text-white hover:bg-surface-bright transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </button>

        <div class="w-16 h-16 rounded-2xl bg-[#00a6e0]/10 flex items-center justify-center text-[#00a6e0] mb-4 mt-2">
            <span class="material-symbols-outlined text-[32px]">qr_code_scanner</span>
        </div>
        
        <h2 class="text-2xl font-bold mb-2 text-center">Bayar dengan QRIS</h2>
        <p class="text-on-surface-variant text-sm text-center mb-8 px-4">Scan QR Code di bawah menggunakan aplikasi e-Wallet atau Mobile Banking Anda.</p>
        
        <div class="bg-white rounded-[2rem] p-4 mb-8 shadow-lg ring-8 ring-white/5">
            @if($owner->gopay_qr_image)
                <img src="{{ Storage::url($owner->gopay_qr_image) }}" alt="QRIS" class="w-56 h-56 lg:w-64 lg:h-64 object-contain rounded-xl"/>
            @else
                <div class="w-56 h-56 lg:w-64 lg:h-64 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <span class="material-symbols-outlined text-[64px] mb-2">qr_code_2</span>
                    <p class="text-sm text-center px-4">QR belum dikonfigurasi admin</p>
                </div>
            @endif
        </div>
        
        <div class="w-full bg-surface-container-highest rounded-2xl p-5 mb-8 text-center border border-white/5">
            <p class="text-sm text-on-surface-variant mb-1 uppercase tracking-widest text-[10px] font-semibold">Total Tagihan</p>
            <p class="text-3xl lg:text-4xl font-black text-primary font-mono tracking-tight">IDR <span x-text="formatRp(total)"></span></p>
            <p class="text-xs text-on-surface-variant mt-2">Pastikan nominal sesuai sebelum membayar</p>
        </div>
        
        <div x-show="errorMsg" x-cloak class="w-full text-xs text-error bg-error/10 rounded-lg py-2 px-4 mb-4 text-center border border-error/20" x-text="errorMsg"></div>
        
        <button @click="submitOrder()" :disabled="loading"
                class="w-full py-4 rounded-2xl bg-[#00a6e0] hover:bg-[#008cc0] text-white font-bold text-base lg:text-lg disabled:opacity-70 active:scale-[0.98] transition-all shadow-lg shadow-[#00a6e0]/20 flex items-center justify-center gap-2">
            <span x-show="!loading" class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                Saya Sudah Bayar
            </span>
            <span x-show="loading" x-cloak class="flex items-center gap-2">
                <span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>
                Memverifikasi...
            </span>
        </button>
    </div>
</div>

{{-- ===== ORDER SUCCESS SCREEN ===== --}}
<div x-show="orderDone" x-cloak class="fixed inset-0 z-[70] bg-surface flex flex-col items-center justify-center p-4 lg:p-8"
     x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
    <div class="w-full max-w-md bg-surface-container rounded-[2rem] p-6 lg:p-8 shadow-2xl border border-primary/20 flex flex-col items-center text-center relative overflow-hidden">
        {{-- Confetti effect overlay (simplified CSS version) --}}
        <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(circle at 50% 0%, rgba(16, 185, 129, 0.15) 0%, transparent 70%);"></div>

        <div class="w-24 h-24 bg-primary-container/20 rounded-full flex items-center justify-center mb-6 ring-8 ring-primary-container/10 relative z-10 animate-[bounce_2s_infinite]">
            <span class="material-symbols-outlined text-[48px] text-primary" style="font-variation-settings:'FILL' 1">task_alt</span>
        </div>
        
        <h2 class="text-3xl font-bold mb-2 text-on-surface relative z-10">Pesanan Berhasil!</h2>
        <p class="text-on-surface-variant text-sm mb-8 relative z-10 px-4">Tunjukkan kode ini kepada kasir untuk memproses pesanan Anda.</p>
        
        <div class="w-full bg-surface-container-high rounded-[1.5rem] p-6 mb-6 relative z-10 border border-white/5 shadow-inner">
            <p class="text-[10px] font-semibold text-on-surface-variant mb-2 font-mono uppercase tracking-[0.2em]">Kode Antrean Anda</p>
            <p class="text-4xl lg:text-5xl font-black text-primary font-mono tracking-widest mb-6 py-2" x-text="orderCode"></p>
            
            <div class="bg-white rounded-2xl p-3 inline-block shadow-md">
                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=0&color=0f131f&data=' + orderCode"
                     alt="QR Code Pesanan" class="w-40 h-40 lg:w-44 lg:h-44"/>
            </div>
        </div>
        
        <div class="w-full bg-surface-container-highest rounded-2xl p-5 text-left space-y-3 relative z-10 border border-white/5">
            <div class="flex justify-between items-center text-sm border-b border-white/5 pb-2">
                <span class="text-on-surface-variant flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">table_restaurant</span> Meja</span>
                <span class="font-bold text-on-surface">{{ $table->table_number }}</span>
            </div>
            <div class="flex justify-between items-center text-sm border-b border-white/5 pb-2">
                <span class="text-on-surface-variant flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">receipt_long</span> Total Bill</span>
                <span class="font-mono font-bold text-primary text-base">IDR <span x-text="formatRp(total)"></span></span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-on-surface-variant flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">payments</span> Metode</span>
                <span class="font-bold flex items-center gap-1 text-on-surface">
                    <span x-text="paymentMethod === 'QRIS' ? 'QRIS / e-Wallet' : 'Bayar Kasir'"></span>
                    <span x-show="paymentMethod === 'QRIS'" class="material-symbols-outlined text-[16px] text-primary" style="font-variation-settings:'FILL' 1">check_circle</span>
                </span>
            </div>
        </div>
        
        <div class="mt-6 flex items-center gap-2 text-[11px] text-on-surface-variant bg-surface-container-lowest px-4 py-2 rounded-full border border-white/5">
            <span class="material-symbols-outlined text-[14px] text-yellow-500">timer</span>
            <span>Kode akan hangus dalam <strong>45 menit</strong> jika tidak diproses.</span>
        </div>
    </div>
</div>

<script>
function menuApp() {
    return {
        activeCategory: 'Semua',
        cart: {},
        cartOpen: window.innerWidth >= 1024, // Open by default on desktop
        qrisOpen: false,
        orderDone: false,
        paymentMethod: 'CASHIER',
        orderCode: '',
        loading: false,
        errorMsg: '',

        init() {
            // Handle window resize to auto-open/close cart
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024 && !this.qrisOpen && !this.orderDone) {
                    this.cartOpen = true;
                }
            });
        },

        get cartCount() {
            return Object.values(this.cart).reduce((s, i) => s + i.qty, 0);
        },
        get subtotal() {
            return Object.values(this.cart).reduce((s, i) => s + (i.price * i.qty), 0);
        },
        // We calculate display amounts for the UI to be clear
        get subtotalDisplay() {
            return this.subtotal;
        },
        get taxDisplay() {
            return this.subtotal * 0.11;
        },
        get total() {
            // This matches the math in the controller (price * qty * 1.11)
            return Object.values(this.cart).reduce((s, i) => s + (i.displayPrice * i.qty), 0);
        },

        addToCart(id, name, basePrice, stock, image) {
            const displayPrice = Math.round(basePrice * 1.11);
            if (this.cart[id]) {
                if (this.cart[id].qty < stock) {
                    this.cart[id].qty++;
                }
            } else {
                // Force reactivity by recreating the object
                this.cart = { 
                    ...this.cart, 
                    [id]: { name, price: basePrice, displayPrice, qty: 1, stock, image, notes: '' } 
                };
            }
            
            // Auto open cart on mobile when first item added
            if (window.innerWidth < 1024 && this.cartCount === 1) {
                this.cartOpen = true;
            }
        },
        increaseQty(id) {
            if (this.cart[id] && this.cart[id].qty < this.cart[id].stock) {
                this.cart[id].qty++;
            }
        },
        decreaseQty(id) {
            if (!this.cart[id]) return;
            if (this.cart[id].qty > 1) {
                this.cart[id].qty--;
            } else {
                this.removeItem(id);
            }
        },
        removeItem(id) {
            const newCart = { ...this.cart };
            delete newCart[id];
            this.cart = newCart;
            
            if (this.cartCount === 0 && window.innerWidth < 1024) {
                this.cartOpen = false;
            }
        },
        clearCart() {
            if(confirm('Kosongkan semua pesanan?')) {
                this.cart = {};
                if (window.innerWidth < 1024) this.cartOpen = false;
            }
        },

        handleOrder() {
            if (this.cartCount === 0) return;
            
            if (this.paymentMethod === 'QRIS') {
                this.cartOpen = false;
                this.qrisOpen = true;
                // Scroll to top for mobile
                window.scrollTo(0,0);
            } else {
                this.submitOrder();
            }
        },

        async submitOrder() {
            this.loading = true;
            this.errorMsg = '';
            
            const items = Object.entries(this.cart).map(([id, item]) => ({
                product_id: parseInt(id),
                quantity: item.qty,
                notes: item.notes || null
            }));
            
            try {
                const resp = await fetch('{{ route("guest.order.store", $tableCode) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ items, payment_method: this.paymentMethod })
                });
                
                const data = await resp.json();
                
                if (data.success) {
                    this.orderCode = data.order_code;
                    this.cartOpen = false;
                    this.qrisOpen = false;
                    this.orderDone = true;
                    window.scrollTo(0,0);
                } else {
                    this.errorMsg = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                }
            } catch(e) {
                this.errorMsg = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
            } finally {
                this.loading = false;
            }
        },

        formatRp(num) {
            return Math.round(num || 0).toLocaleString('id-ID');
        }
    };
}
</script>
</body>
</html>
