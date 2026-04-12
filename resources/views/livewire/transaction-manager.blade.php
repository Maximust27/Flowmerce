<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-white mb-2">Keuangan</h1>
            <p class="text-slate-400 max-w-lg">Pantau arus kas dan kesehatan finansial warung Anda dengan transparansi penuh.</p>
        </div>
        <div class="flex gap-3">
            <button wire:click="openModal('INCOME')" class="btn btn-primary">
                <span class="material-symbols-outlined">add</span>
                Catat Pemasukan
            </button>
            <button wire:click="openModal('EXPENSE')" class="btn btn-danger">
                <span class="material-symbols-outlined">remove</span>
                Catat Pengeluaran
            </button>
        </div>
    </div>

    @if (session()->has('message_tx'))
        <div class="glass-card p-4 rounded-xl mb-6 border border-primary/30 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">check_circle</span>
            <span class="text-sm font-medium text-primary">{{ session('message_tx') }}</span>
        </div>
    @endif

    {{-- Summary Metrics --}}
    @php
        $userId = auth()->id();
        $totalIncome = \App\Models\Transaction::where('user_id', $userId)->where('type', 'INCOME')->sum('amount');
        $totalExpense = \App\Models\Transaction::where('user_id', $userId)->where('type', 'EXPENSE')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 stagger-enter">
        <div class="glass-card inner-glow p-6 rounded-2xl ai-glow-emerald relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-7xl">trending_up</span>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Pemasukan</p>
            <h3 class="text-3xl font-bold font-jb text-on-surface tracking-tighter">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
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
            <h3 class="text-3xl font-bold font-jb text-on-surface tracking-tighter">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
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
            <h3 class="text-3xl font-bold font-jb {{ $netProfit >= 0 ? 'text-primary' : 'text-error' }} tracking-tighter">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Transaction Table --}}
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-white">Daftar Transaksi</h3>
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
            <tbody class="divide-y divide-white/5 text-sm stagger-enter">
                @forelse ($transactions as $tx)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-5 font-jb text-white">{{ $tx->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-5">
                        @if ($tx->type == 'INCOME')
                            <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] rounded-full font-bold uppercase tracking-wider">Pemasukan</span>
                        @else
                            <span class="px-3 py-1 bg-error/10 text-error text-[10px] rounded-full font-bold uppercase tracking-wider">Pengeluaran</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-slate-300 font-medium">{{ $tx->notes ?? ($tx->category ?? '-') }}</td>
                    <td class="px-6 py-5 text-right font-jb {{ $tx->type == 'INCOME' ? 'text-primary' : 'text-error' }} font-bold">
                        {{ $tx->type == 'INCOME' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <span class="material-symbols-outlined text-4xl text-slate-600 mb-3 block">receipt_long</span>
                        <p class="text-slate-500 font-medium">Belum ada riwayat transaksi.</p>
                        <p class="text-xs text-slate-600 mt-1">Klik "Catat Pemasukan" untuk mulai.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pb-24">
        {{ $transactions->links(data: ['scrollTo' => false]) }}
    </div>

    {{-- P&L Report --}}
    @if($totalIncome > 0 || $totalExpense > 0)
    <div class="flex justify-between items-end mb-6">
        <div>
            <h3 class="text-xl font-bold text-white">Laporan Laba Rugi (P&L)</h3>
            <p class="text-xs text-slate-500 font-medium">Pratinjau laporan keseluruhan</p>
        </div>
    </div>
    <div class="glass-card inner-glow rounded-2xl p-8 space-y-6 mb-24 stagger-enter">
        <div class="flex justify-between items-center border-b border-white/5 pb-4">
            <span class="text-slate-400 font-medium">Pendapatan Kotor</span>
            <span class="font-jb font-bold text-white">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between items-center border-b border-white/5 pb-4">
            <span class="text-slate-400 font-medium">Total Pengeluaran</span>
            <span class="font-jb font-bold text-error">(Rp {{ number_format($totalExpense, 0, ',', '.') }})</span>
        </div>
        <div class="flex justify-between items-center pt-4">
            <span class="text-lg font-black text-primary uppercase tracking-widest">Laba Bersih</span>
            <div class="bg-primary/20 px-8 py-4 rounded-2xl ai-glow-emerald border border-primary/30">
                <span class="text-3xl font-black font-jb {{ $netProfit >= 0 ? 'text-primary' : 'text-error' }} tracking-tighter">Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal Catat Transaksi --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
        
        <div class="glass-card-strong w-full max-w-lg rounded-2xl p-6 relative z-10 shadow-2xl border border-white/10 m-4"
             x-data
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">Catat Transaksi</h3>
                <button wire:click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block font-bold text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Tipe Transaksi</label>
                    <select wire:model="type" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30">
                        <option value="INCOME">Pemasukan</option>
                        <option value="EXPENSE">Pengeluaran</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Nominal (Rp)</label>
                    <input type="number" wire:model="amount" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm font-jb focus:ring-2 focus:ring-primary/30" placeholder="0">
                    @error('amount') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Kategori</label>
                    <input type="text" wire:model="category" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="Cth: Penjualan, Belanja Stok">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Catatan</label>
                    <textarea wire:model="notes" rows="2" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="Opsional"></textarea>
                </div>
                <div class="pt-4 flex gap-3 justify-end">
                    <button type="button" wire:click="closeModal" class="btn btn-ghost border border-white/10">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
