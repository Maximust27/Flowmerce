@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-slate-600 bg-white/5 border border-white/5 rounded-xl cursor-default">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-slate-300 bg-white/10 border border-white/10 rounded-xl hover:text-white hover:bg-white/20 transition-all active:scale-95">
                    {!! __('pagination.previous') !!}
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-slate-300 bg-white/10 border border-white/10 rounded-xl hover:text-white hover:bg-white/20 transition-all active:scale-95">
                    {!! __('pagination.next') !!}
                </button>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-slate-600 bg-white/5 border border-white/5 rounded-xl cursor-default">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-slate-400 font-medium font-jb">
                    Showing
                    <span class="font-bold text-slate-200">{{ $paginator->firstItem() }}</span>
                    to
                    <span class="font-bold text-slate-200">{{ $paginator->lastItem() }}</span>
                    of
                    <span class="font-bold text-slate-200">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-xl overflow-hidden glass-card p-1 gap-1">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center p-2 text-slate-600 cursor-default" aria-hidden="true">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </span>
                        </span>
                    @else
                        <button wire:click="previousPage" rel="prev" class="relative inline-flex items-center p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all active:scale-90" aria-label="{{ __('pagination.previous') }}">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 cursor-default uppercase tracking-widest font-jb">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-on-primary bg-primary rounded-lg shadow-lg shadow-primary/20 transition-all font-jb">{{ $page }}</span>
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-slate-400 hover:text-white hover:bg-white/10 rounded-lg transition-all active:scale-95 font-jb" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage" rel="next" class="relative inline-flex items-center p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all active:scale-90" aria-label="{{ __('pagination.next') }}">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center p-2 text-slate-600 cursor-default" aria-hidden="true">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
