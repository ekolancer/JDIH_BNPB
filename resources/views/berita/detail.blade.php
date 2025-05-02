@extends('layout.app-clear')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Section List Biru -->
<section class="file-title-section">
    <div class="container"></div>
</section>

<div class="container">
    <div class="row">
        <!-- Detail Container -->
        <div class="col-md-8">
            <div class="container-detail shadow-lg">
                <div class="title-section">
                    <h1 class="article-title">
                        {{ $berita->judul }}
                    </h1>
                    <div class="article-meta d-flex justify-content-between align-items-center">
                        <p class="mb-0">
                            <i class="far fa-calendar-alt me-1" style="font-weight: bold;"></i>
                            {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y') }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-eye" style="font-weight: bold;"></i> {{ $berita->view_count }} dilihat
                        </p>
                    </div>
                    <div class="article-image" style="position: relative; width: 100%; max-height: auto; overflow: hidden;">
                        <img
                            alt="{{ $berita->judul }}"
                            class="img-fluid"
                            src="{{ asset('storage/' . $berita->gambar) }}"
                            style="object-fit: cover; width: 100%; height: auto;" />
                    </div>
                    <div class="article-content">
                        {!! str($berita->konten)->sanitizeHtml() !!}
                    </div>

                    <!-- Tombol Kembali -->
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-back">Kembali</a>
                </div>
            </div>
        </div>

        <!-- Card Berita Terpopuler -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Terpopuler</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($beritaTerpopuler as $item)
                        <li class="list-group-item popular-item">
                            <!-- Gambar di sebelah kiri -->
                            <img src="{{ asset('storage/'.$item->gambar) }}" alt="Gambar Berita" class="img-fluid">
                            <div class="content">
                                <!-- Judul dengan maksimal 50 karakter dan hyperlink ke halaman detail -->
                                <h6><a href="{{ route('berita.show', $item->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($item->judul, 50) }}</a></h6>
                                <!-- Tanggal dibuat -->
                                <p class="text-muted"><i class="far fa-calendar-alt me-1" style="font-weight: bold;"></i>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection