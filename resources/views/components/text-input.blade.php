@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:-translate-y-0.5 transition-all duration-300 placeholder:text-slate-500 shadow-sm hover:bg-surface-container-highest/80']) }}>
