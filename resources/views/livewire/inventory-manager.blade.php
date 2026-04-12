<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
        <div>
            <h2 class="text-4xl font-extrabold tracking-tight text-white mb-2">Inventaris</h2>
            <p class="text-slate-400 font-medium">Kelola stok produk warung Anda dengan presisi AI.</p>
        </div>
        <button wire:click="openModal" class="btn btn-primary" id="btn-tambah-produk">
            <span class="material-symbols-outlined">add</span>
            Tambah Produk
        </button>
    </div>

    @if (session()->has('message'))
        <div class="glass-card p-4 rounded-xl mb-6 border border-primary/30 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">check_circle</span>
            <span class="text-sm font-medium text-primary">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Overview Stats --}}
    @php
        $userId = auth()->id();
        $totalProducts = \App\Models\Product::where('user_id', $userId)->count();
        $outOfStock = \App\Models\Product::where('user_id', $userId)->where('current_stock', 0)->count();
        $lowStock = \App\Models\Product::where('user_id', $userId)->where('current_stock', '>', 0)->whereColumn('current_stock', '<=', 'min_stock_alert')->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 stagger-enter">
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-2">Total Produk</p>
            <p class="text-4xl font-bold text-white font-jb tracking-tighter">{{ $totalProducts }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl border-l-4 border-error/50">
            <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-2">Stok Habis</p>
            <p class="text-4xl font-bold text-error font-jb tracking-tighter">{{ $outOfStock }}</p>
            <p class="mt-4 text-xs text-slate-500 font-medium">Perlu segera restock</p>
        </div>
        <div class="glass-card p-6 rounded-2xl border-l-4 border-amber-400/50">
            <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-2">Stok Menipis</p>
            <p class="text-4xl font-bold text-amber-400 font-jb tracking-tighter">{{ $lowStock }}</p>
            <p class="mt-4 text-xs text-slate-500 font-medium">Di bawah batas aman</p>
        </div>
        <div class="glass-card p-6 rounded-2xl bg-gradient-to-br from-surface-container to-primary/5">
            <p class="text-primary text-[11px] font-bold uppercase tracking-wider mb-2">Total Aset Stok</p>
            <p class="text-sm text-slate-300 leading-relaxed font-medium font-jb">
                Rp {{ number_format(\App\Models\Product::where('user_id', $userId)->selectRaw('SUM(buy_price * current_stock) as total')->value('total') ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Product Table --}}
    <div class="glass-card rounded-2xl overflow-hidden mb-8">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5">
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-500">Produk</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-500">Beli</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-500">Jual</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-500">Stok</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-500 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 stagger-enter">
                @forelse ($products as $product)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 shrink-0">
                                <span class="material-symbols-outlined">inventory_2</span>
                            </div>
                            <div>
                                <p class="font-bold text-white">{{ $product->name }}</p>
                                <p class="text-xs text-slate-500">ID: #{{ $product->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-jb text-sm font-medium">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 font-jb text-sm font-medium text-primary">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if ($product->current_stock == 0)
                            <span class="relative px-3 py-1.5 text-[10px] rounded-full bg-error/20 text-error font-bold uppercase tracking-wider border border-error/30">
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-error"></span>
                                </span>
                                {{ $product->current_stock }} unit
                            </span>
                        @elseif ($product->current_stock <= $product->min_stock_alert)
                            <span class="px-3 py-1.5 text-[10px] rounded-full bg-amber-400/20 text-amber-400 font-bold uppercase tracking-wider border border-amber-400/30">{{ $product->current_stock }} unit</span>
                        @else
                            <span class="px-3 py-1.5 text-[10px] rounded-full bg-primary/20 text-primary font-bold uppercase tracking-wider border border-primary/30">{{ $product->current_stock }} unit</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button wire:click="edit({{ $product->id }})" class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">edit</span></button>
                        <button wire:click="delete({{ $product->id }})" wire:confirm="Yakin ingin menghapus produk ini?" class="p-2 text-slate-500 hover:text-error transition-colors"><span class="material-symbols-outlined">delete</span></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <span class="material-symbols-outlined text-4xl text-slate-600 mb-3 block">inventory_2</span>
                        <p class="text-slate-500 font-medium">Belum ada data produk.</p>
                        <p class="text-xs text-slate-600 mt-1">Klik "Tambah Produk" untuk mulai.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pb-24">
        {{ $products->links(data: ['scrollTo' => false]) }}
    </div>

    {{-- Modal Tambah Produk --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto" x-data x-init="$el.querySelector('input[type=text]')?.focus()">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
        
        <div class="glass-card-strong w-full max-w-lg rounded-2xl p-6 relative z-10 shadow-2xl border border-white/10 m-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">{{ $product_id ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
                <button wire:click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block font-bold text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Nama Produk</label>
                    <input type="text" wire:model="name" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="Cth: Indomie Goreng">
                    @error('name') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Harga Beli</label>
                        <input type="number" wire:model="buy_price" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                        @error('buy_price') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Harga Jual</label>
                        <input type="number" wire:model="sell_price" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                        @error('sell_price') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Stok Awal</label>
                        <input type="number" wire:model="current_stock" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                        @error('current_stock') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Peringatan Stok ≤</label>
                        <input type="number" wire:model="min_stock_alert" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="5">
                    </div>
                </div>
                <div class="pt-4 flex gap-3 justify-end">
                    <button type="button" wire:click="closeModal" class="btn btn-ghost border border-white/10">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
