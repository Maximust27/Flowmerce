@php
$menu = [
    ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'space_dashboard'],
    ['route' => 'inventaris.index', 'match' => 'inventaris.*', 'label' => 'Inventaris', 'icon' => 'inventory_2'],
    ['route' => 'gudang.index', 'match' => 'gudang.*', 'label' => 'Gudang Digital', 'icon' => 'warehouse'],
    ['route' => 'keuangan.index', 'match' => 'keuangan.*', 'label' => 'Keuangan', 'icon' => 'account_balance_wallet'],
    ['route' => 'pegawai.index', 'match' => 'pegawai.*', 'label' => 'Pegawai', 'icon' => 'groups'],
    ['route' => 'pos.index', 'match' => 'pos.*', 'label' => 'Kasir / POS', 'icon' => 'point_of_sale'],
    ['route' => 'chat.index', 'match' => 'chat.*', 'label' => 'Asisten AI', 'icon' => 'auto_awesome'],
];
@endphp
<div class="fm-sidebar__content">
  <div class="flex items-center"><a class="fm-brand" href="{{ route('dashboard') }}" wire:navigate><span class="fm-brand__mark"><span class="material-symbols-outlined" aria-hidden="true">hub</span></span><span><span class="fm-brand__name">flowmerce</span><span class="fm-brand__subtitle">{{ auth()->user()->business_name ?: 'Bisnis Anda' }}</span></span></a><button type="button" class="fm-icon-button fm-sidebar__mobile-close" aria-label="Tutup navigasi" @click="sidebarOpen=false"><span class="material-symbols-outlined" aria-hidden="true">close</span></button></div>
  <p class="fm-menu-label">Menu utama</p><nav class="fm-menu" aria-label="Navigasi utama">
    @foreach($menu as $item)
      <a href="{{ route($item['route']) }}" wire:navigate @click="sidebarOpen=false" class="fm-menu-link" @if(request()->routeIs($item['match'])) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">{{ $item['icon'] }}</span>{{ $item['label'] }}</a>
    @endforeach
  </nav>
  <div class="fm-sidebar__bottom"><a href="{{ route('profile') }}" wire:navigate class="fm-menu-link" @if(request()->routeIs('profile')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">settings</span>Pengaturan</a>
  <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="fm-menu-link"><span class="material-symbols-outlined" aria-hidden="true">logout</span>Keluar</button></form></div>
</div>