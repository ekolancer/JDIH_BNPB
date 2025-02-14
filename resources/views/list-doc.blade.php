@extends('layout.app-clear')

@section('content')

<div class="container mt-2" style="padding: 50px;">

    <div class="row align-items-start">
        <!-- Kolom Filter Dokumen -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm bg-light">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="fw-bold mb-0">Filter</h5>
                    <button class="btn btn-primary btn-sm text-warning fw-bold">
                        <i class="bi bi-trash3"></i> Reset
                    </button>
                </div>
                <div class="card-body">
                    @foreach($data as $column => $values)
                    <div class="filter-section mb-3">
                        <h6 class="fw-bold">{{ $filterNames[$column] ?? ucfirst(str_replace('_', ' ', $column)) }}</h6>
                        <div id="filter_{{ $column }}">
                            @foreach($values as $key => $count)
                            <div class="form-check {{ $loop->index >= 3 ? 'd-none extra-'.$column : '' }}">
                                <input class="form-check-input" type="checkbox" name="{{ $column }}[]" value="{{ $key }}" id="{{ $column }}_{{ $key }}">
                                <label class="form-check-label d-flex justify-content-between w-100" for="{{ $column }}_{{ $key }}">
                                    <span>{{ $key }}</span>
                                    <span class="badge bg-secondary">{{ $count }}</span>
                                </label>
                            </div>
                            @endforeach
                            @if(count($values) > 3)
                            <button class="btn btn-link p-0 text-primary" onclick="toggleFilter('{{ $column }}')">
                                <span id="toggle-text-{{ $column }}">Lebih Banyak</span>
                            </button>
                            @endif
                        </div>
                    </div>
                    <hr>
                    @endforeach
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
                                <!-- Tombol aksi -->
                                <div class="d-flex">
                                    <a href="{{ route('dokumen.detail', ['tipeDok' => $result->tipeDok, 'slug' => $result->slug]) }}" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
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
    function toggleFilter(column) {
        let hiddenItems = document.querySelectorAll('.extra-' + column);
        let toggleButton = document.getElementById('toggle-text-' + column);
        hiddenItems.forEach(item => item.classList.toggle('d-none'));
        toggleButton.textContent = toggleButton.textContent === "Lebih Banyak" ? "Lebih Sedikit" : "Lebih Banyak";
    }
</script>