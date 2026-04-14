<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-white mb-2">Keuangan</h1>
            <p class="text-slate-400 max-w-lg">Pantau arus kas dan kesehatan finansial warung Anda dengan transparansi penuh.</p>
        </div>
        <div class="flex gap-3">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-white mb-2">Keuangan</h1>
            <p class="text-slate-400 max-w-lg">Pantau arus kas dan kesehatan finansial warung Anda dengan transparansi penuh.</p>
        </div>
        <div class="flex gap-3">
            <button class="btn btn-primary" wire:click="openModal('INCOME')">
                <span class="material-symbols-outlined">add</span>
                Catat Pemasukan
            </button>
            <button class="btn btn-danger" wire:click="openModal('EXPENSE')">
                <span class="material-symbols-outlined">remove</span>
                Catat Pengeluaran
            </button>
        </div>
    </div>

    {{-- Livewire Modal --}}
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
            
            <div class="glass-card-strong w-full max-w-lg rounded-2xl p-6 relative z-10 shadow-2xl border border-white/10 m-4">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Catat {{ $type === 'INCOME' ? 'Pemasukan' : 'Pengeluaran' }}</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Nominal (Rp)</label>
                        <input type="number" wire:model="amount" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                        @error('amount') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Kategori</label>
                        <input type="text" wire:model="category" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="Cth: Penjualan Harian">
                        @error('category') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Catatan Ekstra</label>
                        <textarea wire:model="notes" rows="2" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="(Opsional)"></textarea>
                    </div>
                    
                    <div class="pt-4 flex gap-3 justify-end">
                        <button type="button" wire:click="closeModal" class="btn btn-ghost border border-white/10">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Summary Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card inner-glow p-6 rounded-2xl ai-glow-emerald relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-7xl">trending_up</span>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
                <span class="text-primary text-xs font-bold font-jb tracking-tighter">+12% ↗</span>
            </div>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Pemasukan</p>
            <h3 class="text-3xl font-bold font-jb text-on-surface tracking-tighter">Rp {{ number_format(\App\Models\Transaction::where('type', 'INCOME')->sum('amount'), 0, ',', '.') }}</h3>
        </div>

        <div class="glass-card inner-glow p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-7xl">trending_down</span>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">shopping_bag</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Pengeluaran</p>
            <h3 class="text-3xl font-bold font-jb text-on-surface tracking-tighter">Rp {{ number_format(\App\Models\Transaction::where('type', 'EXPENSE')->sum('amount'), 0, ',', '.') }}</h3>
        </div>

        <div class="glass-card inner-glow p-6 rounded-2xl ai-glow-violet relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity text-tertiary">
                <span class="material-symbols-outlined text-7xl">auto_awesome</span>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-tertiary/10 flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined">insights</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Laba Bersih</p>
            <h3 class="text-3xl font-bold font-jb text-on-surface tracking-tighter">Rp {{ number_format(\App\Models\Transaction::where('type', 'INCOME')->sum('amount') - \App\Models\Transaction::where('type', 'EXPENSE')->sum('amount'), 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Transaction Table --}}
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-white">Daftar Transaksi</h3>
        <div class="flex items-center gap-3">
            <div class="bg-surface-container-high px-4 py-2 rounded-xl flex items-center gap-3 cursor-pointer hover:bg-surface-variant transition-colors border border-white/5">
                <span class="material-symbols-outlined text-slate-400 text-sm">calendar_today</span>
                <span class="text-xs font-bold text-slate-300">Bulan Ini</span>
                <span class="material-symbols-outlined text-slate-500 text-sm">expand_more</span>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-2xl overflow-hidden mb-8">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5">
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-500">Tanggal</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-500">Kategori</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-500">Deskripsi</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-sm">
                @forelse($transactions as $tx)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-5 font-jb text-white">{{ $tx->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 {{ $tx->type === 'INCOME' ? 'bg-primary/10 text-primary' : 'bg-error/10 text-error' }} text-[10px] rounded-full font-bold uppercase tracking-wider">
                            {{ $tx->type }}
                        </span>
                    </td>
                    <td class="px-6 py-5 text-slate-300 font-medium">{{ $tx->category }} <br><span class="text-xs text-slate-500">{{ $tx->notes }}</span></td>
                    <td class="px-6 py-5 text-right font-jb {{ $tx->type === 'INCOME' ? 'text-primary' : 'text-error' }} font-bold">
                        {{ $tx->type === 'INCOME' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada data transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t border-white/5">
            {{ $transactions->links() }}
        </div>
    </div>

    {{-- P&L Report --}}
    <div class="flex justify-between items-end mb-6">
        <div>
            <h3 class="text-xl font-bold text-white">Laporan Laba Rugi (P&L)</h3>
            <p class="text-xs text-slate-500 font-medium">Pratinjau laporan periode Oktober 2023</p>
        </div>
        <button class="flex items-center gap-2 px-4 py-2 border border-secondary text-secondary rounded-xl font-bold text-xs hover:bg-secondary/10 transition-colors">
            <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
            <span>Download PDF</span>
        </button>
    </div>

    <div class="glass-card inner-glow rounded-2xl p-8 space-y-6 mb-24">
        <div class="flex justify-between items-center border-b border-white/5 pb-4">
            <span class="text-slate-400 font-medium">Pendapatan Kotor</span>
            <span class="font-jb font-bold text-white">Rp 3.000.000</span>
        </div>
        <div class="flex justify-between items-center border-b border-white/5 pb-4">
            <span class="text-slate-400 font-medium">Harga Pokok Penjualan (HPP)</span>
            <span class="font-jb font-bold text-error">(Rp 1.500.000)</span>
        </div>
        <div class="flex justify-between items-center bg-surface-container-high/30 p-4 rounded-xl">
            <span class="text-on-surface font-bold">Laba Kotor</span>
            <span class="font-jb text-primary font-bold text-lg">Rp 1.500.000</span>
        </div>
        <div class="flex justify-between items-center border-b border-white/5 pb-4 px-1">
            <span class="text-slate-400 font-medium">Biaya Operasional</span>
            <span class="font-jb font-bold text-error">(Rp 1.000.000)</span>
        </div>
        <div class="flex justify-between items-center pt-4">
            <span class="text-lg font-black text-primary uppercase tracking-widest">Laba Bersih</span>
            <div class="bg-primary/20 px-8 py-4 rounded-2xl ai-glow-emerald border border-primary/30">
                <span class="text-3xl font-black font-jb text-primary tracking-tighter">Rp 500.000</span>
            </div>
        </div>
    </div>

</div>
