@if ($paginator->hasPages())
    <nav class="theme-pagination">
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if (! $paginator->onFirstPage())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
                </li>
            @endif
        </ul>
    </nav>
@endif

<style>
.theme-pagination .pagination {
    margin: 2rem 0;
}

.theme-pagination .page-link {
    color: #ec8951;
    background-color: #fff;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border-radius: 0.25rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.theme-pagination .page-link:hover {
    color: #fff;
    background-color: #ec8951;
    border-color: #ec8951;
}

/* Keep a consistent box for icon-only controls, including empty disabled placeholders */
.theme-pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    min-height: 36px;
    padding: 0;
}

.theme-pagination .page-item.active .page-link {
    color: #fff;
    background-color: #ec8951;
    border-color: #ec8951;
}

.theme-pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
    cursor: not-allowed;
}

.theme-pagination .page-item.disabled .page-link:hover {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}
</style>