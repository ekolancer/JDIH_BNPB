@extends('layout.app-clear')

@section('content')

<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-primary">Dokumen Hukum / Peraturan</h5>
        <span class="text-secondary">Terdapat {{ $dokumen->total() }} data</span>
    </div>

    <!-- Daftar Peraturan -->
    <div class="row">
        @forelse ($dokumen as $peraturan)
        <div class="col-12 mb-3">
            <div class="card shadow-lg border-0 zoom-card">
                <div class="card-body">
                    <!-- Judul -->
                    <h5 class="card-title text-primary fw-bold">{{ $peraturan->judul }}</h5>
                    <!-- Deskripsi Singkat -->
                    <p class="card-text mb-3 text-muted">
                        {{ \Str::limit($peraturan->teuBadan ?? 'Tidak ada data', 150) }}
                    </p>
                    <!-- Informasi -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($peraturan->tglPenetapan)->format('d F Y') }}
                            </small>
                        </div>
                        <!-- Jumlah View -->
                        <div class="text-muted">
                            <i class="far fa-eye me-1"></i>{{ rand(50, 1500) }} x Dilihat
                        </div>
                    </div>

                    <!-- Tindakan -->
                    <div class="mt-3 d-flex justify-content-start">
                        <a href="#" class="btn btn-primary btn-sm me-2">FullText</a>
                        <a href="#" class="btn btn-outline-primary btn-sm">Abstrak</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Jika Tidak Ada Data -->
        <div class="col-12">
            <div class="alert alert-warning text-center">
                Tidak ada data peraturan ditemukan.
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $dokumen->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection