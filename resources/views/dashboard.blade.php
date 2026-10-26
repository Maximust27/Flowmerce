<div class="fm-dashboard">
  <x-ui.page-header eyebrow="Business Overview" :title="'Selamat datang, '.explode(' ', auth()->user()->name)[0].' 👋'" description="Berikut gambaran singkat performa bisnis dan aktivitas toko Anda.">
    <x-slot:actions><x-ui.button href="{{ route('pos.index') }}"><span class="material-symbols-outlined" aria-hidden="true">point_of_sale</span>Buka Kasir</x-ui.button></x-slot:actions>
  </x-ui.page-header>

  <div class="fm-dashboard__hero">
    <x-ui.card padding="lg">
      <p class="fm-section-eyebrow">Workspace hari ini</p>
      <h2 class="fm-dashboard__hero-title" style="margin-top:12px">Satu tempat untuk<br>semua aktivitas usaha.</h2>
      <p class="fm-dashboard__hero-copy">Pantau pendapatan, periksa persediaan, kelola tim, dan lihat transaksi dengan alur kerja yang lebih sederhana.</p>
      <div class="fm-dashboard__hero-actions">
        <x-ui.button href="{{ route('inventaris.index') }}"><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span>Kelola Inventaris</x-ui.button>
        <x-ui.button variant="secondary" href="{{ route('keuangan.index') }}">Lihat Keuangan <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></x-ui.button>
      </div>
    </x-ui.card>
    <x-ui.card variant="accent" padding="lg">
      <div class="fm-dashboard__focus"><p class="fm-dashboard__focus-label">Total pendapatan tercatat</p><span class="material-symbols-outlined" aria-hidden="true">trending_up</span></div>
      <p class="fm-dashboard__focus-value">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
      <p style="font-size:11px;opacity:.82;margin-top:11px">Akumulasi transaksi masuk pada akun bisnis ini.</p>
      <div style="height:1px;background:rgb(255 255 255 / 27%);margin:22px 0 15px"></div>
      <p style="font-size:11px;opacity:.92">Keuntungan tercatat <strong class="fm-mono" style="font-size:13px;margin-left:6px">Rp {{ number_format($profit, 0, ',', '.') }}</strong></p>
    </x-ui.card>
  </div>

  @if($lowStockCount > 0)
    <x-ui.card variant="notice" padding="sm" class="fm-notice" role="status">
      <div class="fm-notice__content"><span class="fm-notice__icon"><span class="material-symbols-outlined" aria-hidden="true">warning_amber</span></span><div><p class="fm-notice__title">{{ $lowStockCount }} produk mencapai batas minimum</p><p class="fm-notice__description">Tinjau stok agar aktivitas penjualan tidak terhambat.</p></div></div>
      <x-ui.button variant="secondary" href="{{ route('inventaris.index') }}">Periksa Stok <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></x-ui.button>
    </x-ui.card>
  @endif

  <section class="fm-kpi-grid" aria-label="Ringkasan operasional">
    <x-ui.stat-card label="Pendapatan" :value="'Rp '.number_format($revenue, 0, ',', '.')" icon="payments" tone="success" hint="Total pemasukan bisnis" />
    <x-ui.stat-card label="Keuntungan Bersih" :value="'Rp '.number_format($profit, 0, ',', '.')" icon="monitoring" hint="Pemasukan dikurangi pengeluaran" />
    <x-ui.stat-card label="Stok Perlu Perhatian" :value="number_format($lowStockCount, 0, ',', '.').' produk'" icon="inventory_2" tone="warning" hint="Pada atau di bawah batas stok" />
  </section>

  <div class="fm-dashboard-grid">
    <x-ui.card padding="lg">
      <div class="fm-panel-header"><div><h2 class="fm-panel-title">Grafik Penjualan</h2><p class="fm-panel-description">Pemasukan {{ $chartFilter === 'monthly' ? '6 bulan' : '7 hari' }} terakhir berdasarkan catatan keuangan.</p></div>
        <div class="fm-segmented" role="group" aria-label="Rentang grafik penjualan">
          <button type="button" wire:click="setChartFilter('weekly')" wire:loading.attr="disabled" wire:target="setChartFilter" aria-pressed="{{ $chartFilter === 'weekly' ? 'true' : 'false' }}">7 Hari</button>
          <button type="button" wire:click="setChartFilter('monthly')" wire:loading.attr="disabled" wire:target="setChartFilter" aria-pressed="{{ $chartFilter === 'monthly' ? 'true' : 'false' }}">6 Bulan</button>
        </div>
      </div>
      <div class="fm-chart" role="img" aria-label="Grafik pemasukan {{ $chartFilter === 'monthly' ? '6 bulan terakhir' : '7 hari terakhir' }}">
        <div class="fm-chart__plot" aria-hidden="true">
          @foreach($chartBars as $i => $height)<div class="fm-chart__item" wire:key="chart-{{ $chartFilter }}-{{ $i }}"><div class="fm-chart__bar" style="--fm-bar-height: {{ min(100, max(0, $height / 60 * 100)) }}%"></div></div>@endforeach
        </div>
        <div class="fm-chart__labels" aria-hidden="true">@foreach($chartLabels as $label)<span>{{ $label }}</span>@endforeach</div>
        <p class="fm-chart__summary">Dihitung dari <strong>transaksi pemasukan</strong> yang tercatat.</p>
      </div>
    </x-ui.card>
    <div class="fm-stack">
      <x-ui.card padding="md">
        <x-ui.section-header title="Akses Cepat" description="Buka pekerjaan yang paling sering dilakukan." />
        <nav class="fm-shortcuts" aria-label="Akses cepat" style="margin-top:20px">
          <a href="{{ route('inventaris.index') }}" wire:navigate class="fm-shortcut"><span class="fm-shortcut__label"><span class="material-symbols-outlined" aria-hidden="true">add_box</span>Kelola Produk</span><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
          <a href="{{ route('keuangan.index') }}" wire:navigate class="fm-shortcut"><span class="fm-shortcut__label"><span class="material-symbols-outlined" aria-hidden="true">add_card</span>Catat Keuangan</span><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
          <a href="{{ route('chat.index') }}" wire:navigate class="fm-shortcut"><span class="fm-shortcut__label"><span class="material-symbols-outlined" aria-hidden="true">auto_awesome</span>Asisten AI</span><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
        </nav>
      </x-ui.card>
      <x-ui.card padding="md">
        <x-ui.section-header title="Transaksi Terakhir" description="Lima aktivitas keuangan terbaru." />
        <div class="fm-transaction-list" style="margin-top:15px">
          @forelse($recentTransactions as $tx)
            <div class="fm-transaction" data-type="{{ $tx->type === 'INCOME' ? 'income' : 'expense' }}" wire:key="tx-dashboard-{{ $tx->id }}">
              <div class="fm-transaction__left"><span class="fm-transaction__icon"><span class="material-symbols-outlined" aria-hidden="true">{{ $tx->type === 'INCOME' ? 'south_west' : 'north_east' }}</span></span><div><p class="fm-transaction__category">{{ $tx->category ?: 'Transaksi' }}</p><p class="fm-transaction__date">{{ $tx->created_at->format('d M Y, H:i') }}</p></div></div>
              <span class="fm-transaction__amount">{{ $tx->type === 'INCOME' ? '+' : '−' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}</span>
            </div>
          @empty
            <x-ui.empty-state icon="receipt_long" title="Belum ada transaksi" description="Aktivitas keuangan akan muncul setelah transaksi dibuat." />
          @endforelse
        </div>
        <div class="fm-panel-footer"><a href="{{ route('keuangan.index') }}" wire:navigate class="fm-text-link">Semua transaksi <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a></div>
      </x-ui.card>
    </div>
  </div>
</div>