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
.theme-pagination {
    margin: 2rem 0;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.theme-pagination .pagination {
    margin: 0;
    flex-wrap: wrap;
    gap: 4px;
}

.theme-pagination .page-item {
    margin: 2px;
}

.theme-pagination .page-link {
    color: #ec8951;
    background-color: #fff;
    border: 1px solid #dee2e6;
    padding: 0;
    border-radius: 0.25rem;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    min-height: 38px;
    font-size: 14px;
    font-weight: 500;
}

.theme-pagination .page-link:hover {
    color: #fff;
    background-color: #ec8951;
    border-color: #ec8951;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(236, 137, 81, 0.3);
}

.theme-pagination .page-item.active .page-link {
    color: #fff;
    background-color: #ec8951;
    border-color: #ec8951;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(236, 137, 81, 0.4);
}

.theme-pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
    cursor: not-allowed;
    opacity: 0.6;
}

.theme-pagination .page-item.disabled .page-link:hover {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
    transform: none;
    box-shadow: none;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .theme-pagination .page-link {
        min-width: 34px;
        min-height: 34px;
        font-size: 13px;
    }
    
    .theme-pagination .pagination {
        justify-content: center;
    }
}
</style>