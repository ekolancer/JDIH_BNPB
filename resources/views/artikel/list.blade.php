@extends('layout.app-clear')

@section('content')

<div class="container mt-2" style="padding: 50px;">
    <!-- Title "Daftar Artikel" in the center -->
    <h2 class="text-center w-100" style="padding-bottom: 50px">Dokumen Hukum / Artikel</h2>

    <div class="row">
        <!-- Kolom Filter -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Filter</h5>
                    <form method="GET" action="{{ route('artikel.index') }}">
                        <!-- Judul -->
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" id="judul" name="judul" class="form-control" placeholder="Masukkan judul">
                        </div>

                        <div class="mb-3">
                            <label for="tipeDok" class="form-label">Tipe Dokumen</label>
                            <select id="tipeDok" name="tipeDok" class="form-select">
                                <option value="">Pilih Tipe Dokumen</option>
                                @foreach($tipeDokOptions as $option)
                                <option value="{{ $option }}" {{ request('tipeDok') == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nomor -->
                        <div class="mb-3">
                            <label for="nomorPanggil" class="form-label">Nomor Panggil</label>
                            <input type="text" id="nomorPanggil" name="nomorPanggil" class="form-control" placeholder="Masukkan nomor panggil">
                        </div>

                        <!-- Tahun -->
                        <div class="mb-3">
                            <label for="tahunTerbit" class="form-label">Tahun Terbit</label>
                            <input type="number" id="tahunTerbit" name="tahunTerbit" class="form-control" placeholder="Masukkan tahun terbit">
                        </div>

                        <!-- Penerbit -->
                        <div class="mb-3">
                            <label for="penerbit" class="form-label">Penerbit</label>
                            <input type="text" id="penerbit" name="penerbit" class="form-control" placeholder="Masukkan nama penerbit">
                        </div>

                        <!-- Tombol Filter -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Cari</button>
                            <a href="{{ route('artikel.index') }}" class="btn btn-warning">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Daftar Dokumen -->
        <div class="col-md-8">
            <div class="row">
                @forelse ($dokumen as $artikel)
                <div class="col-12 mb-3">
                    <!-- Card Dokumen -->
                    <div class="card shadow-lg border-0 zoom-card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Kolom Ikon File -->
                                <div class="align-items-center pb-3 mt-0 me-2">
                                    <img src="/img/icondoc.png" alt="" height="50px" width="50px"><!-- Icon file -->
                                </div>
                                <!-- Kolom Tipe Dokumen dan Judul -->
                                <div class="d-flex flex-column w-100">
                                    <h5 class="card-title text-primary fw-bold mb-0">
                                        {{ $artikel->tipeDok }}
                                    </h5>
                                    <p class="card-text mb-3 text-muted">
                                        {{ $artikel->judul ?? 'Tidak ada deskripsi.' }}
                                    </p>
                                </div>
                                <!-- Status -->
                                <div class="badge {{ $artikel->status === 'BERLAKU' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $artikel->status }}
                                </div>
                            </div>

                            <!-- Informasi tambahan -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        Dibuat pada: {{ \Carbon\Carbon::parse($artikel->created_at)->format('d F Y') }}
                                    </small>
                                </div>

                                <!-- Tombol aksi -->
                                <div class="d-flex">
                                    <a href="{{ route('artikel.show', $artikel->judul) }}" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    <a href="#" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-download me-1"></i>Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Tidak ada data artikel ditemukan.
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $dokumen->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection