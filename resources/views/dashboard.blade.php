<div>
    {{-- Memanggil komponen stats secara lazy --}}
    <livewire:dashboard-stats />

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