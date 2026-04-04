@props([
    'level' => 'aman',
    'stock' => 0,
])

@php
    $config = match($level) {
        'aman' => ['class' => 'badge-stock-aman', 'label' => 'Aman'],
        'menipis' => ['class' => 'badge-stock-menipis', 'label' => 'Menipis'],
        'habis' => ['class' => 'badge-stock-habis', 'label' => 'Habis'],
        default => ['class' => 'badge-stock-aman', 'label' => 'Aman'],
    };
@endphp

<span {{ $attributes->merge(['class' => $config['class']]) }}>
    {{ $config['label'] }} ({{ $stock }})
</span>
