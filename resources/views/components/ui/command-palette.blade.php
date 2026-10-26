@php
    $items = auth()->user()->role === 'cashier'
        ? collect(config('flowmerce-navigation.items'))->where('route', 'pos.index')->values()->all()
        : config('flowmerce-navigation.items');
@endphp
<div class="fm-command" x-show="commandOpen" x-cloak @keydown.escape.window="commandOpen=false">
  <div class="fm-command__scrim" @click="commandOpen=false" aria-hidden="true"></div>
  <section class="fm-command__dialog" role="dialog" aria-modal="true" aria-label="Navigasi cepat">
    <div class="fm-command__search">
      <span class="material-symbols-outlined" aria-hidden="true">search</span>
      <label for="fm-command-input" class="sr-only">Cari halaman</label>
      <input id="fm-command-input" x-ref="commandSearch" x-model="commandQuery" placeholder="Cari halaman di Flowmerce..." type="search" autocomplete="off">
      <kbd>ESC</kbd>
    </div>
    <p class="fm-command__caption">PINDAH KE HALAMAN</p>
    <nav class="fm-command__results" aria-label="Hasil navigasi cepat">
      @foreach($items as $item)
        <a href="{{ route($item['route']) }}" wire:navigate @click="commandOpen=false; commandQuery=''"
           x-show="@js(strtolower($item['label'])) .includes(commandQuery.trim().toLowerCase()) || commandQuery.trim()===''"
           class="fm-command__result">
          <span class="fm-command__result-icon"><span class="material-symbols-outlined" aria-hidden="true">{{ $item['icon'] }}</span></span>
          <span>{{ $item['label'] }}</span>
          <span class="material-symbols-outlined fm-command__result-arrow" aria-hidden="true">arrow_outward</span>
        </a>
      @endforeach
      <a href="{{ route('profile') }}" wire:navigate @click="commandOpen=false; commandQuery=''"
         x-show="'pengaturan profil akun'.includes(commandQuery.trim().toLowerCase()) || commandQuery.trim()===''"
         class="fm-command__result"><span class="fm-command__result-icon"><span class="material-symbols-outlined" aria-hidden="true">settings</span></span>Pengaturan akun<span class="material-symbols-outlined fm-command__result-arrow" aria-hidden="true">arrow_outward</span></a>
    </nav>
    <div class="fm-command__footer"><span><kbd>↵</kbd> Pilih halaman</span><span><kbd>ESC</kbd> Tutup</span></div>
  </section>
</div>