@extends('layout.app-clear')

<!-- push css halaman detail berita -->
@push('styles')
<link href="{{ asset('css/list-page/info.css') }}?v={{ filemtime(public_path('css/list-page/info.css')) }}" rel="stylesheet">
@endpush

@section('content')
<div class="infographic-list-wrapper">

    <section class="info-list-hero py-5">
        <div class="container text-center">
            <div class="hero-content mx-auto" style="max-width: 700px;" data-aos="fade-up" data-aos-duration="800">
                <span class="info-badge mb-3">Informasi Hukum</span>
                <h1 class="hero-title fw-bold mb-3">Galeri Infografis</h1>
                <p class="hero-subtitle text-muted">
                    Temukan rangkuman informasi, prosedur, dan edukasi seputar hukum dari JDIH BNPB dalam bentuk visual yang interaktif dan mudah dipahami.
                </p>
            </div>
        </div>
    </section>

    <section class="info-list-content pb-5">
        <div class="container">
            <div class="row g-4">

                @forelse ($infografis as $item)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <a href="{{ route('infografis.show', $item->slug) }}" class="info-card-link text-decoration-none">
                        <article class="info-card shadow-sm">

                            <div class="info-card-img-wrap">
                                @if($item->gambar && file_exists(public_path('storage/' . $item->gambar)))
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="Thumbnail Infografis" class="info-card-img" loading="lazy">
                                @else
                                <img src="{{ asset('img/noimage.png') }}" alt="Default Image" class="info-card-img default-img" loading="lazy">
                                @endif

                                <div class="info-card-overlay">
                                    <span class="btn-view">
                                        <i class="fas fa-expand me-2"></i> Lihat Detail
                                    </span>
                                </div>
                            </div>

                            <div class="info-card-body">
                                <div class="info-card-meta mb-2">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d F Y') }}
                                </div>
                                <h3 class="info-card-title">
                                    {{ \Illuminate\Support\Str::limit($item->judul ?? 'Detail Infografis Hukum JDIH BNPB', 60) }}
                                </h3>
                            </div>

                        </article>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="empty-state-box p-5 rounded-4 border bg-white shadow-sm mx-auto" style="max-width: 500px;">
                        <i class="fas fa-folder-open fa-4x text-muted opacity-50 mb-3"></i>
                        <h4 class="fw-bold text-dark">Belum Ada Infografis</h4>
                        <p class="text-muted mb-0">Infografis saat ini belum tersedia atau sedang dalam proses pembaruan data.</p>
                    </div>
                </div>
                @endforelse

            </div>

            @if($infografis->hasPages())
            <div class="info-pagination d-flex justify-content-center mt-5 pt-4 border-top">
                {{ $infografis->links('pagination::bootstrap-5') }}
            </div>
            @endif

        </div>
    </section>

</div>
@endsection