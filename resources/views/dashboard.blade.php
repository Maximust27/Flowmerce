<div class="fm-dashboard">
    <x-ui.page-header eyebrow="Overview" title="Dashboard Bisnis" description="Pantau kondisi usaha dan aktivitas terkini dalam satu tempat.">
        <x-slot:actions><x-ui.button href="{{ route('pos.index') }}"><span class="material-symbols-outlined" aria-hidden="true">point_of_sale</span>Buka Kasir</x-ui.button></x-slot:actions>
    </x-ui.page-header>

    @if($lowStockCount > 0)
        <x-ui.card variant="notice" padding="sm" class="fm-notice" role="status">
            <div class="fm-notice__content"><span class="fm-notice__icon"><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span></span><div><p class="fm-notice__title">{{ $lowStockCount }} produk perlu perhatian</p><p class="fm-notice__description">Stok mencapai batas minimum. Periksa inventaris agar operasional tetap lancar.</p></div></div>
            <x-ui.button variant="secondary" href="{{ route('inventaris.index') }}">Periksa stok <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></x-ui.button>
        </x-ui.card>
    @endif

    <section aria-label="Ringkasan statistik" class="fm-kpi-grid">
        <x-ui.stat-card label="Total Pendapatan" :value="'Rp '.number_format($revenue, 0, ',', '.')" icon="trending_up" tone="success" hint="Akumulasi transaksi pemasukan" />
        <x-ui.stat-card label="Keuntungan Bersih" :value="'Rp '.number_format($profit, 0, ',', '.')" icon="savings" hint="Pendapatan dikurangi pengeluaran" />
        <x-ui.stat-card label="Stok Menipis" :value="number_format($lowStockCount, 0, ',', '.').' produk'" icon="inventory_2" tone="warning" hint="Produk pada atau di bawah batas stok" />
    </section>

    <div class="fm-dashboard-grid">
      <x-ui.card padding="lg">
        <div class="fm-panel-header"><div><h2 class="fm-panel-title">Performa Penjualan</h2><p class="fm-panel-description">Grafik pemasukan {{ $chartFilter === 'monthly' ? 'enam bulan' : 'tujuh hari' }} terakhir</p></div>
          <div class="fm-segmented" role="group" aria-label="Periode grafik penjualan">
            <button type="button" wire:click="setChartFilter('weekly')" wire:loading.attr="disabled" wire:target="setChartFilter" aria-pressed="{{ $chartFilter === 'weekly' ? 'true' : 'false' }}">7 Hari</button>
            <button type="button" wire:click="setChartFilter('monthly')" wire:loading.attr="disabled" wire:target="setChartFilter" aria-pressed="{{ $chartFilter === 'monthly' ? 'true' : 'false' }}">6 Bulan</button>
          </div>
        </div>
        <div class="fm-chart" role="img" aria-label="Grafik pemasukan {{ $chartFilter === 'monthly' ? '6 bulan' : '7 hari' }} terakhir">
          <div class="fm-chart__plot" aria-hidden="true">
            @foreach($chartBars as $i => $height)
              <div class="fm-chart__item" wire:key="chart-{{ $chartFilter }}-{{ $i }}"><div class="fm-chart__bar" style="--fm-bar-height: {{ min(100, max(0, $height / 60 * 100)) }}%"></div></div>
            @endforeach
          </div>
          <div class="fm-chart__labels" aria-hidden="true">@foreach($chartLabels as $label)<span>{{ $label }}</span>@endforeach</div>
          <p class="fm-chart__summary">Data dari <strong>transaksi pemasukan</strong> usaha Anda.</p>
        </div>
      </x-ui.card>

      <div class="fm-stack">
        <x-ui.card padding="md">
          <div class="fm-panel-header"><div><h2 class="fm-panel-title">Aksi Cepat</h2><p class="fm-panel-description">Aktivitas yang sering digunakan</p></div></div>
          <nav class="fm-shortcuts" aria-label="Aksi cepat">
            <a href="{{ route('inventaris.index') }}" wire:navigate class="fm-shortcut"><span class="fm-shortcut__label"><span class="material-symbols-outlined" aria-hidden="true">add_box</span>Kelola Produk</span><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
            <a href="{{ route('keuangan.index') }}" wire:navigate class="fm-shortcut"><span class="fm-shortcut__label"><span class="material-symbols-outlined" aria-hidden="true">add_card</span>Catat Keuangan</span><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
            <a href="{{ route('gudang.index') }}" wire:navigate class="fm-shortcut"><span class="fm-shortcut__label"><span class="material-symbols-outlined" aria-hidden="true">warehouse</span>Lihat Gudang</span><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
          </nav>
        </x-ui.card>

        <x-ui.card padding="md">
          <div class="fm-panel-header"><div><h2 class="fm-panel-title">Transaksi Terakhir</h2><p class="fm-panel-description">Lima catatan keuangan terbaru</p></div></div>
          <div class="fm-transaction-list">
            @forelse($recentTransactions as $tx)
              <div class="fm-transaction" data-type="{{ $tx->type === 'INCOME' ? 'income' : 'expense' }}" wire:key="recent-tx-{{ $tx->id }}">
                <div class="fm-transaction__left"><span class="fm-transaction__icon"><span class="material-symbols-outlined" aria-hidden="true">{{ $tx->type === 'INCOME' ? 'south_west' : 'north_east' }}</span></span><div><p class="fm-transaction__category">{{ $tx->category ?: 'Transaksi' }}</p><p class="fm-transaction__date">{{ $tx->created_at->format('d M Y, H:i') }}</p></div></div>
                <span class="fm-transaction__amount">{{ $tx->type === 'INCOME' ? '+' : '−' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}</span>
              </div>
            @empty
              <p class="fm-empty">Belum ada transaksi yang tercatat.</p>
            @endforelse
          </div>
          <div class="fm-panel-footer"><a href="{{ route('keuangan.index') }}" wire:navigate class="fm-text-link">Lihat semua transaksi <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a></div>
        </x-ui.card>
      </div>
    </div>
</div>