<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 drop-shadow-xl" data-aos="fade-up" data-aos-duration="800">
    <!-- Revenue Card -->
    <div class="bg-gray-800/80 backdrop-blur-md border border-gray-700/50 rounded-2xl p-6 hover:shadow-cyan-500/10 hover:shadow-2xl transition-all duration-300">
        <h3 class="text-gray-400 text-sm font-medium tracking-wider uppercase mb-2">Total Pendapatan</h3>
        <p class="text-3xl font-bold text-white tracking-tight">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
    </div>

    <!-- Profit Card -->
    <div class="bg-gray-800/80 backdrop-blur-md border border-gray-700/50 rounded-2xl p-6 hover:shadow-green-500/10 hover:shadow-2xl transition-all duration-300">
        <h3 class="text-gray-400 text-sm font-medium tracking-wider uppercase mb-2">Keuntungan Bersih</h3>
        <p class="text-3xl font-bold {{ $profit >= 0 ? 'text-green-400' : 'text-red-400' }} tracking-tight">
            Rp {{ number_format($profit, 0, ',', '.') }}
        </p>
    </div>

    <!-- Low Stock Card -->
    <div class="bg-gray-800/80 backdrop-blur-md border border-gray-700/50 rounded-2xl p-6 hover:shadow-orange-500/10 hover:shadow-2xl transition-all duration-300">
        <h3 class="text-gray-400 text-sm font-medium tracking-wider uppercase mb-2">Peringatan Stok</h3>
        <p class="text-3xl font-bold {{ $lowStockCount > 0 ? 'text-orange-400 animate-pulse' : 'text-white' }} tracking-tight">
            {{ $lowStockCount }} <span class="text-lg font-normal text-gray-500">Barang</span>
        </p>
    </div>
</div>
