<div>
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm bg-light">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-filter me-2"></i>Filter</h5>
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
                        <h6 class="fw-bold"><i class="fa-solid fa-list me-2"></i>JENIS DOKUMEN</h6>
                        @foreach($availableFilters['jenisDok'] as $option)
                        <div class="form-check d-flex justify-content-between align-items-center border-zinc-950">
                            <div>
                                <input class="form-check-input" type="checkbox" wire:model.defer="filters.jenisDok" value="{{ $option }}">
                                <label class="form-check-label text-uppercase">{{ $option }}</label>
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
                            <h6 class="fw-bold mb-0"><i class="fa-solid fa-list me-2"></i>TAHUN TERBIT</h6>
                            <span class="fw-bold text-primary" id="tahunValue">{{ $filters['tahun'] }}</span>
                        </div>
                        <input type="range" wire:model.defer="filters.tahun" min="1990" max="{{ date('Y') }}" class="form-range" id="tahunSlider">
                        <div class="d-flex justify-content-between mt-2">
                            <span>1990</span>
                            <span>{{ date('Y') }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button wire:click="applyFilters" class="btn btn-primary btn-sm">Terapkan</button>
                    </div>
                    <hr>

                    <!-- Other Filters -->
                    @foreach($availableFilters as $column => $values)
                    @if($column !== 'jenisDok' && $column !== 'singkatan' && $column !== 'penerbit')
                    <div class="filter-section mb-3 shadow-md">
                        <h6 class="fw-bold"><i class="fa-solid fa-list me-2"></i>{{ strtoupper(str_replace('_', ' ', $column)) }}</h6>

                        @foreach($values as $index => $value)
                        @if(!empty($value) && $value !== '-')
                        <div class="form-check d-flex justify-content-between align-items-center 
                        {{ $index >= 3 ? 'd-none more-' . $column : '' }}">
                            <div>
                                <input class="form-check-input" type="checkbox" wire:model.defer="filters.{{ $column }}" value="{{ $value }}">
                                <label class="form-check-label text-uppercase text-start">{{ $value }}</label>
                            </div>
                            <span class="badge bg-primary">{{ \App\Models\DokumenHukum::where($column, $value)->count() }}</span>
                        </div>
                        @endif
                        @endforeach

                        <!-- Tombol Tampilkan Lebih Banyak -->
                        @if(count($values) > 3)
                        <button class="btn btn-link btn-sm mt-2 toggle-more"
                            data-column="{{ $column }}">
                            Lihat lebih banyak...
                        </button>
                        @endif
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
                <div class="sort-options d-flex align-items-center gap-2">
                    <label for="sortBy" class="fw-bold text-muted mb-0">Urutkan :</label>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle btn-sm" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $sortBy === 'latest' ? 'Terbaru' : 'Populer' }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                            <li>
                                <a class="dropdown-item {{ $sortBy === 'latest' ? 'active' : '' }}" href="#" wire:click.prevent="updateSortBy('latest')">
                                    Terbaru
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $sortBy === 'popular' ? 'active' : '' }}" href="#" wire:click.prevent="updateSortBy('popular')">
                                    Populer
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div wire:loading.delay class="alert alert-info text-center custom-alert" style="font-size: 1.2rem; padding: 20px;">
                <i class="fa fa-spinner fa-spin"></i> Please wait, loading...
            </div>

            <!-- List Dokumen -->
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
                                        <h5 class="card-title text-primary fw-bold mb-0 text-uppercase text-start">
                                            @if(in_array($result->tipeDok, ['Peraturan', 'Putusan']))
                                            {{ $result->singkatan }} Nomor {{ $result->nomor }} Tahun {{ $result->tahun }}
                                            @else
                                            {{ $result->judul }}
                                            @endif
                                        </h5>
                                        <p class="card-text mb-3 text-muted text-justify" style="text-align: justify; hyphens: auto; word-wrap: break-word;">
                                            {{ Str::limit($result->judul ?? 'Tidak ada deskripsi.', 150) }}
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
                                            {{ \Carbon\Carbon::parse($result->created_at)->locale('id')->translatedFormat('d F Y') }}
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
                            Tidak ada dokumen hukum ditemukan.
                        </div>
                    </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $results->appends(request()->query())->links('pagination::simple-modern') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk slider tahun -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const slider = document.getElementById('tahunSlider');
        const output = document.getElementById('tahunValue');
        slider.oninput = function() {
            output.textContent = this.value;
        };
    });
</script>

<!-- Script untuk scroll ke atas -->
<script>
    window.addEventListener('scroll-to-top', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>

<!-- script untuk refresh URL ketika filter diterapkan -->
<script>
    window.addEventListener('update-url', event => {
        console.log("Update URL Event Triggered:", event.detail);
        console.log("Filters Data:", event.detail.filters);

        const newUrl = event.detail.filters ? '?' + event.detail.filters : window.location.pathname;
        window.history.replaceState({}, '', newUrl);
    });
</script>

<!-- Script untuk menampilkan lebih banyak filter -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-more').forEach(button => {
            button.addEventListener('click', function() {
                let column = this.getAttribute('data-column');
                let elements = document.querySelectorAll('.more-' + column);

                if (this.innerText.includes('Lihat lebih banyak')) {
                    elements.forEach(el => el.classList.remove('d-none'));
                    this.innerText = 'Lihat lebih sedikit';
                } else {
                    elements.forEach(el => el.classList.add('d-none'));
                    this.innerText = 'Lihat lebih banyak...';
                }
            });
        });
    });
</script>