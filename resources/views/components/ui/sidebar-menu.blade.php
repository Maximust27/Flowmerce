@php($menu = config('flowmerce-navigation.items'))
<div class="fm-sidebar__content">
  <a class="fm-brand" href="{{ route('dashboard') }}" wire:navigate aria-label="Flowmerce — Dashboard" title="Flowmerce">
    <span class="fm-brand__mark"><span class="material-symbols-outlined" aria-hidden="true">hub</span></span>
    <span class="fm-brand__wordmark"><span class="fm-brand__name">Flowmerce</span><span class="fm-brand__subtitle">{{ auth()->user()->business_name ?: 'Workspace' }}</span></span>
  </a>
  <button class="fm-icon-button fm-sidebar__mobile-close" type="button" aria-label="Tutup menu" @click="sidebarOpen = false"><span class="material-symbols-outlined" aria-hidden="true">close</span></button>
  <p class="fm-menu-label">Workspace</p>
  <nav class="fm-menu" aria-label="Navigasi utama">
    @foreach($menu as $item)
      <a href="{{ route($item['route']) }}" wire:navigate @click="sidebarOpen = false"
        class="fm-menu-link" title="{{ $item['label'] }}"
        @if(request()->routeIs($item['match'])) aria-current="page" @endif>
        <span class="material-symbols-outlined" aria-hidden="true">{{ $item['icon'] }}</span>
        <span class="fm-menu-link__text">{{ $item['label'] }}</span>
      </a>
    @endforeach
  </nav>
  <div class="fm-sidebar__bottom">
    <a class="fm-menu-link" href="{{ route('profile') }}" wire:navigate title="Pengaturan" @if(request()->routeIs('profile')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">settings</span><span class="fm-menu-link__text">Pengaturan</span></a>
    <form action="{{ route('logout') }}" method="POST">@csrf
      <button type="submit" class="fm-menu-link" title="Keluar"><span class="material-symbols-outlined" aria-hidden="true">logout</span><span class="fm-menu-link__text">Keluar</span></button>
    </form>
  </div>
</div>