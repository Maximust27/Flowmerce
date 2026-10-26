@props(['tone' => 'accent'])
<span data-tone="{{ $tone }}" {{ $attributes->class(['fm-badge']) }}>{{ $slot }}</span>