<div class="fm-page">
  <x-ui.page-header eyebrow="Financial Overview" title="Keuangan" description="Pantau transaksi pemasukan dan pengeluaran untuk mengelola kesehatan usaha.">
    <x-slot:actions>
      <x-ui.button variant="secondary" wire:click="openModal('EXPENSE')"><span class="material-symbols-outlined" aria-hidden="true">remove_circle_outline</span>Catat Pengeluaran</x-ui.button>
      <x-ui.button wire:click="openModal('INCOME')"><span class="material-symbols-outlined" aria-hidden="true">add</span>Catat Pemasukan</x-ui.button>
    </x-slot:actions>
  </x-ui.page-header>
  @if(session()->has('message_tx'))<x-ui.feedback>{{ session('message_tx') }}</x-ui.feedback>@endif
  @php
    $userId = auth()->id();
    $totalIncome = \App\Models\Transaction::where('user_id', $userId)->where('type', 'INCOME')->sum('amount');
    $totalExpense = \App\Models\Transaction::where('user_id', $userId)->where('type', 'EXPENSE')->sum('amount');
    $netProfit = $totalIncome - $totalExpense;
  @endphp
  <section class="fm-kpi-grid" aria-label="Ringkasan keuangan">
    <x-ui.stat-card label="Total Pemasukan" :value="'Rp '.number_format($totalIncome, 0, ',', '.')" icon="trending_up" tone="success" hint="Akumulasi transaksi masuk" />
    <x-ui.stat-card label="Total Pengeluaran" :value="'Rp '.number_format($totalExpense, 0, ',', '.')" icon="trending_down" tone="warning" hint="Akumulasi transaksi keluar" />
    <x-ui.stat-card label="Keuntungan Bersih" :value="'Rp '.number_format($netProfit, 0, ',', '.')" icon="account_balance_wallet" variant="accent" hint="Pemasukan − pengeluaran" />
  </section>
  <x-ui.section-header title="Riwayat Transaksi" description="Semua pemasukan dan pengeluaran tercatat di sini.">
    <x-slot:actions><x-ui.badge tone="accent">Catatan Keuangan</x-ui.badge></x-slot:actions>
  </x-ui.section-header>
  <x-ui.table label="Riwayat transaksi keuangan">
    <x-slot:head><tr><th scope="col">Tanggal</th><th scope="col">Jenis transaksi</th><th scope="col">Kategori / Keterangan</th><th scope="col" class="text-right">Nominal</th></tr></x-slot:head>
    @forelse($transactions as $tx)
      <tr wire:key="tx-{{ $tx->id }}">
        <td class="fm-mono fm-nowrap">{{ $tx->created_at->format('d M Y') }}</td>
        <td>@if($tx->type === 'INCOME')<x-ui.badge tone="success">Pemasukan</x-ui.badge>@else<x-ui.badge tone="danger">Pengeluaran</x-ui.badge>@endif</td>
        <td><p class="fm-strong">{{ $tx->category ?: 'Transaksi' }}</p>@if($tx->notes)<p class="fm-data-table__secondary">{{ $tx->notes }}</p>@endif</td>
        <td class="fm-mono fm-nowrap text-right"><strong style="color:{{ $tx->type === 'INCOME' ? 'var(--fm-success)' : 'var(--fm-danger)' }}">{{ $tx->type === 'INCOME' ? '+' : '−' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}</strong></td>
      </tr>
    @empty
      <tr><td colspan="4"><x-ui.empty-state icon="receipt_long" title="Belum ada transaksi" description="Mulai catat pemasukan atau pengeluaran bisnis Anda." /></td></tr>
    @endforelse
    <x-slot:footer>{{ $transactions->links('vendor.pagination.custom', data: ['scrollTo' => false]) }}</x-slot:footer>
  </x-ui.table>
  @if($totalIncome > 0 || $totalExpense > 0)
    <x-ui.card padding="lg">
      <x-ui.section-header title="Ringkasan Laba Rugi" description="Ikhtisar seluruh transaksi yang tercatat.">
        <x-slot:actions><span class="fm-section-eyebrow">Laporan ringkas</span></x-slot:actions>
      </x-ui.section-header>
      <div class="fm-report" style="margin-top:22px">
        <div class="fm-report__row"><span>Jumlah pemasukan</span><strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong></div>
        <div class="fm-report__row"><span>Jumlah pengeluaran</span><strong>−Rp {{ number_format($totalExpense, 0, ',', '.') }}</strong></div>
        <div class="fm-report__row fm-report__total"><span>Keuntungan bersih</span><strong>Rp {{ number_format($netProfit, 0, ',', '.') }}</strong></div>
      </div>
    </x-ui.card>
  @endif

  @if($isModalOpen)
  <x-ui.modal title="Catat Transaksi">
    <form wire:submit.prevent="save" class="fm-form">
      <x-ui.select-field field="type" label="Jenis Transaksi" wire:model="type" required><option value="INCOME">Pemasukan</option><option value="EXPENSE">Pengeluaran</option></x-ui.select-field>
      <x-ui.field field="amount" label="Nominal (Rp)" type="number" min="1" step="any" wire:model="amount" placeholder="0" required />
      <x-ui.field field="category" label="Kategori" wire:model="category" placeholder="Contoh: Penjualan Produk" />
      <x-ui.textarea-field field="notes" label="Catatan (opsional)" rows="3" wire:model="notes" placeholder="Keterangan transaksi"></x-ui.textarea-field>
      <div class="fm-form__actions"><x-ui.button variant="secondary" wire:click="closeModal">Batal</x-ui.button><x-ui.button type="submit" wire:loading.attr="disabled" wire:target="save"><span class="material-symbols-outlined" aria-hidden="true">check</span>Simpan Transaksi</x-ui.button></div>
    </form>
  </x-ui.modal>
  @endif
</div>