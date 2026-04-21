<div>
    {{-- AI Insight Banner (Hanya muncul jika ada stok minim) --}}
    @if($lowStockCount > 0)
    <div class="mb-8 relative overflow-hidden glass-card rounded-2xl p-6 border border-violet-500/20 ai-glow flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-violet-600/10 blur-[80px]"></div>
        <div class="flex items-center gap-4 z-10">
            <div class="w-12 h-12 rounded-xl bg-violet-500/20 flex items-center justify-center text-violet-400">
                <span class="material-symbols-outlined">bolt</span>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white tracking-tight">Ada {{ $lowStockCount }} barang stok menipis hari ini</h3>
                <p class="text-sm text-slate-400">Segera pesan ke supplier untuk menjaga kelancaran operasional.</p>
            </div>
        </div>
        <a href="{{ route('inventaris.index') }}" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-500 text-white font-bold rounded-xl text-sm transition-all hover:scale-105 active:scale-95 flex items-center gap-2 z-10 shrink-0" wire:navigate>
            Lihat Detail
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>
    @endif

    {{-- Metric Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Revenue --}}
        <div class="glass-card rounded-2xl p-6 hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
            </div>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Pendapatan</p>
            <h4 class="text-3xl font-bold font-jb text-white tracking-tight">Rp {{ number_format($revenue, 0, ',', '.') }}</h4>
        </div>

        {{-- Profit --}}
        <div class="glass-card rounded-2xl p-6 hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">savings</span>
                </div>
            </div>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Keuntungan</p>
            <h4 class="text-3xl font-bold font-jb text-white tracking-tight">Rp {{ number_format($profit, 0, ',', '.') }}</h4>
        </div>

        {{-- Low Stock --}}
        <div class="glass-card rounded-2xl p-6 hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                @if($lowStockCount > 0)
                <span class="text-[10px] font-bold text-amber-500 bg-amber-500/10 px-2 py-1 rounded-full">Kritis</span>
                @endif
            </div>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Stok Minim</p>
            <h4 class="text-3xl font-bold font-jb text-white tracking-tight">{{ $lowStockCount }} <span class="text-sm font-normal text-slate-500">items</span></h4>
        </div>
    </div>

    {{-- Main Grid Layout (Grafik & Transaksi) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-24">
        {{-- Chart Section --}}
        <div class="lg:col-span-2 glass-card rounded-2xl p-8 relative overflow-hidden group">
            <div class="flex justify-between items-center mb-8 relative z-10">
                <div>
                    <h2 class="text-xl font-bold text-white">Grafik Penjualan</h2>
                    <p class="text-xs text-slate-500 mt-1">Statistik Pemasukan {{ $chartFilter === 'monthly' ? '6 bulan' : '7 hari' }} terakhir</p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="setChartFilter('weekly')" class="px-3 py-1.5 rounded-lg {{ $chartFilter === 'weekly' ? 'bg-surface-container-highest text-slate-300' : 'hover:bg-surface-container-highest text-slate-500 transition-colors' }} text-xs font-bold">Mingguan</button>
                    <button wire:click="setChartFilter('monthly')" class="px-3 py-1.5 rounded-lg {{ $chartFilter === 'monthly' ? 'bg-surface-container-highest text-slate-300' : 'hover:bg-surface-container-highest text-slate-500 transition-colors' }} text-xs font-bold">Bulanan</button>
                </div>
            </div>

            {{-- Bar Chart Dinamis --}}
            <div class="h-64 w-full flex items-end justify-between relative z-10 group-hover:opacity-90 transition-opacity">
                @foreach($chartBars as $i => $h)
                <div class="flex-1 flex flex-col items-center gap-2">
                    <div class="w-full bg-primary/20 rounded-t-lg relative transition-all duration-500" style="height: {{ $h * 4 }}px">
                        <div class="absolute top-0 w-full h-1 bg-primary @if($i === 6) shadow-[0_-8px_16px_rgba(78,222,163,0.3)] @endif"></div>
                    </div>
                    <span class="text-[10px] font-bold {{ $i === 6 ? 'text-primary' : 'text-slate-600' }}">{{ $chartLabels[$i] }}</span>
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
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Transaksi Terakhir</h3>
                <div class="space-y-4">
                    @forelse($recentTransactions as $tx)
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg {{ $tx->type == 'INCOME' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-error/10 text-error' }} flex items-center justify-center">
                                <span class="material-symbols-outlined">{{ $tx->type == 'INCOME' ? 'north_east' : 'south_west' }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ $tx->category ?? 'Transaksi' }}</p>
                                <p class="text-[10px] text-slate-500 font-jb">{{ $tx->created_at->format('d M, H:i') }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold {{ $tx->type == 'INCOME' ? 'text-emerald-400' : 'text-error' }} font-jb">
                            {{ $tx->type == 'INCOME' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-500">Belum ada transaksi.</p>
                    @endforelse
                </div>
                <a href="{{ route('keuangan.index') }}" class="w-full mt-6 py-2 text-[10px] font-bold text-slate-400 hover:text-white transition-colors uppercase tracking-widest border-t border-white/5 pt-4 block text-center" wire:navigate>Lihat Semua Riwayat</a>
            </div>
        </div>
    </div>
</div>