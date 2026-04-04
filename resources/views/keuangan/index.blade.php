<x-layouts.app :title="'Keuangan'">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-white mb-2">Keuangan</h1>
            <p class="text-slate-400 max-w-lg">Pantau arus kas dan kesehatan finansial warung Anda dengan transparansi penuh.</p>
        </div>
        <div class="flex gap-3">
            <button class="btn btn-primary">
                <span class="material-symbols-outlined">add</span>
                Catat Pemasukan
            </button>
            <button class="btn btn-danger">
                <span class="material-symbols-outlined">remove</span>
                Catat Pengeluaran
            </button>
        </div>
    </div>

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
            <h3 class="text-3xl font-bold font-jb text-on-surface tracking-tighter">Rp 3.000.000</h3>
        </div>

        <div class="glass-card inner-glow p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-7xl">trending_down</span>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">shopping_bag</span>
                </div>
                <span class="text-secondary text-xs font-bold font-jb tracking-tighter">-5% ↘</span>
            </div>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Pengeluaran</p>
            <h3 class="text-3xl font-bold font-jb text-on-surface tracking-tighter">Rp 2.500.000</h3>
        </div>

        <div class="glass-card inner-glow p-6 rounded-2xl ai-glow-violet relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity text-tertiary">
                <span class="material-symbols-outlined text-7xl">auto_awesome</span>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-tertiary/10 flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined">insights</span>
                </div>
                <span class="text-tertiary text-xs font-bold font-jb tracking-tighter">+8% ↗</span>
            </div>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Laba Bersih</p>
            <h3 class="text-3xl font-bold font-jb text-on-surface tracking-tighter">Rp 500.000</h3>
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
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-5 font-jb text-white">12 Okt 2023</td>
                    <td class="px-6 py-5"><span class="px-3 py-1 bg-primary/10 text-primary text-[10px] rounded-full font-bold uppercase tracking-wider">Penjualan</span></td>
                    <td class="px-6 py-5 text-slate-300 font-medium">Penjualan Paket Glow 10pcs</td>
                    <td class="px-6 py-5 text-right font-jb text-primary font-bold">+Rp 1.500.000</td>
                </tr>
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-5 font-jb text-white">11 Okt 2023</td>
                    <td class="px-6 py-5"><span class="px-3 py-1 bg-secondary/10 text-secondary text-[10px] rounded-full font-bold uppercase tracking-wider">Operasional</span></td>
                    <td class="px-6 py-5 text-slate-300 font-medium">Biaya Pengiriman Logistik</td>
                    <td class="px-6 py-5 text-right font-jb text-error font-bold">-Rp 250.000</td>
                </tr>
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-5 font-jb text-white">10 Okt 2023</td>
                    <td class="px-6 py-5"><span class="px-3 py-1 bg-tertiary/10 text-tertiary text-[10px] rounded-full font-bold uppercase tracking-wider">Marketing</span></td>
                    <td class="px-6 py-5 text-slate-300 font-medium">Iklan Instagram Stories</td>
                    <td class="px-6 py-5 text-right font-jb text-error font-bold">-Rp 500.000</td>
                </tr>
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-5 font-jb text-white">09 Okt 2023</td>
                    <td class="px-6 py-5"><span class="px-3 py-1 bg-primary/10 text-primary text-[10px] rounded-full font-bold uppercase tracking-wider">Penjualan</span></td>
                    <td class="px-6 py-5 text-slate-300 font-medium">Restock Member Gold</td>
                    <td class="px-6 py-5 text-right font-jb text-primary font-bold">+Rp 1.500.000</td>
                </tr>
            </tbody>
        </table>
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

</x-layouts.app>
