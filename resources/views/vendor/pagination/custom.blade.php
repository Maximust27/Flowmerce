@if($paginator->hasPages())
<nav class="fm-toolbar" role="navigation" aria-label="Navigasi halaman">
  <p class="fm-toolbar__label">Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data</p>
  <div class="fm-toolbar__group">
    @if($paginator->onFirstPage())<span class="fm-pill" aria-disabled="true">Sebelumnya</span>
    @else<button type="button" class="fm-pill" wire:click="previousPage" wire:loading.attr="disabled" rel="prev">Sebelumnya</button>@endif

    @foreach($elements as $element)
      @if(is_string($element))<span class="fm-muted" aria-hidden="true">{{ $element }}</span>
      @elseif(is_array($element))
        @foreach($element as $page => $url)
          @if($page == $paginator->currentPage())<span class="fm-pill" aria-current="page" style="color:var(--fm-accent-on);background:var(--fm-accent);border-color:var(--fm-accent)">{{ $page }}</span>
          @else<button type="button" class="fm-pill" wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled" aria-label="Halaman {{ $page }}">{{ $page }}</button>@endif
        @endforeach
      @endif
    @endforeach
    @if($paginator->hasMorePages())<button type="button" class="fm-pill" wire:click="nextPage" wire:loading.attr="disabled" rel="next">Selanjutnya</button>
    @else<span class="fm-pill" aria-disabled="true">Selanjutnya</span>@endif
  </div>
</nav>
@endif