@props(['label', 'field', 'rows' => 3])
<div class="fm-field">
  <label for="{{ $field }}" class="fm-field__label">{{ $label }}</label>
  <textarea id="{{ $field }}" name="{{ $field }}" rows="{{ $rows }}" {{ $attributes->class(['fm-field__input', 'fm-field__input--invalid' => $errors->has($field)]) }}>{{ $slot }}</textarea>
  @error($field)<p class="fm-field__error" role="alert">{{ $message }}</p>@enderror
</div>