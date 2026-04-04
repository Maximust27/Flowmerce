@props([
    'icon' => null,
    'title' => 'Belum ada data',
    'description' => '',
    'actionUrl' => null,
    'actionLabel' => 'Tambah Sekarang',
])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <div class="empty-state-icon">
        @if($icon)
            {{ $icon }}
        @else
            <svg class="w-9 h-9 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        @endif
    </div>

    <h3 class="text-lg font-semibold text-[var(--color-text-primary)] mb-2">{{ $title }}</h3>

    @if($description)
        <p class="text-sm text-[var(--color-text-muted)] max-w-sm mb-6">{{ $description }}</p>
    @endif

    @if($actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn-primary" wire:navigate>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ $actionLabel }}
        </a>
    @endif
</div>
