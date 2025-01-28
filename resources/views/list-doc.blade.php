@extends('layout.app-clear')

@section('content')

<div class="container mt-2" style="padding: 50px;">

    <div class="row align-items-start">
        <!-- Kolom Filter -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('peraturan.index') }}">
                        <!-- Judul -->
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="judul" name="judul" class="form-control border-start-0" placeholder="Tuliskan kata kunci">
                        </div>

                        <!-- Tipe Dokumen -->
                        <div class="mb-3">
                            <select id="tipe_dokumen" name="tipe_dokumen" class="form-select">
                                <option value="">Jenis Dokumen Hukum</option>
                                @foreach($dokumenAttributes['tipeDok'] as $tipe)
                                <option value="{{ $tipe }}" {{ request('tipe_dokumen') == $tipe ? 'selected' : '' }}>
                                    {{ $tipe }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bentuk Peraturan -->
                        <div class="mb-3" id="bentuk_peraturan_container" style="display: none;">
                            <select id="bentuk_peraturan" name="bentuk_dokumen" class="form-select">
                                <option value="">Bentuk Peraturan</option>
                                @foreach($dokumenAttributes['jenisDok'] as $bentuk)
                                <option value="{{ $bentuk }}" {{ request('bentuk_dokumen') == $bentuk ? 'selected' : '' }}>
                                    {{ $bentuk }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nomor -->
                        <div class="mb-3">
                            <input type="number" id="nomor" name="nomor" class="form-control" placeholder="Nomor">
                        </div>

                        <!-- Tahun -->
                        <div class="mb-3">
                            <input type="number" id="tahun" name="tahun" class="form-control" placeholder="Tahun">
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <select id="status" name="status" class="form-select">
                                <option value="">Status Peraturan</option>
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
                @forelse ($results as $result)
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
                                        {{ $result->jenisDok }} Nomor {{ $result->nomor }} Tahun {{ $result->tahun }}
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
                {{ $results->links('pagination::simple-modern') }}
            </div>
        </div>
    </div>
</div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipeDokumen = document.getElementById('tipe_dokumen');
        const bentukPeraturanContainer = document.getElementById('bentuk_peraturan_container');

        // Function to show/hide Bentuk Peraturan
        function toggleBentukPeraturan() {
            if (tipeDokumen.value === 'Peraturan') {
                bentukPeraturanContainer.style.display = 'block';
            } else {
                bentukPeraturanContainer.style.display = 'none';
            }
        }

        // Initialize visibility on page load
        toggleBentukPeraturan();

        // Add event listener to detect changes
        tipeDokumen.addEventListener('change', toggleBentukPeraturan);
    });
</script>