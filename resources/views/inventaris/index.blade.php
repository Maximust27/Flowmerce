<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
        <div>
            <h2 class="text-4xl font-extrabold tracking-tight text-white mb-2">Inventaris</h2>
            <p class="text-slate-400 font-medium">Kelola stok produk warung Anda dengan presisi AI.</p>
        </div>
        <button class="btn btn-primary" wire:click="openModal">
            <span class="material-symbols-outlined">add</span>
            Tambah Produk
        </button>

        {{-- Livewire Modal --}}
        @if($isModalOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                
                <div class="glass-card-strong w-full max-w-lg rounded-2xl p-6 relative z-10 shadow-2xl border border-white/10 m-4">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-white">Tambah Produk Baru</h3>
                        <button wire:click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <form wire:submit="save" class="space-y-4">
                        <div>
                            <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Nama Produk</label>
                            <input type="text" wire:model="name" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="Cth: Indomie Goreng">
                            @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Harga Beli</label>
                                <input type="number" wire:model="buy_price" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                                @error('buy_price') <span class="text-error text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Harga Jual</label>
                                <input type="number" wire:model="sell_price" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                                @error('sell_price') <span class="text-error text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Stok Awal</label>
                                <input type="number" wire:model="current_stock" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                                @error('current_stock') <span class="text-error text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Batas Stok Minimum</label>
                                <input type="number" wire:model="min_stock_alert" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="5">
                                @error('min_stock_alert') <span class="text-error text-xs">{{ $message }}</span> @enderror
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

    {{-- Overview Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-2">Total Produk</p>
            <p class="text-4xl font-bold text-white font-jb tracking-tighter">24</p>
            <div class="mt-4 flex items-center gap-2 text-primary">
                <span class="material-symbols-outlined text-sm">trending_up</span>
                <span class="text-xs font-bold">+2 minggu ini</span>
            </div>
        </div>
        <div class="glass-card p-6 rounded-2xl border-l-4 border-error/50">
            <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-2">Stok Habis</p>
            <p class="text-4xl font-bold text-error font-jb tracking-tighter">{{ \App\Models\Product::where('current_stock', 0)->count() }}</p>
            <p class="mt-4 text-xs text-slate-500 font-medium">Perlu segera restock</p>
        </div>
        <div class="glass-card p-6 rounded-2xl border-l-4 border-amber-400/50">
            <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-2">Stok Menipis</p>
            <p class="text-4xl font-bold text-amber-400 font-jb tracking-tighter">5</p>
            <p class="mt-4 text-xs text-slate-500 font-medium">Di bawah batas aman</p>
        </div>
        <div class="glass-card p-6 rounded-2xl bg-gradient-to-br from-surface-container to-primary/5">
            <p class="text-primary text-[11px] font-bold uppercase tracking-wider mb-2">Rekomendasi AI</p>
            <p class="text-sm text-slate-300 leading-relaxed font-medium">Beras 5kg diprediksi habis dalam 2 hari berdasarkan tren.</p>
        </div>
    </div>

    {{-- Search & Filters --}}
    <div class="glass-card p-4 rounded-2xl flex flex-wrap items-center gap-4 mb-8">
        <div class="relative flex-1 min-w-[280px]">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">search</span>
            <input class="w-full !pl-12 !pr-4" type="text" placeholder="Cari nama produk atau SKU..." id="search-product">
        </div>
        <div class="flex items-center gap-3">
            <select class="!appearance-none cursor-pointer">
                <option>Semua Kategori</option>
                <option>Sembako</option>
                <option>Kebutuhan Rumah</option>
                <option>Makanan Ringan</option>
            </select>
            <select class="!appearance-none cursor-pointer">
                <option>Semua Status</option>
                <option>Aman</option>
                <option>Menipis</option>
                <option>Habis</option>
            </select>
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
            <tbody class="divide-y divide-white/5">
                @forelse($products as $product)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 shrink-0">
                                <span class="material-symbols-outlined">inventory_2</span>
                            </div>
                            <div>
                                <p class="font-bold text-white">{{ $product->name }}</p>
                                <p class="text-xs text-slate-500">ID: {{ $product->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-jb text-sm font-medium text-slate-300">Rp{{ number_format($product->buy_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 font-jb text-sm font-medium text-primary">Rp{{ number_format($product->sell_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if ($product->current_stock == 0)
                            <span class="relative badge-stock-habis">
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-error"></span>
                                </span>
                                0 unit
                            </span>
                        @elseif ($product->current_stock <= $product->min_stock_alert)
                            <span class="badge-stock-menipis">{{ $product->current_stock }} unit</span>
                        @else
                            <span class="badge-stock-aman">{{ $product->current_stock }} unit</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">edit</span></button>
                        <button class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">delete</span></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-slate-500">Toko baru? Silahkan "Tambah Produk".</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 pb-24 text-slate-300">
        {{ $products->links('vendor.pagination.custom', data: ['scrollTo' => false]) }}
    </div>

</div>
