<x-layouts.app :title="'Dashboard'">

    {{-- AI Insight Banner --}}
    <div class="mb-8 relative overflow-hidden glass-card rounded-2xl p-6 border border-violet-500/20 ai-glow flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-violet-600/10 blur-[80px]"></div>
        <div class="flex items-center gap-4 z-10">
            <div class="w-12 h-12 rounded-xl bg-violet-500/20 flex items-center justify-center text-violet-400">
                <span class="material-symbols-outlined">bolt</span>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white tracking-tight">Ada 2 barang stok menipis hari ini</h3>
                <p class="text-sm text-slate-400">Segera pesan ke supplier untuk menjaga kelancaran operasional.</p>
            </div>
        </div>
        <a href="{{ route('inventaris.index') }}" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-500 text-white font-bold rounded-xl text-sm transition-all hover:scale-105 active:scale-95 flex items-center gap-2 z-10 shrink-0" wire:navigate>
            Lihat Detail
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>

    {{-- Metric Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Revenue --}}
        <div class="glass-card rounded-2xl p-6 hover:translate-y-[-4px] transition-transform duration-300 animate-fade-in-up">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded-full">+12%</span>
            </div>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Pendapatan</p>
            <h4 class="text-3xl font-bold font-jb text-white tracking-tight">Rp 3.000k</h4>
        </div>

        {{-- Profit --}}
        <div class="glass-card rounded-2xl p-6 hover:translate-y-[-4px] transition-transform duration-300 animate-fade-in-up">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">savings</span>
                </div>
                <span class="text-[10px] font-bold text-secondary bg-secondary/10 px-2 py-1 rounded-full">+8%</span>
            </div>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Keuntungan</p>
            <h4 class="text-3xl font-bold font-jb text-white tracking-tight">Rp 500k</h4>
        </div>

        {{-- Low Stock --}}
        <div class="glass-card rounded-2xl p-6 hover:translate-y-[-4px] transition-transform duration-300 animate-fade-in-up">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <span class="text-[10px] font-bold text-amber-500 bg-amber-500/10 px-2 py-1 rounded-full">Kritis</span>
            </div>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Stok Minim</p>
            <h4 class="text-3xl font-bold font-jb text-white tracking-tight">5 <span class="text-sm font-normal text-slate-500">items</span></h4>
        </div>
    </div>

    {{-- Main Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-24">

        {{-- Chart Section --}}
        <div class="lg:col-span-2 glass-card rounded-2xl p-8 relative overflow-hidden group">
            <div class="flex justify-between items-center mb-8 relative z-10">
                <div>
                    <h2 class="text-xl font-bold text-white">Grafik Penjualan</h2>
                    <p class="text-xs text-slate-500 mt-1">Statistik 7 hari terakhir</p>
                </div>
                <div class="flex gap-2">
                    <button class="px-3 py-1.5 rounded-lg bg-surface-container-highest text-xs font-bold text-slate-300">Mingguan</button>
                    <button class="px-3 py-1.5 rounded-lg hover:bg-surface-container-highest text-xs font-bold text-slate-500 transition-colors">Bulanan</button>
                </div>
            </div>

            {{-- Bar Chart --}}
            <div class="h-64 w-full flex items-end justify-between relative z-10 group-hover:opacity-90 transition-opacity">
                @php $bars = [32, 44, 36, 52, 48, 60, 56]; $labels = ['SEN','SEL','RAB','KAM','JUM','SAB','MIN']; @endphp
                @foreach($bars as $i => $h)
                <div class="flex-1 flex flex-col items-center gap-2">
                    <div class="w-full bg-primary/20 rounded-t-lg relative" style="height: {{ $h * 4 }}px">
                        <div class="absolute top-0 w-full h-1 bg-primary @if($i === 5) shadow-[0_-8px_16px_rgba(78,222,163,0.3)] @endif"></div>
                    </div>
                    <span class="text-[10px] font-bold {{ $i === 5 ? 'text-primary' : 'text-slate-600' }}">{{ $labels[$i] }}</span>
                </div>
                @if(!$loop->last) <div class="w-4"></div> @endif
                @endforeach
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('inventaris.index') }}" class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all group active:scale-95" wire:navigate>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">add_circle</span>
                            <span class="text-sm font-semibold">Produk Baru</span>
                        </div>
                        <span class="material-symbols-outlined text-slate-600 group-hover:text-white transition-colors">chevron_right</span>
                    </a>
                    <a href="{{ route('keuangan.index') }}" class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all group active:scale-95" wire:navigate>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-secondary">add_card</span>
                            <span class="text-sm font-semibold">Catat Pemasukan</span>
                        </div>
                        <span class="material-symbols-outlined text-slate-600 group-hover:text-white transition-colors">chevron_right</span>
                    </a>
                    <a href="{{ route('keuangan.index') }}" class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all group active:scale-95" wire:navigate>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-error">remove_circle</span>
                            <span class="text-sm font-semibold">Catat Pengeluaran</span>
                        </div>
                        <span class="material-symbols-outlined text-slate-600 group-hover:text-white transition-colors">chevron_right</span>
                    </a>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Transaksi Terakhir</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                                <span class="material-symbols-outlined">north_east</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">Penjualan Seblak</p>
                                <p class="text-[10px] text-slate-500 font-jb">Today, 09:24</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-emerald-400 font-jb">Rp 25.000</span>
                    </div>
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-error/10 flex items-center justify-center text-error">
                                <span class="material-symbols-outlined">south_west</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">Beli Bahan Baku</p>
                                <p class="text-[10px] text-slate-500 font-jb">Today, 08:15</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-error font-jb">-Rp 150.000</span>
                    </div>
                </div>
                <a href="{{ route('keuangan.index') }}" class="w-full mt-6 py-2 text-[10px] font-bold text-slate-400 hover:text-white transition-colors uppercase tracking-widest border-t border-white/5 pt-4 block text-center" wire:navigate>Lihat Semua Riwayat</a>
            </div>
        </div>
    </div>

</x-layouts.app>
