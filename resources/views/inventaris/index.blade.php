<x-layouts.app :title="'Inventaris'">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4" x-data="{ modalOpen: false }">
        <div>
            <h2 class="text-4xl font-extrabold tracking-tight text-white mb-2">Inventaris</h2>
            <p class="text-slate-400 font-medium">Kelola stok produk warung Anda dengan presisi AI.</p>
        </div>
        <button class="btn btn-primary" id="btn-tambah-produk" @click="modalOpen = true">
            <span class="material-symbols-outlined">add</span>
            Tambah Produk
        </button>

        {{-- Dummy Modal --}}
        <template x-teleport="body">
            <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto" x-cloak>
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="modalOpen = false" x-transition.opacity></div>
                
                <div class="glass-card-strong w-full max-w-lg rounded-2xl p-6 relative z-10 shadow-2xl border border-white/10 m-4"
                     x-show="modalOpen" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-white">Tambah Produk Baru</h3>
                        <button @click="modalOpen = false" class="text-slate-400 hover:text-white transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <form class="space-y-4">
                        <div>
                            <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Nama Produk</label>
                            <input type="text" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="Cth: Indomie Goreng">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Harga Beli</label>
                                <input type="number" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Harga Jual</label>
                                <input type="number" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Stok Awal</label>
                                <input type="number" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Kategori</label>
                                <select class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30">
                                    <option>Sembako</option>
                                    <option>Minuman</option>
                                    <option>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="pt-4 flex gap-3 justify-end">
                            <button type="button" @click="modalOpen = false" class="btn btn-ghost border border-white/10">Batal</button>
                            <button type="button" @click="modalOpen = false" class="btn btn-primary">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
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
            <p class="text-4xl font-bold text-error font-jb tracking-tighter">3</p>
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
                {{-- Row 1: Aman --}}
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 shrink-0">
                                <span class="material-symbols-outlined">cookie</span>
                            </div>
                            <div>
                                <p class="font-bold text-white">Gula Pasir 1kg</p>
                                <p class="text-xs text-slate-500">Sembako • SKU-9231</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-jb text-sm font-medium">Rp14.500</td>
                    <td class="px-6 py-4 font-jb text-sm font-medium text-primary">Rp16.000</td>
                    <td class="px-6 py-4">
                        <span class="badge-stock-aman">24 unit</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">edit</span></button>
                        <button class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">more_vert</span></button>
                    </td>
                </tr>

                {{-- Row 2: Menipis --}}
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 shrink-0">
                                <span class="material-symbols-outlined">water_drop</span>
                            </div>
                            <div>
                                <p class="font-bold text-white">Minyak Goreng 2L</p>
                                <p class="text-xs text-slate-500">Sembako • SKU-1044</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-jb text-sm font-medium">Rp32.000</td>
                    <td class="px-6 py-4 font-jb text-sm font-medium text-primary">Rp35.500</td>
                    <td class="px-6 py-4">
                        <span class="badge-stock-menipis">2 unit</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">edit</span></button>
                        <button class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">more_vert</span></button>
                    </td>
                </tr>

                {{-- Row 3: Habis --}}
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-800 flex items-center justify-center text-slate-600 opacity-50 shrink-0">
                                <span class="material-symbols-outlined">rice_bowl</span>
                            </div>
                            <div>
                                <p class="font-bold text-slate-400">Beras Pandan Wangi 5kg</p>
                                <p class="text-xs text-slate-600">Sembako • SKU-0021</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-jb text-sm font-medium text-slate-500">Rp72.000</td>
                    <td class="px-6 py-4 font-jb text-sm font-medium text-slate-500">Rp80.000</td>
                    <td class="px-6 py-4">
                        <span class="relative badge-stock-habis">
                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-error"></span>
                            </span>
                            0 unit
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">edit</span></button>
                        <button class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">more_vert</span></button>
                    </td>
                </tr>

                {{-- Row 4: Aman --}}
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 shrink-0">
                                <span class="material-symbols-outlined">egg</span>
                            </div>
                            <div>
                                <p class="font-bold text-white">Telur Ayam 1kg</p>
                                <p class="text-xs text-slate-500">Sembako • SKU-5521</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-jb text-sm font-medium">Rp26.500</td>
                    <td class="px-6 py-4 font-jb text-sm font-medium text-primary">Rp28.000</td>
                    <td class="px-6 py-4">
                        <span class="badge-stock-aman">24 unit</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">edit</span></button>
                        <button class="p-2 text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">more_vert</span></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-24">
        <p class="text-slate-500 text-sm font-medium">Menampilkan <span class="text-white font-bold font-jb">1-10</span> dari <span class="text-white font-bold font-jb">24</span> produk</p>
        <div class="flex gap-2">
            <button class="w-10 h-10 flex items-center justify-center rounded-xl glass-card text-slate-500 hover:text-white transition-all">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-primary text-on-primary font-bold font-jb">1</button>
            <button class="w-10 h-10 flex items-center justify-center rounded-xl glass-card text-slate-300 hover:bg-white/10 transition-all font-jb">2</button>
            <button class="w-10 h-10 flex items-center justify-center rounded-xl glass-card text-slate-300 hover:bg-white/10 transition-all font-jb">3</button>
            <button class="w-10 h-10 flex items-center justify-center rounded-xl glass-card text-slate-500 hover:text-white transition-all">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>
    </div>

</x-layouts.app>
