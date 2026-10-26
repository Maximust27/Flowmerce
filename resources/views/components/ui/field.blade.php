@props(['label', 'field', 'type' => 'text', 'hint' => null])
<div class="fm-field">
  <label for="{{ $field }}" class="fm-field__label">{{ $label }}</label>
  <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}" {{ $attributes->class(['fm-field__input', 'fm-field__input--invalid' => $errors->has($field)]) }} />
  @if($hint)<p class="fm-field__hint">{{ $hint }}</p>@endif
  @error($field)<p class="fm-field__error" role="alert">{{ $message }}</p>@enderror
</div>