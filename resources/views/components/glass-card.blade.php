@props([
    'padding' => 'p-6',
])

<div {{ $attributes->merge(['class' => "glass rounded-2xl $padding"]) }}>
    {{ $slot }}
</div>
