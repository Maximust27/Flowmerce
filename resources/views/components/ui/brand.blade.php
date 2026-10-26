@props(['href' => null, 'compact' => false])
<a href="{{ $href ?? url('/') }}" {{ $attributes->class(['fm-wordmark']) }} aria-label="Flowmerce">
  <span class="fm-mark" aria-hidden="true"><span class="fm-mark__upright"></span><span class="fm-mark__top"></span><span class="fm-mark__mid"></span></span>
  @unless($compact)<span class="fm-wordmark__label">flowmerce<span class="fm-wordmark__period">.</span></span>@endunless
</a>