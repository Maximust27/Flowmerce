@props(['label', 'value', 'icon' => 'analytics', 'tone' => 'accent', 'hint' => null])
<x-ui.card padding="sm" {{ $attributes->class(['fm-stat-card']) }} data-tone="{{ $tone }}">
    <div class="fm-stat-card__top"><span class="fm-stat-card__icon"><span class="material-symbols-outlined" aria-hidden="true">{{ $icon }}</span></span></div>
    <div><p class="fm-stat-card__label">{{ $label }}</p><p class="fm-stat-card__value">{{ $value }}</p>@if($hint)<p class="fm-stat-card__hint">{{ $hint }}</p>@endif</div>
</x-ui.card>