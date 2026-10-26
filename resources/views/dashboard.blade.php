@php
  $firstName = explode(' ', trim(auth()->user()->name))[0];
  $greeting = now()->hour < 11 ? 'Selamat pagi' : (now()->hour < 15 ? 'Selamat siang' : (now()->hour < 18 ? 'Selamat sore' : 'Selamat malam'));
@endphp
<div class="fm-dashboard fm-dashboard--studio">
  <x-ui.page-header eyebrow="OVERVIEW / WORKSPACE" :title="$greeting.', '.$firstName.'!" description="Ringkasan performa usaha, dalam satu tampilan yang lebih sederhana.">
    <x-slot:actions>
      <x-ui.button variant="secondary" :href="route('chat.index')"><span class="material-symbols-outlined" aria-hidden="true">auto_awesome</span>Tanya Asisten AI</x-ui.button>
      <x-ui.button :href="route('pos.index')"><span class="material-symbols-outlined" aria-hidden="true">add</span>Transaksi Baru</x-ui.button>
    </x-slot:actions>
  </x-ui.page-header>

  <section class="fm-overview-grid" aria-label="Ringkasan bisnis">
    <x-ui.card class="fm-overview__balance" padding="none">
      <div class="fm-overview__balance-top"><span class="fm-overview__eyebrow"><span class="fm-overview__eyebrow-dot"></span>RINGKASAN KEUANGAN</span><span class="fm-overview__balance-icon"><span class="material-symbols-outlined" aria-hidden="true">account_balance_wallet</span></span></div>
      <div class="fm-overview__balance-main">
        <p class="fm-overview__micro-label">Total pemasukan bisnis</p>
        <p class="fm-overview__balance-number">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
        <p class="fm-overview__balance-subtext">Akumulasi seluruh transaksi pemasukan yang tercatat.</p>
      </div>
      <div class="fm-overview__balance-bottom">
        <div><span>Keuntungan bersih</span><strong>Rp {{ number_format($profit,0,',','.') }}</strong></div>
        <div><span>Pendapatan hari ini</span><strong>Rp {{ number_format($todayIncome,0,',','.') }}</strong></div>
      </div>
      <div class="fm-overview__balance-actions">
        <a href="{{ route('keuangan.index') }}" wire:navigate class="fm-overview__balance-link">Lihat keuangan <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
      </div>
    </x-ui.card>

    <div class="fm-overview__metrics">
      <x-ui.stat-card label="Pendapatan hari ini" :value="'Rp '.number_format($todayIncome, 0, ',', '.')" icon="payments" tone="accent" variant="accent" hint="Pemasukan hari berjalan" />
      <x-ui.stat-card label="Total Produk" :value="number_format($totalProducts,0,',','.')" icon="inventory_2" hint="Terdaftar di inventaris" />
      <x-ui.stat-card label="Keuntungan Bersih" :value="'Rp '.number_format($profit,0,',','.')" icon="savings" tone="success" hint="Total pemasukan − pengeluaran" />
      <x-ui.stat-card label="Stok Menipis" :value="$lowStockCount.' produk'" icon="error_outline" tone="warning" hint="Perlu pengecekan stok" />
    </div>

    <x-ui.card class="fm-overview__chart" padding="md">
      <div class="fm-studio-panel-heading">
        <div><p class="fm-overview__micro-label">ANALYTICS</p><h2 class="fm-studio-panel-title">Tren pemasukan</h2></div>
        <div class="fm-segmented" role="group" aria-label="Periode tren">
          <button type="button" wire:click="setChartFilter('weekly')" aria-pressed="{{ $chartFilter === 'weekly' ? 'true' : 'false' }}" wire:loading.attr="disabled" wire:target="setChartFilter">7 hari</button>
          <button type="button" wire:click="setChartFilter('monthly')" aria-pressed="{{ $chartFilter === 'monthly' ? 'true' : 'false' }}" wire:loading.attr="disabled" wire:target="setChartFilter">6 bulan</button>
        </div>
      </div>
      <div wire:loading.class="fm-overview__chart--loading" wire:target="setChartFilter">
        <x-ui.revenue-chart :labels="$chartLabels" :values="$chartValues" :period="$chartFilter" />
      </div>
    </x-ui.card>
  </section>

  <section class="fm-insights-grid" aria-label="Aktivitas dan prioritas">
    <x-ui.card class="fm-insights__stock" padding="md">
      <div class="fm-studio-panel-heading"><div><p class="fm-overview__micro-label">INVENTORY HEALTH</p><h2 class="fm-studio-panel-title">Perlu perhatian</h2></div><x-ui.badge :tone="$lowStockCount > 0 ? 'warning' : 'success'">{{ $lowStockCount > 0 ? $lowStockCount.' produk' : 'Aman' }}</x-ui.badge></div>
      @forelse($stockAlerts as $product)
        <div class="fm-stock-alert" wire:key="stock-alert-{{ $product->id }}">
          <span class="fm-stock-alert__icon"><span class="material-symbols-outlined" aria-hidden="true">{{ $product->current_stock <= 0 ? 'production_quantity_limits' : 'inventory_2' }}</span></span>
          <div class="fm-stock-alert__body"><p>{{ $product->name }}</p><span>Minimum {{ $product->min_stock_alert }} unit</span></div>
          <x-ui.badge :tone="$product->current_stock <= 0 ? 'danger' : 'warning'">{{ $product->current_stock <= 0 ? 'Habis' : $product->current_stock.' unit' }}</x-ui.badge>
        </div>
      @empty
        <div class="fm-insights__healthy"><span class="material-symbols-outlined" aria-hidden="true">verified</span><p>Persediaan terkendali</p><span>Belum ada produk yang melewati batas minimum stok.</span></div>
      @endforelse
      <div class="fm-insights__footer"><a class="fm-text-link" href="{{ route('inventaris.index') }}" wire:navigate>Lihat inventaris <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a></div>
    </x-ui.card>

    <x-ui.card class="fm-insights__activity" padding="md">
      <div class="fm-studio-panel-heading"><div><p class="fm-overview__micro-label">RECENT ACTIVITY</p><h2 class="fm-studio-panel-title">Transaksi terbaru</h2></div><a class="fm-text-link" href="{{ route('keuangan.index') }}" wire:navigate>Semua <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a></div>
      <div class="fm-activity-list">
        @forelse($recentTransactions as $tx)
          <div class="fm-activity-row" wire:key="fm-activity-{{ $tx->id }}">
            <span class="fm-activity-row__icon" data-type="{{ $tx->type === 'INCOME' ? 'income' : 'expense' }}"><span class="material-symbols-outlined" aria-hidden="true">{{ $tx->type === 'INCOME' ? 'south_west' : 'north_east' }}</span></span>
            <div class="fm-activity-row__body"><p>{{ $tx->category ?: 'Transaksi' }}</p><span>{{ $tx->created_at->format('d M Y · H:i') }}</span></div>
            <strong class="fm-activity-row__amount" data-type="{{ $tx->type === 'INCOME' ? 'income' : 'expense' }}">{{ $tx->type === 'INCOME' ? '+' : '−' }}Rp {{ number_format($tx->amount,0,',','.') }}</strong>
          </div>
        @empty
          <x-ui.empty-state icon="receipt_long" title="Belum ada transaksi" description="Catatan keuangan terbaru akan tampil di sini." />
        @endforelse
      </div>
    </x-ui.card>
  </section>

  <section class="fm-studio-shortcuts" aria-label="Pintasan pekerjaan">
    <div><p class="fm-overview__micro-label">QUICK ACTIONS</p><h2 class="fm-studio-panel-title">Apa yang ingin dikerjakan?</h2></div>
    <div class="fm-studio-shortcuts__links">
      <a href="{{ route('inventaris.index') }}" wire:navigate><span class="material-symbols-outlined" aria-hidden="true">add_box</span>Kelola Produk<span class="material-symbols-outlined" aria-hidden="true">arrow_outward</span></a>
      <a href="{{ route('gudang.index') }}" wire:navigate><span class="material-symbols-outlined" aria-hidden="true">warehouse</span>Arus Gudang<span class="material-symbols-outlined" aria-hidden="true">arrow_outward</span></a>
      <a href="{{ route('pegawai.index') }}" wire:navigate><span class="material-symbols-outlined" aria-hidden="true">group</span>Kelola Tim<span class="material-symbols-outlined" aria-hidden="true">arrow_outward</span></a>
    </div>
  </section>
</div>