@props([
    'title' => '',
    'value' => '0',
    'trend' => null,
    'trendLabel' => '',
    'icon' => 'chart',
    'color' => 'emerald',
])

@php
    $colorMap = [
        'emerald' => [
            'icon_bg' => 'bg-emerald-500/10',
            'icon_text' => 'text-emerald-400',
            'glow' => 'shadow-emerald-500/5',
        ],
        'sky' => [
            'icon_bg' => 'bg-sky-500/10',
            'icon_text' => 'text-sky-400',
            'glow' => 'shadow-sky-500/5',
        ],
        'violet' => [
            'icon_bg' => 'bg-violet-500/10',
            'icon_text' => 'text-violet-400',
            'glow' => 'shadow-violet-500/5',
        ],
        'amber' => [
            'icon_bg' => 'bg-amber-500/10',
            'icon_text' => 'text-amber-400',
            'glow' => 'shadow-amber-500/5',
        ],
        'red' => [
            'icon_bg' => 'bg-red-500/10',
            'icon_text' => 'text-red-400',
            'glow' => 'shadow-red-500/5',
        ],
    ];
    $colors = $colorMap[$color] ?? $colorMap['emerald'];
@endphp

<div {{ $attributes->merge(['class' => 'stat-card animate-fade-in-up']) }}>
    <div class="flex items-start justify-between mb-4">
        <div class="p-2.5 rounded-xl {{ $colors['icon_bg'] }}">
            @if($icon === 'chart')
                <svg class="w-5 h-5 {{ $colors['icon_text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            @elseif($icon === 'money')
                <svg class="w-5 h-5 {{ $colors['icon_text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @elseif($icon === 'warning')
                <svg class="w-5 h-5 {{ $colors['icon_text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            @elseif($icon === 'box')
                <svg class="w-5 h-5 {{ $colors['icon_text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            @endif
        </div>

        @if($trend !== null)
            <div class="flex items-center gap-1 text-xs font-semibold {{ $trend >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                @if($trend >= 0)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                    </svg>
                @else
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                @endif
                <span>{{ abs($trend) }}%</span>
            </div>
        @endif
    </div>

    <p class="text-sm text-[var(--color-text-muted)] mb-1">{{ $title }}</p>
    <p class="text-2xl font-bold font-mono text-[var(--color-text-primary)]">{{ $value }}</p>

    @if($trendLabel)
        <p class="text-xs text-[var(--color-text-muted)] mt-2">{{ $trendLabel }}</p>
    @endif
</div>
