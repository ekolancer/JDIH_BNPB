@extends('layout.app-clear')

@section('content')

<div class="container mt-2" style="padding: 50px;">
    <!-- Title "Daftar monograf" in the center -->
    <h2 class="text-center w-100" style="padding-bottom: 50px">Dokumen Hukum / Monograf</h2>

    <div class="row">
        <!-- Kolom Filter -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Filter</h5>
                    <form method="GET" action="{{ route('monograf.index') }}">
                        <!-- Judul -->
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" id="judul" name="judul" class="form-control" placeholder="Masukkan judul">
                        </div>

                        <div class="mb-3">
                            <label for="tipeDok" class="form-label">Jenis Monograf</label>
                            <select id="tipeDok" name="tipeDok" class="form-select">
                                <option value="">Pilih Jenis Monograf</option>
                                @foreach($tipeDokOptions as $option)
                                <option value="{{ $option }}" {{ request('tipeDok') == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nomor -->
                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN / ISSN</label>
                            <input type="text" id="isbn" name="isbn" class="form-control" placeholder="Masukkan nomor isbn">
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
                            <a href="{{ route('monograf.index') }}" class="btn btn-warning">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Daftar Dokumen -->
        <div class="col-md-7">
            <div class="row">
                @forelse ($dokumen as $monograf)
                <div class="col-12 mb-3">
                    <!-- Card Dokumen -->
                    <div class="card shadow-lg border-0 zoom-card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex">
                                <!-- Kolom Ikon File -->
                                <div class="me-4">
                                    @if($monograf->lampiran && Storage::exists('public/' . $monograf->lampiran))
                                    <!-- Jika file lampiran ada di server -->
                                    <img
                                        src="{{ asset('storage/' . $monograf->lampiran) }}"
                                        alt="Gambar Lampiran"
                                        class="rounded-shadow"
                                        height="200px"
                                        width="160px" />
                                    @else
                                    <!-- Jika file tidak ada, tampilkan gambar default -->
                                    <img
                                        src="{{ asset('img/noimage.png') }}"
                                        alt="Ikon Default"
                                        class="rounded-shadow"
                                        height="200px"
                                        width="160px" />
                                    @endif
                                </div>

                                <!-- Kolom Tipe Dokumen dan Judul -->
                                <div class="d-flex flex-column justify-content-center">
                                    <h5 class="card-title text-primary fw-bold mb-2">
                                        {{ $monograf->judul }}
                                    </h5>
                                    <p class="card-text text-muted mb-0">
                                        {{ $monograf->deskripsi ?? 'Tidak ada deskripsi.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="card-footer bg-light border-top">
                            <div class="d-flex justify-content-between hero-items-center">
                                <!-- Informasi Tanggal -->
                                <div>
                                    <small class="text-muted me-3">
                                        <i class="far fa-calendar-alt me-1" style="font-weight: bold;"></i>
                                        {{ \Carbon\Carbon::parse($monograf->created_at)->format('d F Y') }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="far fa-eye me-1" style="font-weight: bold;"></i>
                                        {{ $monograf->view_count }}x Tayang
                                    </small>
                                </div>

                                <!-- Tombol aksi -->
                                <div class="d-flex">
                                    <a href="{{ route('monograf.show', $monograf->slug) }}" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    @if($monograf->lampiran && Storage::exists('public/' . $monograf->lampiran))
                                    <a href="{{ asset('storage/' . $monograf->lampiran) }}" class="btn btn-secondary btn-sm" download="{{ $monograf->judul }}">
                                        <i class="fas fa-download me-1"></i>Unduh
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Tidak ada data monograf$monograf ditemukan.
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