@props(['padding' => 'md', 'variant' => 'default'])
<section data-padding="{{ $padding }}" data-variant="{{ $variant }}" {{ $attributes->class(['fm-card']) }}>{{ $slot }}</section>