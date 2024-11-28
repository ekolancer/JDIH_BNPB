@extends('layout.app-clear')

@section('content')

<div class="container mt-2" style="padding: 50px;">
    <!-- Title "Daftar Putusan" in the center -->
    <h2 class="text-center w-100" style="padding-bottom: 50px">Dokumen Hukum / Putusan</h2>

    <div class="row">
        <!-- Kolom Filter -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Filter</h5>
                    <form method="GET" action="{{ route('putusan.index') }}">
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

                        <!-- Pengadilan / Penerbit -->
                        <div class="mb-3">
                            <label for="penerbit" class="form-label">Pengadilan</label>
                            <select id="penerbit" name="penerbit" class="form-select">
                                <option value="">Pilih Pengadilan</option>
                                @foreach($penerbitOptions as $option)
                                <option value="{{ $option }}" {{ request('penerbit') == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bidang Hukum -->
                        <div class="mb-3">
                            <label for="bidangHukum" class="form-label">Bidang Hukum</label>
                            <select id="bidangHukum" name="bidangHukum" class="form-select">
                                <option value="">Pilih Bidang Hukum</option>
                                @foreach($penerbitOptions as $option)
                                <option value="{{ $option }}" {{ request('bidangHukum') == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tombol Filter -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Cari</button>
                            <a href="{{ route('putusan.index') }}" class="btn btn-warning">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Daftar Dokumen -->
        <div class="col-md-8">
            <div class="row">
                @forelse ($dokumen as $putusan)
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
                                        Putusan {{ $putusan->penerbit }} Nomor {{ $putusan->nomor }}
                                    </h5>
                                    <p class="card-text mb-3 text-muted">
                                        {{ $putusan->judul ?? 'Tidak ada deskripsi.' }}
                                    </p>
                                </div>
                                <!-- Status -->
                                <div class="badge {{ $putusan->status === 'BERLAKU' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $putusan->status }}
                                </div>
                            </div>

                            <!-- Informasi tambahan -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        Dibuat pada: {{ \Carbon\Carbon::parse($putusan->created_at)->format('d F Y') }}
                                    </small>
                                </div>

                                <!-- Tombol aksi -->
                                <div class="d-flex">
                                    <a href="{{ route('putusan.show', $putusan->judul) }}" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    <a href=""{{ asset('storage/' . $putusan->lampiran) }}" class="btn btn-secondary btn-sm">
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
                        Tidak ada data Putusan ditemukan.
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