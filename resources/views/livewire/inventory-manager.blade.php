<div class="bg-gray-800/80 backdrop-blur-md border border-gray-700/50 rounded-2xl p-6 mb-8 drop-shadow-xl" data-aos="fade-up" data-aos-duration="1000">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-white tracking-wide">📦 Inventaris Produk</h2>
        <button wire:click="openModal" class="bg-cyan-600 hover:bg-cyan-500 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-lg shadow-cyan-500/30">
            + Tambah Produk
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-500/20 border border-green-500/50 text-green-400 p-3 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-700 text-gray-400 text-sm uppercase tracking-wider">
                    <th class="p-3">Nama Produk</th>
                    <th class="p-3">Harga Beli</th>
                    <th class="p-3">Harga Jual</th>
                    <th class="p-3">Stok</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                @forelse ($products as $product)
                <tr class="border-b border-gray-700/50 hover:bg-gray-700/20 transition-colors">
                    <td class="p-3 font-medium text-white">{{ $product->name }}</td>
                    <td class="p-3">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
                    <td class="p-3">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                    <td class="p-3 font-bold">{{ $product->current_stock }}</td>
                    <td class="p-3">
                        @if ($product->current_stock == 0)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400 border border-red-500/50">Habis</span>
                        @elseif ($product->current_stock <= $product->min_stock_alert)
                            <span class="px-2 py-1 text-xs rounded-full bg-orange-500/20 text-orange-400 border border-orange-500/50 hover:animate-pulse">Menipis</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400 border border-green-500/50">Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">Belum ada data produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $products->links(data: ['scrollTo' => false]) }}
    </div>

    <!-- Modal Tambah Produk -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
        <div class="bg-gray-900 rounded-2xl w-full max-w-md p-6 border border-gray-700 shadow-2xl" @click.away="$wire.closeModal()">
            <h3 class="text-xl font-bold text-white mb-4">Tambah Produk Baru</h3>
            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Nama Produk</label>
                        <input type="text" wire:model="name" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-white outline-none focus:border-cyan-500 transition-colors">
                        @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Harga Beli</label>
                            <input type="number" wire:model="buy_price" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-white outline-none focus:border-cyan-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Harga Jual</label>
                            <input type="number" wire:model="sell_price" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-white outline-none focus:border-cyan-500 transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Stok Awal</label>
                            <input type="number" wire:model="current_stock" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-white outline-none focus:border-cyan-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Peringatan Stok <</label>
                            <input type="number" wire:model="min_stock_alert" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-white outline-none focus:border-cyan-500 transition-colors">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-lg text-gray-400 hover:text-white transition-colors">Batal</button>
                    <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
