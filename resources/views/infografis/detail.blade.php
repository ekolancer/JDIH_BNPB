@extends('layout.app-clear')

@section('content')

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

                <!-- Gambar -->
                <div class="card-body p-5">
                    @if (!empty($infografis->gambar) && file_exists(public_path('storage/' . $infografis->gambar)))
                        <img
                            src="{{ asset('storage/' . $infografis->gambar) }}"
                            class="img-fluid rounded shadow"
                            alt="Gambar Infografis">
                    @else
                        <p class="text-center text-muted">Tidak ada gambar untuk ditampilkan.</p>
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

@endsection