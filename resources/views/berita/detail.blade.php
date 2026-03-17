@extends('layout.app-clear')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Section List Biru -->
<section class="file-title-section">
    <div class="container"></div>
</section>

<!-- Section Detail Berita -->
<section class="berita-detail-wrapper">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-8">
                <div class="berita-detail-container shadow-sm">

                    <header class="berita-detail-header">
                        <h1 class="berita-detail-title">
                            {{ $berita->judul }}
                        </h1>

                        <div class="berita-detail-meta">
                            <span>
                                <i class="far fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($berita->created_at)->locale('id')->translatedFormat('d F Y') }}
                            </span>
                            <span>
                                <i class="fas fa-eye"></i>
                                {{ number_format($berita->view_count) }} Dilihat
                            </span>
                            <span>
                                <i class="fas fa-user"></i>
                                {{ $berita->penulis ?? 'Admin JDIH' }}
                            </span>
                        </div>
                    </header>

                    <div class="berita-detail-image-wrapper">
                        <img
                            alt="{{ $berita->judul }}"
                            class="berita-detail-img img-fluid"
                            src="{{ $berita->gambar ? asset('storage/' . $berita->gambar) : asset('img/noimage.png') }}" />
                    </div>

                    <article class="berita-detail-content">
                        {!! str($berita->konten)->sanitizeHtml() !!}
                    </article>

                    <div class="pt-1 border-top mt-1">
                        <a href="{{ url()->previous() }}" class="btn btn-light btn-berita-back shadow-sm">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card berita-popular-card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="berita-popular-header">Terpopuler</h5>

                        <div class="popular-list">
                            @foreach($beritaTerpopuler as $item)
                            <a href="{{ route('berita.show', $item->slug) }}" class="popular-item-link">
                                <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('img/noimage.png') }}"
                                    alt="Thumb" class="popular-item-img">

                                <div class="popular-item-body">
                                    <h6 class="popular-item-title">
                                        {{ Str::limit($item->judul, 60) }}
                                    </h6>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                    </small>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-primary text-white rounded-4 shadow-sm">
                    <h6 class="fw-bold mb-2">Butuh Bantuan Hukum?</h6>
                    <p class="small mb-0 opacity-75">Akses layanan informasi hukum resmi melalui portal JDIH kami.</p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection