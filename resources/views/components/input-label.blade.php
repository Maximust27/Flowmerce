@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]']) }}>
    {{ $value ?? $slot }}
</label>
