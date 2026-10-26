@props(['label'])
<x-ui.card padding="none" {{ $attributes->class(['fm-table-card']) }}>
  <div class="fm-table-scroll">
    <table class="fm-data-table" aria-label="{{ $label }}"><thead>{{ $head }}</thead><tbody>{{ $slot }}</tbody></table>
  </div>
  @if(isset($footer))<div class="fm-table-footer">{{ $footer }}</div>@endif
</x-ui.card>