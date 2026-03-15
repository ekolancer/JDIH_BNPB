<style>
    
</style>

<section class="breadcrumb-section">
    <div class="container">
        <!-- <h1 class="title-bread display-5 fw-bold">{{ $pageTitle }}</h1> -->

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">
                        <i class="bi bi-house-door-fill"></i> Beranda
                    </a>
                </li>

                @foreach($breadcrumbs as $breadcrumb)
                @if (!$loop->last)
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
                </li>
                @else
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $breadcrumb['name'] }}
                </li>
                @endif
                @endforeach
            </ol>
        </nav>
    </div>
</section>