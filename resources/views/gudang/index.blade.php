<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-white mb-2">Gudang Digital</h1>
            <p class="text-slate-400 max-w-lg">Kelola arus masuk dan keluar barang secara real-time dengan asisten cerdas Flowmerce.</p>
        </div>
        <a href="{{ route('inventaris.index') }}" wire:navigate class="btn btn-primary">
            <span class="material-symbols-outlined">add_circle</span>
            Inventaris Produk
        </a>
    </div>

    {{-- Overview Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card inner-glow p-6 rounded-xl">
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-4">Total Stok Digital</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-jb font-bold text-white">{{ number_format($totalStock, 0, ',', '.') }}</span>
                <span class="text-xs text-slate-400">Unit</span>
            </div>
        </div>
        <div class="glass-card inner-glow p-6 rounded-xl border-l-4 border-primary">
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-4">Barang Masuk (Hari Ini)</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-jb font-bold text-primary">+{{ number_format($inToday, 0, ',', '.') }}</span>
                <span class="text-xs text-slate-400">Unit</span>
            </div>
        </div>
        <div class="glass-card inner-glow p-6 rounded-xl border-l-4 border-error">
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-4">Barang Keluar (Hari Ini)</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-jb font-bold text-error">-{{ number_format($outToday, 0, ',', '.') }}</span>
                <span class="text-xs text-slate-400">Unit</span>
            </div>
        </div>
        <div class="glass-card inner-glow p-6 rounded-xl bg-gradient-to-br from-violet-600/10 to-transparent">
            <p class="text-[10px] text-violet-400 font-bold uppercase tracking-widest mb-4">Akurasi Opname</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-jb font-bold text-white">100%</span>
                <span class="material-symbols-outlined text-violet-400 text-sm">verified</span>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="grid grid-cols-1 gap-8">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">Timeline Arus Barang</h3>
                <div class="flex gap-2">
                    <button class="text-xs px-4 py-2 rounded-full bg-surface-container-high text-slate-300">Semua</button>
                    <button class="text-xs px-4 py-2 rounded-full bg-surface-container-high text-slate-300">Masuk</button>
                    <button class="text-xs px-4 py-2 rounded-full bg-surface-container-high text-slate-300">Keluar</button>
                    <button class="text-xs px-4 py-2 rounded-full border border-white/10 text-primary flex items-center gap-1"><span class="material-symbols-outlined text-sm">file_download</span> Export PDF</button>
                </div>
            </div>

            <div class="relative space-y-8 before:absolute before:left-[19px] before:top-2 before:bottom-2 before:w-[2px] before:bg-white/5">
                {{-- Date Header --}}
                <div class="relative z-10 flex items-center mb-6">
                    <div class="w-10 h-10 rounded-full bg-surface-container-lowest border-2 border-white/10 flex items-center justify-center text-slate-400">
                        <span class="material-symbols-outlined text-sm">history</span>
                    </div>
                    <span class="ml-4 text-xs font-bold uppercase tracking-widest text-slate-500">Riwayat Terkini</span>
                </div>

                @forelse($logs as $log)
                <div class="relative z-10 flex items-start gap-4 mb-4">
                    <div class="w-10 h-10 shrink-0 rounded-full {{ $log->type == 'IN' ? 'bg-primary/20 text-primary border-primary/30' : 'bg-error/20 text-error border-error/30' }} flex items-center justify-center border">
                        <span class="material-symbols-outlined">{{ $log->type == 'IN' ? 'north_east' : 'south_west' }}</span>
                    </div>
                    <div class="glass-card inner-glow flex-1 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-slate-400">
                                <span class="material-symbols-outlined">inventory_2</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">{{ $log->product->name ?? 'Produk Dihapus' }}</h4>
                                <p class="text-xs text-slate-500">{{ $log->reason }} • <span class="text-slate-400">Ref: #LOG-{{ $log->id }}</span></p>
                            </div>
                        </div>
                        <div class="flex flex-row sm:flex-col items-center sm:items-end gap-1">
                            <span class="text-xl font-jb font-bold {{ $log->type == 'IN' ? 'text-primary' : 'text-error' }}">
                                {{ $log->type == 'IN' ? '+' : '-' }}{{ $log->quantity }}
                            </span>
                            <span class="text-[10px] font-jb text-slate-500 uppercase">{{ $log->created_at->format('d M y, H:i') }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="pl-12 pt-4">
                    <p class="text-sm text-slate-500">Belum ada aktivitas stok gudang.</p>
                </div>
                @endforelse
                
                <div class="pl-12 pt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="h-20 md:hidden"></div>

</div>
