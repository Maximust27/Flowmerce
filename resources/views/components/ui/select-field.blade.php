@props(['label', 'field'])
<div class="fm-field">
  <label for="{{ $field }}" class="fm-field__label">{{ $label }}</label>
  <select id="{{ $field }}" name="{{ $field }}" {{ $attributes->class(['fm-field__input', 'fm-field__input--invalid' => $errors->has($field)]) }}>{{ $slot }}</select>
  @error($field)<p class="fm-field__error" role="alert">{{ $message }}</p>@enderror
</div>