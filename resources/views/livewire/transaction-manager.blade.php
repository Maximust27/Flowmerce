<div class="bg-gray-800/80 backdrop-blur-md border border-gray-700/50 rounded-2xl p-6 drop-shadow-xl mb-8" data-aos="fade-up" data-aos-duration="1200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-white tracking-wide">💼 Riwayat Transaksi</h2>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-lg shadow-indigo-500/30">
            + Catat Transaksi
        </button>
    </div>

    @if (session()->has('message_tx'))
        <div class="bg-green-500/20 border border-green-500/50 text-green-400 p-3 rounded-lg mb-4">
            {{ session('message_tx') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-700 text-gray-400 text-sm uppercase tracking-wider">
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Tipe</th>
                    <th class="p-3">Nominal</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Keterangan</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                @forelse ($transactions as $tx)
                <tr class="border-b border-gray-700/50 hover:bg-gray-700/20 transition-colors">
                    <td class="p-3 text-sm">{{ $tx->created_at->format('d M Y, H:i') }}</td>
                    <td class="p-3">
                        @if ($tx->type == 'INCOME')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400 border border-green-500/50">Pemasukan</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400 border border-red-500/50">Pengeluaran</span>
                        @endif
                    </td>
                    <td class="p-3 font-bold {{ $tx->type == 'INCOME' ? 'text-green-400' : 'text-red-400' }}">
                        {{ $tx->type == 'INCOME' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                    </td>
                    <td class="p-3">{{ $tx->category ?? '-' }}</td>
                    <td class="p-3 text-sm text-gray-400">{{ $tx->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">Belum ada riwayat transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $transactions->links(data: ['scrollTo' => false]) }}
    </div>

    <!-- Modal Catat Transaksi -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
        <div class="bg-gray-900 rounded-2xl w-full max-w-md p-6 border border-gray-700 shadow-2xl" @click.away="$wire.closeModal()">
            <h3 class="text-xl font-bold text-white mb-4">Catat Transaksi</h3>
            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Tipe Transaksi</label>
                        <select wire:model="type" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-white outline-none focus:border-indigo-500 transition-colors">
                            <option value="INCOME">Pemasukan</option>
                            <option value="EXPENSE">Pengeluaran</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Nominal (Rp)</label>
                        <input type="number" wire:model="amount" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-white outline-none focus:border-indigo-500 transition-colors">
                        @error('amount') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Kategori Tambahan</label>
                        <input type="text" wire:model="category" placeholder="Opsional. Cth: Restock" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-white outline-none focus:border-indigo-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Catatan</label>
                        <textarea wire:model="notes" rows="2" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-white outline-none focus:border-indigo-500 transition-colors"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-lg text-gray-400 hover:text-white transition-colors">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
