@extends('layout.app-clear')

@section('content')
<!-- Section List Biru -->
<section class="file-title-section">
    <div class="container"></div>
</section>

<div class="container mt-3 mb-5">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card shadow-lg border-0" style="display: inline-block;">
                <!-- Header -->
                <div class="card-header d-flex justify-content-between bg-primary text-white p-2">
                    <div class="me-3 flex-shrink-0">
                        <i class="far fa-calendar-alt me-1"></i>
                        {{ \Carbon\Carbon::parse($infografis->created_at)->locale('id')->translatedFormat('d F Y') }}
                    </div>
                    <!-- <h5 class="fw-bold text-uppercase text-center mb-0 mx-3" style="flex: 1; overflow: hidden; text-overflow: ellipsis;">
                        {{ $infografis->judul }}
                    </h5> -->
                    <div class="ms-3 flex-shrink-0">
                        <i class="fas fa-eye me-1"></i> {{ $infografis->view_count }} Dilihat
                    </div>
                </div>

                <!-- Gambar -->
                <div class="card-body p-3 text-center">
                    @if (!empty($infografis->gambar) && file_exists(public_path('storage/' . $infografis->gambar)))
                        <img
                            src="{{ asset('storage/' . $infografis->gambar) }}"
                            class="img-fluid rounded shadow d-block mx-auto"
                            alt="Gambar Infografis"
                            style="height: auto; width: auto; max-width:100%; max-height:100vh; object-fit: contain;">
                    @else
                        <p class="text-center text-muted">Tidak ada gambar untuk ditampilkan.</p>
                    @endif
                </div>

                <!-- Footer Navigation -->
                <div class="d-flex justify-content-center mt-1 mb-3">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection