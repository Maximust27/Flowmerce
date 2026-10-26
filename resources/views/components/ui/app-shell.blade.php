@php
  $menu = auth()->user()->role === 'cashier'
    ? collect(config('flowmerce-navigation.items'))->where('route', 'pos.index')->values()->all()
    : config('flowmerce-navigation.items');
  $activeMenu = collect($menu)->first(fn($item) => request()->routeIs($item['match']));
  $activeTitle = request()->routeIs('profile') ? 'Pengaturan' : ($activeMenu['label'] ?? 'Workspace');
@endphp
<div class="fm-app fm-shell" x-data="flowmerceShell()"
     @keydown.window.ctrl.k.prevent="openCommand()"
     @keydown.window.meta.k.prevent="openCommand()"
     @keydown.escape.window="sidebarOpen=false;commandOpen=false">
  <a href="#main-content" class="fm-skip-link">Lewati ke konten utama</a>
  <aside class="fm-sidebar fm-sidebar--desktop"><x-ui.sidebar-menu /></aside>
  <div class="fm-overlay" x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen=false" aria-hidden="true"></div>
  <aside class="fm-sidebar fm-sidebar--mobile" x-cloak x-show="sidebarOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="translate-x-0"><x-ui.sidebar-menu /></aside>
  <div class="fm-main">
    <header class="fm-topbar">
      <div class="fm-topbar__identity">
        <button type="button" class="fm-icon-button fm-topbar__menu" @click="sidebarOpen=true" aria-label="Buka menu navigasi" :aria-expanded="sidebarOpen.toString()"><span class="material-symbols-outlined" aria-hidden="true">menu</span></button>
        <div class="fm-topbar__crumb"><span class="fm-topbar__crumb-parent">{{ auth()->user()->business_name ?: 'Bisnis Saya' }}</span><span class="fm-topbar__crumb-separator">/</span><span class="fm-topbar__crumb-current">{{ $activeTitle }}</span></div>
      </div>
      <button class="fm-search-trigger" type="button" @click="openCommand()" aria-label="Cari halaman (Ctrl K)">
        <span class="material-symbols-outlined" aria-hidden="true">search</span><span class="fm-search-trigger__placeholder">Cari halaman atau menu...</span><kbd>Ctrl K</kbd>
      </button>
      <div class="fm-topbar__actions">
        <a class="fm-topbar__quick" href="{{ route('pos.index') }}" title="Buka terminal kasir"><span class="material-symbols-outlined" aria-hidden="true">point_of_sale</span><span>Kasir</span></a>
        <x-ui.theme-toggle />
        <span class="fm-topbar__separator" aria-hidden="true"></span>
        <a class="fm-profile" href="{{ route('profile') }}" wire:navigate title="Pengaturan akun" aria-label="Profil {{ auth()->user()->name }}">
          <span class="fm-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name,0,1)) }}</span>
          <span class="fm-profile__meta"><span class="fm-profile__name">{{ auth()->user()->name }}</span><span class="fm-profile__role">{{ auth()->user()->role === 'admin' ? 'Owner / Admin' : 'Kasir' }}</span></span>
          <span class="material-symbols-outlined fm-profile__caret" aria-hidden="true">expand_more</span>
        </a>
      </div>
    </header>
    <nav class="fm-topnav" aria-label="Akses cepat">
      @foreach(array_slice($menu,0,4) as $item)
        <a href="{{ route($item['route']) }}" wire:navigate @if(request()->routeIs($item['match'])) aria-current="page" @endif>{{ $item['label'] }}</a>
      @endforeach
      @if(auth()->user()->role === 'admin')
        <a href="{{ route('chat.index') }}" wire:navigate @if(request()->routeIs('chat.*')) aria-current="page" @endif>Asisten AI <span class="material-symbols-outlined" aria-hidden="true">auto_awesome</span></a>
      @endif
    </nav>
    <main class="fm-content" id="main-content">{{ $slot }}</main>
  </div>
  <nav class="fm-mobile-nav" aria-label="Navigasi cepat mobile">
    @if(auth()->user()->role === 'admin')
    <a class="fm-mobile-nav__link" href="{{ route('dashboard') }}" wire:navigate @if(request()->routeIs('dashboard')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">space_dashboard</span>Beranda</a>
    <a class="fm-mobile-nav__link" href="{{ route('inventaris.index') }}" wire:navigate @if(request()->routeIs('inventaris.*')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span>Produk</a>
    @endif
    <a class="fm-mobile-nav__link fm-mobile-nav__link--primary" href="{{ route('pos.index') }}"><span class="material-symbols-outlined" aria-hidden="true">point_of_sale</span>Kasir</a>
    @if(auth()->user()->role === 'admin')
    <a class="fm-mobile-nav__link" href="{{ route('keuangan.index') }}" wire:navigate @if(request()->routeIs('keuangan.*')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">account_balance_wallet</span>Keuangan</a>
    @endif
    <button type="button" class="fm-mobile-nav__link" @click="sidebarOpen=true"><span class="material-symbols-outlined" aria-hidden="true">menu</span>Menu</button>
  </nav>
  <x-ui.command-palette />
</div>