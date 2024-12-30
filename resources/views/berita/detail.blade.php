@extends('layout.app-clear')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Section Judul File dan Breadcrumb -->
<section class="file-title-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/berita') }}">Berita</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit(($berita->judul), 20) }}</li>
            </ol>
        </nav>
    </div>
</section>

<div class="container">
    <div class="row">
        <!-- Detail Container -->
        <div class="col-md-10">
            <div class="container-detail shadow-lg">
                <div class="title-section">
                    <h1 class="article-title">
                        {{ $berita->judul }}
                    </h1>
                    <div class="article-meta d-flex justify-content-between align-items-center">
                        <p class="mb-0">
                            <i class="far fa-calendar-alt me-1" style="font-weight: bold;"></i>
                            {{ \Carbon\Carbon::parse($berita->created_at)->format('d F Y') }}
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
                    <p class="article-content">
                        {!! str($berita->konten)->sanitizeHtml() !!}
                    </p>

                    <!-- Tombol Kembali -->
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-back">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection