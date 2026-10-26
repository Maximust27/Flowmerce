<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Flowmerce — Aplikasi manajemen UMKM untuk inventaris, gudang, kasir, keuangan, dan asisten AI.">
  <title>Flowmerce — Workspace modern untuk UMKM</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <script>(function(){try{var v=localStorage.getItem('flowmerce-theme');document.documentElement.dataset.fmTheme=(v==='dark'||v==='light')?v:(matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light')}catch(e){document.documentElement.dataset.fmTheme='light'}})();</script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="fm-welcome">
  <header class="fm-welcome__header"><nav class="fm-welcome__nav" aria-label="Navigasi utama">
    <a href="{{ url('/') }}" class="fm-welcome__brand"><span class="fm-welcome__brand-icon"><span class="material-symbols-outlined" aria-hidden="true">hub</span></span>Flowmerce</a>
    <div class="fm-welcome__links"><a href="#fitur">Fitur</a><a href="#keunggulan">Keunggulan</a><a href="#mulai">Mulai</a></div>
    <div class="fm-toolbar__group">
      <div x-data="flowmerceShell()"><x-ui.theme-toggle /></div>
      @auth
        <a class="fm-button" href="{{ auth()->user()->role === 'cashier' ? route('pos.index') : route('dashboard') }}">Masuk Workspace</a>
      @else
        <a class="fm-button" data-variant="secondary" href="{{ route('login') }}">Masuk</a>
        @if(Route::has('register'))<a class="fm-button" href="{{ route('register') }}">Daftar Gratis</a>@endif
      @endauth
    </div>
  </nav></header>
  <main>
    <section class="fm-welcome__hero">
      <div><p class="fm-welcome__eyebrow">All-in-One Business Management</p><h1 class="fm-welcome__headline">Bisnis makin rapi.<br>Kerja makin <em>ringan.</em></h1><p class="fm-welcome__copy">Dari transaksi kasir sampai laporan keuangan, Flowmerce membantu Anda mengelola usaha dalam satu workspace modern yang mudah digunakan.</p>
        <div class="fm-welcome__actions">
          @auth<a class="fm-button" href="{{ auth()->user()->role === 'cashier' ? route('pos.index') : route('dashboard') }}">Buka Dashboard <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
          @else<a class="fm-button" href="{{ route('register') }}">Mulai Sekarang <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>@endauth
          <a class="fm-button" data-variant="secondary" href="#fitur">Jelajahi Fitur</a>
        </div>
        <p class="fm-note" style="margin-top:21px">Dibuat untuk aktivitas bisnis UMKM Indonesia.</p>
      </div>
      <div class="fm-welcome__preview" aria-label="Ilustrasi tampilan dashboard Flowmerce">
        <div class="fm-welcome__preview-header"><span class="fm-welcome__preview-dot"></span><span class="fm-welcome__preview-dot"></span><span class="fm-welcome__preview-dot"></span><span class="fm-muted" style="font-size:10px;margin-left:8px;font-weight:800">Flowmerce / Ringkasan</span></div>
        <div class="fm-welcome__preview-grid">
          <div class="fm-welcome__preview-tile" data-accent="true"><p>Dashboard</p><strong>Workspace</strong><p style="margin-top:8px">Ringkasan usaha</p></div>
          <div class="fm-welcome__preview-tile"><p>Aktivitas</p><strong>Inventaris</strong><p style="margin-top:8px">Kelola produk & stok</p></div>
          <div class="fm-welcome__preview-tile"><p>Keuangan</p><strong>Laporan</strong><p style="margin-top:8px">Pantau arus kas</p></div>
          <div class="fm-welcome__preview-tile"><p>Operasional</p><strong>Kasir POS</strong><p style="margin-top:8px">Transaksi lebih cepat</p></div>
        </div>
        <div class="fm-card" style="padding:20px;margin-top:12px"><p class="fm-card__eyebrow">Visualisasi aktivitas</p><div style="display:flex;align-items:flex-end;gap:10px;height:76px;margin-top:18px">@foreach([24,48,34,65,45,75,58,70] as $height)<span style="display:block;flex:1;height:{{ $height }}%;background:{{ $loop->odd ? 'var(--fm-accent)' : 'var(--fm-accent-soft)' }};border-radius:6px 6px 0 0"></span>@endforeach</div></div>
      </div>
    </section>
    <section class="fm-welcome__section" id="fitur">
      <p class="fm-welcome__eyebrow">Dibangun untuk UMKM</p><h2 class="fm-dashboard__hero-title">Semua kebutuhan bisnis,<br>dalam satu alur kerja.</h2>
      <div class="fm-welcome__feature-grid">
        <article class="fm-welcome__feature"><span class="fm-welcome__feature-icon"><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span></span><h3>Inventaris & Gudang</h3><p>Catat produk, lihat status stok, dan telusuri histori barang masuk-keluar dengan lebih mudah.</p></article>
        <article class="fm-welcome__feature"><span class="fm-welcome__feature-icon"><span class="material-symbols-outlined" aria-hidden="true">point_of_sale</span></span><h3>Kasir & POS</h3><p>Alur pemilihan produk, keranjang, dan checkout praktis untuk kebutuhan transaksi harian.</p></article>
        <article class="fm-welcome__feature"><span class="fm-welcome__feature-icon"><span class="material-symbols-outlined" aria-hidden="true">account_balance_wallet</span></span><h3>Keuangan & Analisis</h3><p>Pantau pemasukan, pengeluaran, dan ringkasan keuntungan berdasarkan catatan transaksi.</p></article>
        <article class="fm-welcome__feature"><span class="fm-welcome__feature-icon"><span class="material-symbols-outlined" aria-hidden="true">groups</span></span><h3>Manajemen Pegawai</h3><p>Atur akun staf serta hak akses administrator dan kasir sesuai kebutuhan toko.</p></article>
        <article class="fm-welcome__feature"><span class="fm-welcome__feature-icon"><span class="material-symbols-outlined" aria-hidden="true">auto_awesome</span></span><h3>Asisten AI Bisnis</h3><p>Tanyakan kondisi stok, laporan bisnis, atau ide promosi dalam percakapan yang sederhana.</p></article>
        <article class="fm-welcome__feature"><span class="fm-welcome__feature-icon"><span class="material-symbols-outlined" aria-hidden="true">contrast</span></span><h3>Fleksibel & Nyaman</h3><p>Antarmuka responsif dengan tema terang atau gelap yang dapat disesuaikan.</p></article>
      </div>
    </section>
    <section class="fm-welcome__section" id="keunggulan" style="padding-top:0"><x-ui.card padding="lg"><div class="fm-dashboard__hero" style="align-items:center"><div><p class="fm-welcome__eyebrow">Cara kerja yang lebih fokus</p><h2 class="fm-dashboard__hero-title">Lebih sedikit tab.<br>Lebih banyak kendali.</h2><p class="fm-welcome__copy">Operasional sehari-hari tidak perlu terasa rumit. Akses informasi penting, buat transaksi, dan pantau performa bisnis dalam pengalaman yang konsisten.</p></div><div class="fm-list"><div class="fm-list__row">Dashboard terintegrasi<span class="material-symbols-outlined" aria-hidden="true">check_circle</span></div><div class="fm-list__row">Navigasi yang terstruktur<span class="material-symbols-outlined" aria-hidden="true">check_circle</span></div><div class="fm-list__row">Tampilan modern responsif<span class="material-symbols-outlined" aria-hidden="true">check_circle</span></div></div></div></x-ui.card></section>
    <section class="fm-welcome__section" id="mulai" style="text-align:center;padding-top:15px"><p class="fm-welcome__eyebrow">Mulai sekarang</p><h2 class="fm-dashboard__hero-title">Siap mengelola bisnis lebih nyaman?</h2><p class="fm-welcome__copy" style="margin:18px auto 26px">Buat akun Flowmerce dan mulailah mengatur kebutuhan operasional usaha Anda.</p>
      @auth<a class="fm-button" href="{{ auth()->user()->role === 'cashier' ? route('pos.index') : route('dashboard') }}">Masuk Workspace</a>@else<a class="fm-button" href="{{ route('register') }}">Buat Akun Flowmerce</a>@endauth
    </section>
  </main>
  <footer class="fm-welcome__footer">© {{ date('Y') }} Flowmerce · Sistem manajemen untuk UMKM Indonesia.</footer>
</body>
</html>