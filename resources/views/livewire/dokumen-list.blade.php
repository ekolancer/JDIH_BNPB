<div>
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm bg-light">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="fw-bold mb-0">Filter</h5>
                    <button wire:click="resetFilters" class="btn btn-light btn-sm text-danger fw-bold">
                        <i class="fa-solid fa-trash-can me-2"></i> Reset
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filter by Judul -->
                    <div class="filter-section mb-3 shadow-md">
                        <div class="d-flex">
                            <input type="text" wire:model.defer="filters.judul" class="form-control me-2" placeholder="Cari judul...">
                            <button wire:click="applyFilters" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                    <hr>
                    <!-- Filter by Jenis Dokumen -->
                    <div class="filter-section mb-3 shadow-md">
                        <h6 class="fw-bold">Jenis Dokumen</h6>
                        @foreach($availableFilters['jenisDok'] as $option)
                        <div class="form-check d-flex justify-content-between align-items-center border-zinc-950">
                            <div>
                                <input class="form-check-input" type="checkbox" wire:model.defer="filters.jenisDok" value="{{ $option }}">
                                <label class="form-check-label">{{ $option }}</label>
                            </div>
                            <span class="badge bg-primary">{{ \App\Models\DokumenHukum::where('jenisDok', $option)->count() }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button wire:click="applyFilters" class="btn btn-primary btn-sm">Terapkan</button>
                    </div>
                    <hr>
                    <!-- Filter by Nomor -->
                    <div class="filter-section mb-3 shadow-md">
                        <div class="d-flex">
                            <input type="number" wire:model.defer="filters.nomor" min="1" max="300" class="form-control me-2" placeholder="Cari berdasarkan nomor...">
                            <button wire:click="applyFilters" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                    <hr>
                    <!-- Filter by Tahun Terbit -->
                    <div class="filter-section mb-3 shadow-md">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Tahun Terbit</h6>
                            <span class="fw-bold text-primary" id="tahunValue">{{ $filters['tahun'] }}</span>
                        </div>
                        <input type="range" wire:model.defer="filters.tahun" min="1990" max="2025" class="form-range" id="tahunSlider">
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button wire:click="applyFilters" class="btn btn-primary btn-sm">Terapkan</button>
                    </div>
                    <hr>

                    <!-- Other Filters -->
                    @foreach($availableFilters as $column => $values)
                    @if($column !== 'jenisDok')
                    <div class="filter-section mb-3 shadow-md">
                        <h6 class="fw-bold">{{ ucfirst(str_replace('_', ' ', $column)) }}</h6>
                        @foreach($values as $value)
                        <div class="form-check d-flex justify-content-between align-items-center">
                            <div>
                                <input class="form-check-input" type="checkbox" wire:model.defer="filters.{{ $column }}" value="{{ $value }}">
                                <label class="form-check-label">{{ $value }}</label>
                            </div>
                            <span class="badge bg-primary">{{ \App\Models\DokumenHukum::where($column, $value)->count() }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button wire:click="applyFilters" class="btn btn-primary btn-sm">Terapkan</button>
                    </div>
                    <hr>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- List Dokumen -->
        <div class="col-md-8">
            <!-- Filter Aktif -->
            <div class="mb-2">
                @if(count($activeFilters) > 0)
                <div class="d-flex flex-wrap gap-2 p-2 border rounded bg-light">
                    @foreach($activeFilters as $filter)
                    <span class="badge bg-primary d-flex align-items-center">
                        {{ $filter }}
                        <button type="button" class="btn-close btn-close-white ms-2" wire:click="removeFilter('{{ $filter }}')" style="font-size: 0.75rem;"></button>
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Hasil Pencarian dan Sorting -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Bagian kiri: Jumlah hasil pencarian -->
                <div>
                    <strong>Ditemukan {{ number_format($numResults) }} hasil</strong>
                    <div class="text-muted small">Dalam {{ round($queryTime, 3) }} detik</div>
                </div>

                <!-- Bagian kanan: Dropdown Sorting -->
                <div class="dropdown">
                    <button class="btn btn-primary btn-sm d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-sort me-2"></i> Urutkan
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#" wire:click="sortBy('created_at')">
                                Terbaru
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" wire:click="sortBy('view_count')">
                                Terpopuler
                            </a>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="col-12">
                <div wire:loading class="alert alert-info text-center">
                    Tunggu sebentar.
                </div>
            </div>

            <div wire:loading.remove>
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
                                            @if(in_array($result->tipeDok, ['Peraturan', 'Putusan']))
                                            {{ $result->singkatan }} Nomor {{ $result->nomor }} Tahun {{ $result->tahun }}
                                            @else
                                            {{ $result->judul }}
                                            @endif
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
                                            {{ \Carbon\Carbon::parse($result->created_at)->translatedFormat('d F Y') }}
                                        </small>
                                        <small class="text-muted me-3">
                                            <i class="far fa-eye me-1" style="font-weight: bold;"></i>
                                            {{ $result->view_count }} Dilihat
                                        </small>
                                        <small class="text-muted">
                                            <i class="far fa-download me-1" style="font-weight: bold;"></i>
                                            {{ $result->download_count }} Diunduh
                                        </small>
                                    </div>
                                    <!-- Tombol aksi -->
                                    <div class="d-flex">
                                        <a href="{{ route('dokumen.detail', ['tipeDok' => $result->tipeDok, 'slug' => $result->slug]) }}" class="btn btn-primary btn-sm me-2">
                                            <i class="fas fa-eye me-2"></i>Detail
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
                <div class="d-flex justify-content-center mt-4">
                    {{ $results->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const slider = document.getElementById('tahunSlider');
        const output = document.getElementById('tahunValue');
        slider.oninput = function() {
            output.textContent = this.value;
        };

        window.addEventListener('scroll-to-top', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>