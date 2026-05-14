@if ($paginator->hasPages())
    <nav class="puff-pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="puff-pagination__summary">
            @if ($paginator->firstItem())
                Showing
                <strong>{{ number_format($paginator->firstItem()) }}</strong>
                to
                <strong>{{ number_format($paginator->lastItem()) }}</strong>
                of
                <strong>{{ number_format($paginator->total()) }}</strong>
                results
            @else
                No results
            @endif
        </div>

        <div class="puff-pagination__controls">
            @if ($paginator->onFirstPage())
                <span class="puff-pagination__button is-disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    Previous
                </span>
            @else
                <a class="puff-pagination__button" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">
                    Previous
                </a>
            @endif

            <div class="puff-pagination__pages">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="puff-pagination__ellipsis" aria-disabled="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="puff-pagination__page is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="puff-pagination__page" href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a class="puff-pagination__button" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">
                    Next
                </a>
            @else
                <span class="puff-pagination__button is-disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    Next
                </span>
            @endif
        </div>
    </nav>
@endif
