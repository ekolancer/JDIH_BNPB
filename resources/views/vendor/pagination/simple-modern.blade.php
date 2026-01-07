@php
    $queryString = http_build_query(\Illuminate\Support\Arr::except(request()->query(), 'page'));
    $queryString = $queryString ? '&' . $queryString : '';
@endphp

@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&larr;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() . $queryString }}" rel="prev">&larr;</a>
                </li>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($elements as $element)
                {{-- Tampilkan teks jika bukan array --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Tampilkan link halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url . $queryString }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Selanjutnya --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() . $queryString }}" rel="next">&rarr;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&rarr;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
