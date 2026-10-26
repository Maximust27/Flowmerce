@php($menu = config('flowmerce-navigation.items'))
<div class="fm-app fm-shell" x-data="flowmerceShell()" @keydown.escape.window="sidebarOpen = false">
  <aside class="fm-sidebar fm-sidebar--desktop"><x-ui.sidebar-menu /></aside>
  <div class="fm-overlay" x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" aria-hidden="true"></div>
  <aside class="fm-sidebar fm-sidebar--mobile" x-cloak x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0"><x-ui.sidebar-menu /></aside>
  <div class="fm-main">
    <header class="fm-topbar">
      <div class="fm-topbar__heading">
        <button type="button" class="fm-icon-button lg:hidden" aria-label="Buka navigasi" :aria-expanded="sidebarOpen.toString()" @click="sidebarOpen = true"><span class="material-symbols-outlined" aria-hidden="true">menu</span></button>
        <div class="fm-topbar__brand"><span class="fm-topbar__brand-dot" aria-hidden="true"></span><span>Flowmerce <span class="fm-topbar__brand-suffix">Workspace</span></span></div>
      </div>
      <nav class="fm-topnav" aria-label="Menu horizontal">
        @foreach($menu as $item)
          <a href="{{ route($item['route']) }}" wire:navigate class="fm-topnav__link" @if(request()->routeIs($item['match'])) aria-current="page" @endif>{{ $item['label'] }}</a>
        @endforeach
      </nav>
      <div class="fm-topbar__actions">
        <x-ui.theme-toggle />
        <a href="{{ route('profile') }}" wire:navigate class="fm-profile" aria-label="Profil {{ auth()->user()->name }}">
          <span class="fm-profile__meta"><span class="fm-profile__name block">{{ auth()->user()->name }}</span><span class="fm-profile__role block">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Kasir' }}</span></span>
          <span class="fm-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
        </a>
      </div>
    </header>
    <main class="fm-content" id="main-content">{{ $slot }}</main>
  </div>
  <nav class="fm-mobile-nav" aria-label="Navigasi mobile">
    @foreach(array_slice($menu, 0, 2) as $item)
      <a class="fm-mobile-nav__link" href="{{ route($item['route']) }}" wire:navigate @if(request()->routeIs($item['match'])) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">{{ $item['icon'] }}</span>{{ $item['short'] }}</a>
    @endforeach
    <a class="fm-mobile-nav__link" href="{{ route('pos.index') }}"><span class="material-symbols-outlined" aria-hidden="true">point_of_sale</span>Kasir</a>
    <a class="fm-mobile-nav__link" href="{{ route('keuangan.index') }}" wire:navigate @if(request()->routeIs('keuangan.*')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">account_balance_wallet</span>Uang</a>
    <button type="button" class="fm-mobile-nav__link" @click="sidebarOpen=true"><span class="material-symbols-outlined" aria-hidden="true">menu</span>Menu</button>
  </nav>
</div>