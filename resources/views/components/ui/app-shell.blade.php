<div class="fm-app fm-shell" x-data="flowmerceShell()">
    <aside class="fm-sidebar fm-sidebar--desktop"><x-ui.sidebar-menu /></aside>
    <div class="fm-overlay" x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen=false" aria-hidden="true"></div>
    <aside class="fm-sidebar fm-sidebar--mobile" x-cloak x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" @keydown.escape.window="sidebarOpen=false"><x-ui.sidebar-menu /></aside>
    <main class="fm-main" id="main-content"><header class="fm-topbar">
        <div class="fm-topbar__heading"><button type="button" class="fm-icon-button lg:hidden" aria-label="Buka navigasi" :aria-expanded="sidebarOpen.toString()" @click="sidebarOpen=true"><span class="material-symbols-outlined" aria-hidden="true">menu</span></button><div><p class="fm-topbar__eyebrow">Workspace / Flowmerce</p><p class="fm-topbar__title">Ringkasan bisnis</p></div></div>
        <div class="fm-topbar__actions"><x-ui.theme-toggle /><a class="fm-profile" href="{{ route('profile') }}" wire:navigate><span class="fm-profile__meta"><span class="fm-profile__name block">{{ auth()->user()->name }}</span><span class="fm-profile__role block">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Kasir' }}</span></span><span class="fm-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span></a></div>
      </header>
      <div class="fm-content">{{ $slot }}</div>
    </main>
    <nav class="fm-mobile-nav" aria-label="Navigasi mobile">
        <a class="fm-mobile-nav__link" href="{{ route('dashboard') }}" wire:navigate @if(request()->routeIs('dashboard')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">home</span>Beranda</a>
        <a class="fm-mobile-nav__link" href="{{ route('inventaris.index') }}" wire:navigate @if(request()->routeIs('inventaris.*')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span>Produk</a>
        <a class="fm-mobile-nav__link" href="{{ route('pos.index') }}" wire:navigate @if(request()->routeIs('pos.*')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">point_of_sale</span>Kasir</a>
        <a class="fm-mobile-nav__link" href="{{ route('keuangan.index') }}" wire:navigate @if(request()->routeIs('keuangan.*')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">wallet</span>Keuangan</a>
        <button type="button" class="fm-mobile-nav__link" @click="sidebarOpen=true" aria-label="Menu lainnya"><span class="material-symbols-outlined" aria-hidden="true">menu</span>Menu</button>
    </nav>
</div>