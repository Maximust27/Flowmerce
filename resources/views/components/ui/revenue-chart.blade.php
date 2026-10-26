@props(['labels' => [], 'values' => [], 'period' => 'weekly'])
@php
  $series = array_map(fn($value) => max(0, (float) $value), array_values($values));
  $peak = max(1, ...($series ?: [0]));
  $count = count($series);
  $left = 18; $right = 742; $top = 24; $bottom = 208;
  $points = [];
  foreach ($series as $index => $total) {
    $x = $count > 1 ? $left + ($index / ($count - 1)) * ($right - $left) : ($left + $right) / 2;
    $y = $bottom - ($total / $peak) * ($bottom - $top);
    $points[] = [$x, $y];
  }
  $curve = '';
  if (count($points)) {
    $curve = sprintf('M %.2f %.2f', $points[0][0], $points[0][1]);
    for ($i = 1; $i < count($points); $i++) {
      $before = $points[$i - 1]; $after = $points[$i];
      $mid = ($before[0] + $after[0]) / 2;
      $curve .= sprintf(' C %.2f %.2f, %.2f %.2f, %.2f %.2f', $mid, $before[1], $mid, $after[1], $after[0], $after[1]);
    }
    $area = $curve . sprintf(' L %.2f %d L %.2f %d Z', end($points)[0], $bottom, $points[0][0], $bottom);
  }
  $totalIncome = array_sum($series);
  $hasData = $totalIncome > 0;
@endphp
<figure {{ $attributes->class(['fm-lineplot']) }}>
  <div class="fm-lineplot__summary">
    <div><p class="fm-overview__micro-label">Total periode</p><p class="fm-lineplot__total">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p></div>
    <span class="fm-lineplot__legend"><span></span>Pemasukan</span>
  </div>
  @if($hasData && $count > 1)
  <svg class="fm-lineplot__svg" viewBox="0 0 760 240" role="img" aria-label="Grafik pemasukan untuk {{ $period === 'monthly' ? 'enam bulan' : 'tujuh hari' }} terakhir">
    <defs><linearGradient id="fm-income-gradient" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="var(--fm-accent)" stop-opacity=".19"/><stop offset="100%" stop-color="var(--fm-accent)" stop-opacity="0"/></linearGradient></defs>
    @foreach([54,104,154,208] as $gridY)<line x1="{{ $left }}" x2="{{ $right }}" y1="{{ $gridY }}" y2="{{ $gridY }}" class="fm-lineplot__grid" />@endforeach
    <path d="{{ $area }}" fill="url(#fm-income-gradient)" />
    <path d="{{ $curve }}" pathLength="100" class="fm-lineplot__curve" />
    @foreach($points as $i => $point)
      <circle class="fm-lineplot__point" cx="{{ $point[0] }}" cy="{{ $point[1] }}" r="5"><title>{{ $labels[$i] ?? '' }}: Rp {{ number_format($series[$i], 0, ',', '.') }}</title></circle>
    @endforeach
  </svg>
  @else
    <div class="fm-lineplot__empty" role="status"><span class="material-symbols-outlined" aria-hidden="true">monitoring</span><strong>Belum ada pemasukan</strong><span>Grafik akan terisi saat transaksi pemasukan dicatat.</span></div>
  @endif
  <figcaption class="fm-lineplot__axis">
    @foreach($labels as $label)<span>{{ $label }}</span>@endforeach
  </figcaption>
</figure>