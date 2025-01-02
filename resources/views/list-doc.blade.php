@extends('layout.app-clear')

@section('content')

<div class="container mt-2" style="padding: 50px;">

    <div class="row align-items-start">
        <!-- Kolom Filter -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Filter</h5>
                    <form method="GET" action="{{ route('peraturan.index') }}">
                        <!-- Judul -->
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" id="judul" name="judul" class="form-control" placeholder="Masukkan judul">
                        </div>

                        <!-- Nomor -->
                        <div class="mb-3">
                            <label for="nomor" class="form-label">Nomor</label>
                            <input type="text" id="nomor" name="nomor" class="form-control" placeholder="Masukkan nomor">
                        </div>

                        <!-- Tahun -->
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <input type="number" id="tahun" name="tahun" class="form-control" placeholder="Masukkan tahun">
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="">Pilih Status</option>
                                <option value="Berlaku">Berlaku</option>
                                <option value="Tidak Berlaku">Tidak Berlaku</option>
                            </select>
                        </div>

                        <!-- Tombol Filter -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Cari</button>
                            <a href="{{ route('peraturan.index') }}" class="btn btn-warning">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Daftar Dokumen -->
        <div class="col-md-8">
            <div class="row">
                @forelse ($paginatedResults as $result)
                <div class="col-12 mb-3">
                    <!-- Card Dokumen -->
                    <div class="card shadow-lg border-0 zoom-card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Kolom Ikon File -->
                                <div class="align-items-center pb-3 mt-0 me-3">
                                    <img src="/img/icon/allDoc.png" alt="" height="50px" width="45px">
                                </div>
                                <!-- Kolom Tipe Dokumen dan Judul -->
                                <div class="d-flex flex-column w-100">
                                    <h5 class="card-title text-primary fw-bold mb-0">
                                        {{ $result->jenisDok }} Nomor {{ $result->nomor }} Tahun {{ $result->tahunTerbit }}
                                    </h5>
                                    <p class="card-text mb-3 text-muted">
                                        {{ $result->judul ?? 'Tidak ada deskripsi.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="card-footer bg-light border-top">
                            <div class="d-flex justify-content-between hero-items-center">
                                <!-- Informasi tambahan -->
                                <div>
                                    <small class="text-muted me-3">
                                        <i class="far fa-calendar-alt me-1" style="font-weight: bold;"></i>
                                        {{ \Carbon\Carbon::parse($result->created_at)->format('d F Y') }}
                                    </small>
                                    <small class="text-muted me-3">
                                        <i class="far fa-eye me-1" style="font-weight: bold;"></i>
                                        {{ $result->view_count }}x Tayang
                                    </small>
                                    <small class="text-muted">
                                        <i class="far fa-download me-1" style="font-weight: bold;"></i>
                                        {{ $result->download_count }}x Unduhan
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Tidak ada data peraturan ditemukan.
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $dokumen->links('pagination::simple-modern') }}
            </div>
        </div>
    </div>
</div>

@endsection