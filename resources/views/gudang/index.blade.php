<div class="fm-page">
  <x-ui.page-header eyebrow="Operasional Stok" title="Gudang Digital" description="Lihat pergerakan barang dan riwayat masuk-keluar stok secara runtut.">
    <x-slot:actions><x-ui.button href="{{ route('inventaris.index') }}"><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span>Kelola Produk</x-ui.button></x-slot:actions>
  </x-ui.page-header>
  <section class="fm-kpi-grid" aria-label="Ringkasan stok gudang">
    <x-ui.stat-card label="Total Stok" :value="number_format($totalStock, 0, ',', '.').' unit'" icon="warehouse" hint="Ketersediaan seluruh produk" />
    <x-ui.stat-card label="Barang Masuk Hari Ini" :value="'+'.number_format($inToday, 0, ',', '.').' unit'" icon="move_to_inbox" tone="success" hint="Perubahan stok masuk hari ini" />
    <x-ui.stat-card label="Barang Keluar Hari Ini" :value="'−'.number_format($outToday, 0, ',', '.').' unit'" icon="outbox" variant="accent" hint="Perubahan stok keluar hari ini" />
  </section>
  <x-ui.section-header title="Aktivitas Gudang" description="Riwayat penyesuaian stok otomatis maupun manual.">
    <x-slot:actions>
      <div class="fm-toolbar__group" role="group" aria-label="Filter pergerakan stok">
        <button type="button" wire:click="setFilter('all')" class="fm-pill" aria-pressed="{{ $filter === 'all' ? 'true' : 'false' }}">Semua</button>
        <button type="button" wire:click="setFilter('in')" class="fm-pill" aria-pressed="{{ $filter === 'in' ? 'true' : 'false' }}">Masuk</button>
        <button type="button" wire:click="setFilter('out')" class="fm-pill" aria-pressed="{{ $filter === 'out' ? 'true' : 'false' }}">Keluar</button>
        <x-ui.button variant="outline" wire:click="exportPdf"><span class="material-symbols-outlined" aria-hidden="true">download</span>Ekspor PDF</x-ui.button>
      </div>
    </x-slot:actions>
  </x-ui.section-header>
  <div class="fm-timeline">
    @forelse($logs as $log)
      <article class="fm-timeline__item" data-type="{{ $log->type === 'IN' ? 'in' : 'out' }}" wire:key="stock-log-{{ $log->id }}">
        <span class="fm-timeline__dot"><span class="material-symbols-outlined" aria-hidden="true">{{ $log->type === 'IN' ? 'south_west' : 'north_east' }}</span></span>
        <div class="fm-timeline__body">
          <div><h3 class="fm-timeline__name">{{ $log->product->name ?? 'Produk sudah dihapus' }}</h3><p class="fm-timeline__meta">{{ $log->reason }} · Referensi #LOG-{{ $log->id }}</p><p class="fm-timeline__meta">{{ $log->created_at->format('d M Y, H:i') }}</p></div>
          <div><p class="fm-timeline__number">{{ $log->type === 'IN' ? '+' : '−' }}{{ number_format($log->quantity, 0, ',', '.') }} unit</p><div style="margin-top:5px"><x-ui.badge :tone="$log->type === 'IN' ? 'success' : 'danger'">{{ $log->type === 'IN' ? 'Masuk' : 'Keluar' }}</x-ui.badge></div></div>
        </div>
      </article>
    @empty
      <x-ui.card padding="lg"><x-ui.empty-state icon="history" title="Belum ada pergerakan stok" description="Riwayat stok akan tercatat setelah ada aktivitas inventaris atau penjualan." /></x-ui.card>
    @endforelse
  </div>
  <div class="fm-surface-panel">{{ $logs->links('vendor.pagination.custom', data: ['scrollTo' => false]) }}</div>
</div>