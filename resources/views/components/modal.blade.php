@props(['name', 'show' => false, 'maxWidth' => '2xl'])
@php($widthClass = ['sm' => 'max-w-sm','md' => 'max-w-md','lg' => 'max-w-lg','xl' => 'max-w-xl','2xl' => 'max-w-2xl'][$maxWidth] ?? 'max-w-2xl')
<div x-data="{show: @js($show)}"
  x-on:open-modal.window="$event.detail === '{{ $name }}' && (show = true)"
  x-on:close-modal.window="$event.detail === '{{ $name }}' && (show = false)"
  x-on:close.stop="show = false"
  @keydown.escape.window="show = false"
  x-show="show" x-cloak
  x-init="$watch('show', value => { if (value) $nextTick(() => $el.querySelector('input,button')?.focus()) })"
  class="fm-modal">
  <div class="fm-modal__scrim" @click="show = false" aria-hidden="true"></div>
  <div class="fm-modal__panel {{ $widthClass }}" role="dialog" aria-modal="true" aria-label="{{ $name }}">{{ $slot }}</div>
</div>