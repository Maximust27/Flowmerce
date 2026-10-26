@props([
    'label',
    'field',
    'type' => 'text',
    'hint' => null,
    'icon' => null,
    'reveal' => false,
    'required' => false,
])
<div class="fm-field" @if($type === 'password' && $reveal) x-data="{ passwordVisible: false }" @endif>
    <label for="{{ $field }}" class="fm-field__label">{{ $label }}@if($required)<span class="fm-field__required" aria-hidden="true"> *</span>@endif</label>
    <div class="fm-field__control">
        @if($icon)
            <span class="fm-field__leading material-symbols-outlined" aria-hidden="true">{{ $icon }}</span>
        @endif
        <input
            id="{{ $field }}"
            name="{{ $field }}"
            type="{{ $type }}"
            @if($type === 'password' && $reveal) x-bind:type="passwordVisible ? 'text' : 'password'" @endif
            @if($required) required @endif
            @if($errors->has($field)) aria-invalid="true" aria-describedby="{{ $field }}-error" @elseif($hint) aria-describedby="{{ $field }}-hint" @endif
            {{ $attributes->class([
                'fm-field__input',
                'fm-field__input--with-icon' => filled($icon),
                'fm-field__input--with-action' => $type === 'password' && $reveal,
                'fm-field__input--invalid' => $errors->has($field),
            ]) }}
        >
        @if($type === 'password' && $reveal)
            <button class="fm-field__toggle" type="button"
                x-on:click="passwordVisible = !passwordVisible"
                x-bind:aria-label="passwordVisible ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                x-bind:aria-pressed="passwordVisible.toString()">
                <span class="material-symbols-outlined" aria-hidden="true" x-text="passwordVisible ? 'visibility_off' : 'visibility'">visibility</span>
            </button>
        @endif
    </div>
    @if($hint)<p id="{{ $field }}-hint" class="fm-field__hint">{{ $hint }}</p>@endif
    @error($field)<p id="{{ $field }}-error" class="fm-field__error" role="alert">{{ $message }}</p>@enderror
</div>