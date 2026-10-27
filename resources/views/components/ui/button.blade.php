@props(['href' => null, 'variant' => 'primary', 'type' => 'button', 'navigate' => true])
@if($href)
<a href="{{ $href }}" data-variant="{{ $variant }}" {{ $attributes->class(['fm-button']) }} @if($navigate) wire:navigate @endif>{{ $slot }}</a>
@else
<button type="{{ $type }}" data-variant="{{ $variant }}" {{ $attributes->class(['fm-button']) }}>{{ $slot }}</button>
@endif