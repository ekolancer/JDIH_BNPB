@extends('layout.app-clear')

<!-- push css halaman list berita -->
@push('styles')
<link href="{{ asset('css/list-page/berita.css') }}?v={{ filemtime(public_path('css/list-page/berita.css')) }}" rel="stylesheet">
@endpush

@section('content')
<div class="news-list-wrapper">

    <section class="news-list-hero py-5 border-bottom">
        <div class="container text-center">
            <div class="hero-content mx-auto" style="max-width: 800px;" data-aos="fade-up" data-aos-duration="800">
                <span class="news-badge mb-3">Informasi Hukum</span>
                <h1 class="hero-title fw-bold mb-3">Berita Seputar JDIH BNPB</h1>
                <p class="hero-subtitle text-muted">
                    Ikuti perkembangan, kegiatan, dan informasi terbaru seputar jaringan dokumentasi dan informasi hukum di lingkungan Badan Nasional Penanggulangan Bencana.
                </p>
            </div>
        </div>
    </section>

    <section class="news-list-content py-5">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-8">
                    <h4 class="section-title mb-4">Berita Terbaru</h4>

                    <div class="news-list-container">
                        @forelse ($berita as $item)
                        <article class="news-item">
                            <a href="{{ route('berita.show', $item->slug) }}" class="news-item-link">

                                <div class="row g-4 align-items-center">
                                    <div class="col-sm-5 col-md-4">
                                        <figure class="news-item-thumb mb-0">
                                            @if($item->gambar && file_exists(public_path('storage/' . $item->gambar)))
                                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="img-fluid" loading="lazy">
                                            @else
                                            <img src="{{ asset('img/noimage.png') }}" alt="Default Image" class="img-fluid default-img" loading="lazy">
                                            @endif
                                        </figure>
                                    </div>

                                    <div class="col-sm-7 col-md-8">
                                        <div class="news-item-content">

                                            <div class="news-item-meta mb-2">
                                                <span class="meta-date">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d F Y') }}
                                                </span>
                                                <span class="meta-sep">•</span>
                                                <span class="meta-views">
                                                    <i class="far fa-eye"></i> {{ number_format($item->view_count) }}
                                                </span>
                                            </div>

                                            <h2 class="news-item-title mb-2">
                                                {{ $item->judul }}
                                            </h2>

                                            <p class="news-item-excerpt text-muted mb-3">
                                                {{ Str::limit(strip_tags($item->konten), 130, '...') }}
                                            </p>

                                            <span class="news-item-readmore">Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i></span>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        </article>
                        @empty
                        <div class="empty-state p-5 text-center border rounded-4 bg-light">
                            <i class="fas fa-newspaper fa-4x text-muted opacity-50 mb-3"></i>
                            <h5 class="fw-bold">Belum Ada Berita</h5>
                            <p class="text-muted mb-0">Saat ini belum ada artikel berita yang dipublikasikan.</p>
                        </div>
                        @endforelse
                    </div>

                    @if($berita->hasPages())
                    <div class="news-pagination mt-5 pt-4 border-top">
                        {{ $berita->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                </div>

                <aside class="col-lg-4">
                    <div class="news-sidebar sticky-sidebar">

                        <div class="news-widget p-4 rounded-4 shadow-sm border bg-white">
                            <h4 class="widget-title mb-4">Terpopuler</h4>
                            <div class="popular-list">
                                @forelse($beritaTerpopuler as $item)
                                <a href="{{ route('berita.show', $item->slug) }}" class="popular-item">
                                    <div class="popular-thumb-wrap">
                                        <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('img/noimage.png') }}" alt="Thumb" class="popular-thumb" loading="lazy">
                                    </div>
                                    <div class="popular-content">
                                        <h5 class="popular-title">{{ Str::limit($item->judul, 60) }}</h5>
                                        <div class="popular-date">
                                            {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d M Y') }}
                                        </div>
                                    </div>
                                </a>
                                @empty
                                <p class="text-muted text-center small mb-0">Belum ada data terpopuler.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </aside>

            </div>
        </div>
</div>
</section>

</div>
@endsection