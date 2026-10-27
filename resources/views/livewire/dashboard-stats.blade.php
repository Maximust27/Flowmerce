<div class="fm-page">
  @if($lowStockCount > 0)
    <x-ui.card variant="notice" padding="sm" class="fm-notice" role="status">
      <div class="fm-notice__content"><span class="fm-notice__icon"><span class="material-symbols-outlined" aria-hidden="true">warning_amber</span></span><div><p class="fm-notice__title">{{ $lowStockCount }} produk mendekati batas stok</p><p class="fm-notice__description">Periksa persediaan melalui halaman Inventaris.</p></div></div>
      <x-ui.button variant="secondary" href="{{ route('inventaris.index') }}">Periksa Stok</x-ui.button>
    </x-ui.card>
  @endif
  <section class="fm-kpi-grid" aria-label="Statistik ringkas">
    <x-ui.stat-card label="Pendapatan" :value="'Rp '.number_format($revenue, 0, ',', '.')" icon="payments" tone="success" hint="Transaksi pemasukan" />
    <x-ui.stat-card label="Keuntungan Bersih" :value="'Rp '.number_format($profit, 0, ',', '.')" icon="monitoring" hint="Pemasukan dikurangi pengeluaran" />
    <x-ui.stat-card label="Stok Menipis" :value="$lowStockCount.' produk'" icon="inventory_2" tone="warning" hint="Produk memerlukan perhatian" />
  </section>
</div>