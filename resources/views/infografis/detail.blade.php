@extends('layout.app-clear')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <!-- Header -->
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white p-4">
                    <div class="me-3 flex-shrink-0">
                        <i class="far fa-calendar-alt me-1"></i>
                        {{ \Carbon\Carbon::parse($infografis->created_at)->format('d F Y') }}
                    </div>
                    <h5 class="fw-bold text-uppercase text-center mb-0 mx-3" style="flex: 1; overflow: hidden; text-overflow: ellipsis;">
                        {{ $infografis->judul }}
                    </h5>
                    <div class="ms-3 flex-shrink-0">
                        <i class="fas fa-eye me-1"></i> {{ $infografis->view_count }} Dilihat
                    </div>
                </div>

                <!-- Carousel -->
                <div class="card-body p-5">
                    @php
                    // Periksa jika gambar berupa array atau JSON, dan decode jika perlu
                    $images = is_array($infografis->gambar) ? $infografis->gambar : json_decode($infografis->gambar, true);
                    @endphp

                    @if (count($images) > 0)
                    <div id="carouselInfografis" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($images as $key => $image)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <img
                                    src="{{ asset('storage/' . $image) }}"
                                    class="d-block w-100"
                                    alt="Gambar Infografis {{ $key + 1 }}">
                            </div>
                            @endforeach
                        </div>
                        <!-- Tombol navigasi carousel -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInfografis" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselInfografis" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    @else
                    <p>Tidak ada gambar untuk ditampilkan.</p>
                    @endif
                </div>

                <!-- Footer Navigation -->
                <div class="d-flex justify-content-center mt-1 mb-5">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const carousel = new bootstrap.Carousel('#infografisCarousel', {
        interval: 5000, // Waktu pergantian otomatis (ms)
        ride: 'carousel'
    });
</script>

@endsection