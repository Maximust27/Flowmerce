@props(['title', 'close' => 'closeModal', 'width' => 'md'])
<div class="fm-modal" role="presentation" x-data x-init="$nextTick(() => $el.querySelector('input:not([type=hidden]), select, textarea')?.focus())" @keydown.escape.window="$wire.call('{{ $close }}')">
  <div class="fm-modal__scrim" wire:click="{{ $close }}" aria-hidden="true"></div>
  <section class="fm-modal__panel" data-width="{{ $width }}" role="dialog" aria-modal="true" aria-label="{{ $title }}" wire:key="fm-modal-{{ $title }}">
    <header class="fm-modal__head"><div><p class="fm-modal__eyebrow">Flowmerce Workspace</p><h2 class="fm-modal__title">{{ $title }}</h2></div><button type="button" class="fm-icon-button" wire:click="{{ $close }}" aria-label="Tutup"><span class="material-symbols-outlined" aria-hidden="true">close</span></button></header>
    <div class="fm-modal__body">{{ $slot }}</div>
  </section>
</div>