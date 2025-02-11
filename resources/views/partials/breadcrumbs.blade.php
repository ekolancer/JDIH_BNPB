<nav aria-label="breadcrumb">
    <ol class="breadcrumb justify-content-center bg-transparent">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        @foreach($breadcrumbs as $breadcrumb)
            @if (!$loop->last)
                <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a></li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['name'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>