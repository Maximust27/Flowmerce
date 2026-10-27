@props(['href' => null, 'compact' => false])
<a href="{{ $href ?? url('/') }}" {{ $attributes->class(['fm-wordmark']) }} aria-label="Flowmerce">
    <span class="fm-mark fm-mark--image" aria-hidden="true">
        <picture>
            <source type="image/webp" srcset="{{ asset('images/branding/flowmerce-icon.webp') }}">
            <img src="{{ asset('images/branding/flowmerce-mark.svg') }}"
                 width="42" height="42" alt="" decoding="async">
        </picture>
    </span>
    @unless($compact)
        <span class="fm-wordmark__label">flowmerce<span class="fm-wordmark__period">.</span></span>
    @endunless
</a>