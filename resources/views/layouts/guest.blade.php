<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Flowmerce — Kelola usaha, stok, kasir, dan keuangan dalam satu workspace.">
  <title>{{ config('app.name', 'Flowmerce') }} — {{ $title ?? 'Akun' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <script>(function(){try{var v=localStorage.getItem('flowmerce-theme');document.documentElement.dataset.fmTheme=(v==='dark'||v==='light')?v:(matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light')}catch(e){document.documentElement.dataset.fmTheme='light'}})();</script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="fm-auth">
  <main class="fm-auth__shell">
    <section class="fm-auth__story">
      <x-ui.brand :href="url('/')" class="fm-auth__brand" />
      <div class="fm-auth__message"><p class="fm-section-eyebrow" style="color:inherit">Workspace untuk UMKM</p><h1>Lebih rapi.<br>Lebih cepat.<br>Lebih siap tumbuh.</h1><p>Kelola produk, keuangan, pegawai, dan transaksi kasir dalam satu aplikasi yang nyaman digunakan setiap hari.</p><div class="fm-auth__features"><span class="fm-auth__feature">Inventaris & Gudang</span><span class="fm-auth__feature">POS Terintegrasi</span><span class="fm-auth__feature">Asisten AI</span></div></div>
      <p class="fm-auth__story-footer">© {{ date('Y') }} Flowmerce. Dibuat untuk usaha Indonesia.</p>
    </section>
    <section class="fm-auth__content">
      <a href="{{ url('/') }}" class="fm-auth__back"><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span> Kembali ke beranda</a>
      <x-ui.brand :href="url('/')" class="fm-auth__mobile-logo" />
      <div class="fm-auth__form">{{ $slot }}</div>
      <p class="fm-auth__content-footer">Akses aman untuk manajemen UMKM</p>
    </section>
  </main>
</body>
</html>