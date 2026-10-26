@php($menu = config('flowmerce-navigation.items'))
<div class="fm-rail__inner">
  <div class="fm-rail__brand">
    <x-ui.brand :href="route('dashboard')" :compact="true" wire:navigate />
    <button type="button" class="fm-icon-button fm-rail__close" @click="sidebarOpen=false" aria-label="Tutup menu"><span class="material-symbols-outlined" aria-hidden="true">close</span></button>
  </div>
  <p class="fm-rail__caption">MENU</p>
  <nav class="fm-rail__nav" aria-label="Navigasi workspace">
    @foreach($menu as $item)
      <a href="{{ route($item['route']) }}" wire:navigate @click="sidebarOpen=false"
         class="fm-rail__link"
         @if(request()->routeIs($item['match'])) aria-current="page" @endif
         title="{{ $item['label'] }}">
        <span class="material-symbols-outlined" aria-hidden="true">{{ $item['icon'] }}</span>
        <span class="fm-rail__link-label">{{ $item['label'] }}</span>
      </a>
    @endforeach
  </nav>
  <div class="fm-rail__footer">
    <a href="{{ route('profile') }}" wire:navigate @click="sidebarOpen=false" class="fm-rail__link" title="Pengaturan" @if(request()->routeIs('profile')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">settings</span><span class="fm-rail__link-label">Pengaturan</span></a>
    <form action="{{ route('logout') }}" method="POST">@csrf
      <button class="fm-rail__link" type="submit" title="Keluar" aria-label="Keluar dari akun"><span class="material-symbols-outlined" aria-hidden="true">logout</span><span class="fm-rail__link-label">Keluar</span></button>
    </form>
  </div>
</div>