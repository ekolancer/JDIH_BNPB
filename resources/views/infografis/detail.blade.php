@extends('layout.app-detail')

<!-- push css halaman detail infografis -->
@push('styles')
<link href="{{ asset('css/detail-info.css') }}?v={{ filemtime(public_path('css/detail-info.css')) }}" rel="stylesheet">
@endpush

@section('content')
<div class="infographic-detail-wrapper">
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                <div class="mb-4">
                    <a href="{{ url()->previous() }}" class="btn-back-clean">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>

                <header class="info-header text-center mb-0">
                    <h1 class="info-title">
                        {{ $infografis->judul_label }}
                    </h1>

                    <div class="info-meta">
                        <span class="meta-item">
                            <i class="far fa-calendar-alt"></i>
                            {{ $infografis->tanggal_formatted }}
                        </span>
                        <span class="meta-separator d-none d-sm-inline">•</span>
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            {{ $infografis->view_count_formatted }} Dilihat
                        </span>
                    </div>
                </header>

                <div class="info-share-top d-flex justify-content-center align-items-center gap-3 mb-4">
                    <span class="share-label mb-0">Bagikan:</span>
                    <div class="d-flex gap-2">
                        <a href="#" class="share-icon fb" title="Bagikan ke Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="share-icon x" title="Bagikan ke X"><i class="fab fa-x-twitter"></i></a>
                        <a href="#" class="share-icon wa" title="Bagikan ke WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="share-icon tg" title="Telegram"><i class="fab fa-telegram-plane"></i></a>
                    </div>
                </div>

                <figure class="info-image-container mb-5">
                    @if ($infografis->image_url)
                    <div class="image-wrapper shadow-xl">
                        <img
                            src="{{ $infografis->image_url }}"
                            class="img-fluid info-img"
                            alt="{{ $infografis->image_alt }}"
                            loading="lazy">
                    </div>
                    @else
                    <div class="empty-state shadow-sm">
                        <i class="fas fa-image fa-4x mb-3 text-muted opacity-50"></i>
                        <h5 class="fw-bold text-secondary">Belum Ada Infografis</h5>
                        <p class="text-muted mb-0">Gambar untuk infografis ini belum tersedia.</p>
                    </div>
                    @endif
                </figure>

            </div>
        </div>
    </div>
</div>
@endsection