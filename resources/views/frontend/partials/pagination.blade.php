@if ($paginator->hasPages())
    <div class="flex items-center justify-center space-x-2 mt-6 w-full" aria-label="Pagination">
        {{-- Nút Trước --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-100 text-xs font-bold text-gray-300 bg-gray-50 cursor-not-allowed select-none">
                <i class="fa-solid fa-chevron-left mr-2 text-3xs"></i> Trước
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-emerald-950 bg-white hover:bg-gray-50 hover:text-emerald-800 hover:border-gray-300 transition-all duration-300 shadow-2xs active:scale-95">
                <i class="fa-solid fa-chevron-left mr-2 text-3xs"></i> Trước
            </a>
        @endif

        {{-- Các số trang --}}
        @foreach ($elements as $element)
            {{-- Dấu ba chấm --}}
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-xs font-semibold text-gray-400 select-none">
                    {{ $element }}
                </span>
            @endif

            {{-- Các link số --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-xs font-extrabold text-white shadow-sm transition-all select-none" style="background-color: {{ $news_settings['title_color'] ?? '#064e3b' }}">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 bg-white hover:bg-gray-50 hover:text-emerald-800 hover:border-gray-300 transition-all duration-300 shadow-2xs active:scale-95">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Nút Sau --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-emerald-950 bg-white hover:bg-gray-50 hover:text-emerald-800 hover:border-gray-300 transition-all duration-300 shadow-2xs active:scale-95">
                Sau <i class="fa-solid fa-chevron-right ml-2 text-3xs"></i>
            </a>
        @else
            <span class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-100 text-xs font-bold text-gray-300 bg-gray-50 cursor-not-allowed select-none">
                Sau <i class="fa-solid fa-chevron-right ml-2 text-3xs"></i>
            </span>
        @endif
    </div>
@endif
