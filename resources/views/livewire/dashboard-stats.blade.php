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
</div>