<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-ghost border border-white/10 uppercase']) }}>
    {{ $slot }}
</button>
