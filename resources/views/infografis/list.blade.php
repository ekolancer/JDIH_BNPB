@extends('layout.app-clear')

@section('content')

<div class="container mt-2" style="padding: 50px;">
    <!-- Title "List Infografis" in the center -->
    <h2 class="text-center w-100" style="padding-bottom: 50px;">List Infografis</h2>
    <!-- Kolom Daftar Infografis -->
    <div class="container">
        <!-- Kolom List Infografis -->
        <div class="col-md-12">
            <div class="row">
                @forelse ($infografis as $item)
                <div class="col-md-3 mb-4"> <!-- 3 berarti 4 kolom dalam 1 baris -->
                    <!-- Card Dokumen -->
                    <div class="card shadow-lg border-0 zoom-card h-100">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex flex-column align-items-center">
                                <!-- Kolom Ikon File -->
                                <div class="mb-3">
                                    @if($item->gambar) <!-- Pastikan gambar tidak null -->
                                    <img
                                        src="{{ asset('storage/' . $item->gambar) }}"
                                        alt="Gambar Lampiran"
                                        class="rounded img-fluid"
                                        style="height: 200px; object-fit: cover;" />
                                    @else
                                    <img
                                        src="{{ asset('img/noimage.png') }}"
                                        alt="Ikon Default"
                                        class="rounded img-fluid"
                                        style="height: 200px; object-fit: cover;" />
                                    @endif
                                </div>

                                <!-- Kolom Judul -->
                                <h5 class="card-title text-primary fw-bold text-center mb-2">
                                    {{ $item->judul }}
                                </h5>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="card-footer bg-light border-top">
                            <div class="d-flex justify-content-between">
                                <!-- Informasi Tanggal -->
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}
                                </small>
                                <!-- Tombol Aksi -->
                                <a href="{{ route('infografis.show', $item->slug) }}" class="btn btn-primary btn-sm">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Tidak ada infografis ditemukan.
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $infografis->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection