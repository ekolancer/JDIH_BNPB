@extends('layout.app-detail')

<!-- push css halaman detail berita -->
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="{{ asset('css/detail-page/berita.css') }}?v={{ filemtime(public_path('css/detail-page/berita.css')) }}" rel="stylesheet">
@endpush

@section('content')

<div class="news-detail-wrapper">

    <div class="news-topbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ url()->previous() }}" class="news-btn-back">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
            <span class="news-breadcrumb-text d-none d-md-block">Berita & Informasi Hukum</span>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-lg-5 g-4 align-items-start">

            <main class="col-lg-8">
                <article class="news-article-container shadow-sm">

                    <header class="news-header">
                        <div class="news-category-badge mb-3">Berita Terkini</div>
                        <h1 class="news-title">
                            {{ $berita->judul }}
                        </h1>
                        <div class="news-meta">
                            <div class="meta-item">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ $berita->penulis_label }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="far fa-calendar-alt"></i>
                                <span>{{ $berita->tanggal_formatted }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-eye"></i>
                                <span>{{ $berita->view_count_formatted }} Dilihat</span>
                            </div>
                        </div>
                    </header>

                    <figure class="news-featured-image">
                        <img src="{{ $berita->image_url }}">
                    </figure>

                    <div class="news-body-content">
                        {!! $berita->konten_sanitized !!}
                    </div>

                    <div class="news-share-section mt-5 border-top pt-4">
                        <span class="share-label">Bagikan Berita:</span>
                        <div class="d-flex gap-2">
                            <a href="#" class="share-btn share-fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="share-btn share-x" title="X"><i class="fab fa-x-twitter"></i></a>
                            <a href="#" class="share-btn share-wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" class="share-btn share-tg" title="Telegram"><i class="fab fa-telegram-plane"></i></a>
                        </div>
                    </div>

                </article>
            </main>

            <aside class="col-lg-4">
                <div class="news-sidebar sticky-sidebar">

                    <div class="news-widget shadow-sm">
                        <h4 class="widget-title">Berita Terpopuler</h4>
                        <div class="popular-news-list">
                            @foreach($beritaTerpopuler as $item)
                            <a href="{{ route('berita.show', $item->slug) }}" class="popular-news-item">
                                <img src="{{ $item->image_url }}" alt="Thumb"
                                    class="popular-thumb">
                                <div class="popular-content">
                                    <h5 class="popular-title">{{ $item->judul_short }}</h5>
                                    <div class="popular-date">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $item->tanggal_short }}
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Widget untuk Bantuan Hukum -->
                    <!-- <div class="news-widget banner-widget text-white shadow-sm mt-4">
                        <div class="banner-icon mb-3">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h4 class="fw-bold mb-2">Butuh Bantuan Hukum?</h4>
                        <p class="mb-4 opacity-75" style="font-size: 0.9rem;">Akses layanan informasi dan dokumentasi hukum resmi dengan mudah melalui portal JDIH kami.</p>
                        <a href="#" class="btn btn-light fw-bold w-100 banner-btn">Ajukan Sekarang</a>
                    </div> -->

                </div>
            </aside>

        </div>
    </div>
</div>
@endsection