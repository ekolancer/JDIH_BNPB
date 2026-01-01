<div>
    <div class="row">

        {{-- ======================================================
             SIDEBAR FILTER
        ======================================================= --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm bg-light">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="fw-bold mb-0">
                        <i class="fa-solid fa-filter me-2"></i>Filter
                    </h5>
                    <button wire:click="resetFilters"
                        class="btn btn-light btn-sm text-danger fw-bold">
                        <i class="fa-solid fa-trash-can me-2"></i>Reset
                    </button>
                </div>

                <div class="card-body">

                    {{-- Cari Judul --}}
                    <div class="mb-3">
                        <div class="d-flex">
                            <input type="text"
                                wire:model.defer="filters.judul"
                                class="form-control me-2"
                                placeholder="Cari judul...">
                            <button wire:click="applyFilters"
                                class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>

                    <hr>

                    {{-- Jenis Dokumen --}}
                    <h6 class="fw-bold mb-2">
                        <i class="fa-solid fa-list me-2"></i>Jenis Dokumen
                    </h6>

                    @foreach($availableFilters['jenis_dokumen'] as $jenis)
                    <div class="form-check d-flex justify-content-between align-items-center mb-1
     {{ $loop->index >= 5 ? 'd-none more-jenis-dokumen' : '' }}">
                        <div>
                            <input class="form-check-input"
                                type="checkbox"
                                wire:model.defer="filters.ref_jenis_dokumen_id"
                                value="{{ $jenis->id }}">
                            <label class="form-check-label text-uppercase">
                                {{ $jenis->singkatan }}
                            </label>
                        </div>
                        <span class="badge bg-primary">
                            {{ $jenis->dokumen_hukum_count }}
                        </span>
                    </div>
                    @endforeach

                    @if(count($availableFilters['jenis_dokumen']) > 5)
                    <button class="btn btn-link btn-sm px-0 toggle-more"
                        data-target="jenis-dokumen">
                        Lihat lebih banyak...
                    </button>
                    @endif

                    <div class="d-flex justify-content-end mt-2">
                        <button wire:click="applyFilters"
                            class="btn btn-primary btn-sm">
                            Terapkan
                        </button>
                    </div>

                    <hr>

                    {{-- Nomor --}}
                    <div class="mb-3">
                        <div class="d-flex">
                            <input type="number"
                                wire:model.defer="filters.nomor"
                                class="form-control me-2"
                                placeholder="Cari nomor...">
                            <button wire:click="applyFilters"
                                class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>

                    <hr>

                    {{-- Tahun --}}
                    <div class="filter-section mb-3 shadow-md">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Tahun Terbit</strong>
                            <span class="text-primary fw-bold" id="tahunValue">
                                {{ $filters['tahun'] }}
                            </span>
                        </div>
                        <input type="range"
                            wire:model.defer="filters.tahun"
                            min="1990"
                            max="{{ date('Y') }}"
                            class="form-range"
                            id="tahunSlider">
                    </div>

                    <hr>

                    {{-- Bidang Hukum --}}
                    <h6 class="fw-bold mb-2">
                        <i class="fa-solid fa-scale-balanced me-2"></i>Bidang Hukum
                    </h6>

                    @foreach($availableFilters['bidang_hukum'] as $bidang)
                    <div class="form-check d-flex justify-content-between align-items-center mb-1
     {{ $loop->index >= 5 ? 'd-none more-bidang-hukum' : '' }}">
                        <div>
                            <input class="form-check-input"
                                type="checkbox"
                                wire:model.defer="filters.ref_bidang_hukum_id"
                                value="{{ $bidang->id }}">
                            <label class="form-check-label">
                                {{ $bidang->nama }}
                            </label>
                        </div>
                        <span class="badge bg-primary">
                            {{ $bidang->dokumen_hukum_count }}
                        </span>
                    </div>
                    @endforeach

                    @if(count($availableFilters['bidang_hukum']) > 5)
                    <button class="btn btn-link btn-sm px-0 toggle-more"
                        data-target="bidang-hukum">
                        Lihat lebih banyak...
                    </button>
                    @endif

                    <div class="d-flex justify-content-end mt-2">
                        <button wire:click="applyFilters"
                            class="btn btn-primary btn-sm">
                            Terapkan
                        </button>
                    </div>

                    <hr>

                    {{-- Subjek --}}
                    <h6 class="fw-bold mb-2">
                        <i class="fa-solid fa-tags me-2"></i>Subjek
                    </h6>

                    @foreach($availableFilters['subjek'] as $subjek)
                    <div class="form-check d-flex justify-content-between align-items-center mb-1
     {{ $loop->index >= 5 ? 'd-none more-subjek' : '' }}">
                        <div>
                            <input class="form-check-input"
                                type="checkbox"
                                wire:model.defer="filters.subjek"
                                value="{{ $subjek->id }}">
                            <label class="form-check-label">
                                {{ $subjek->nama }}
                            </label>
                        </div>
                        <span class="badge bg-primary">
                            {{ $subjek->dokumen_hukum_count }}
                        </span>
                    </div>
                    @endforeach

                    @if(count($availableFilters['subjek']) > 5)
                    <button class="btn btn-link btn-sm px-0 toggle-more"
                        data-target="subjek">
                        Lihat lebih banyak...
                    </button>
                    @endif

                    <div class="d-flex justify-content-end mt-2">
                        <button wire:click="applyFilters"
                            class="btn btn-primary btn-sm">
                            Terapkan
                        </button>
                    </div>

                    <hr>

                    {{-- Status --}}
                    <h6 class="fw-bold mb-2">
                        <i class="fa-solid fa-circle-info me-2"></i>Status
                    </h6>

                    @foreach($availableFilters['status'] as $status)
                    <div class="form-check mb-1">
                        <input class="form-check-input"
                            type="checkbox"
                            wire:model.defer="filters.status"
                            value="{{ $status }}">
                        <label class="form-check-label">
                            {{ ucfirst(strtolower($status)) }}
                        </label>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-end mt-3">
                        <button wire:click="applyFilters"
                            class="btn btn-primary btn-sm">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================================================
             LIST DOKUMEN
        ======================================================= --}}
        <div class="col-md-8">

            {{-- Header hasil --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <strong>Ditemukan {{ number_format($numResults) }} hasil</strong>
                    <div class="text-muted small">
                        Dalam {{ $queryTime }} detik
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle"
                        data-bs-toggle="dropdown">
                        {{ $sortBy === 'latest' ? 'Terbaru' : 'Populer' }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" class="dropdown-item"
                                wire:click.prevent="updateSortBy('latest')">
                                Terbaru
                            </a>
                        </li>
                        <li>
                            <a href="#" class="dropdown-item"
                                wire:click.prevent="updateSortBy('popular')">
                                Populer
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div wire:loading.delay
                class="alert alert-info text-center custom-alert"
                style="font-size: 1.2rem; padding: 20px;">
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

                                    <!-- Ikon File -->
                                    <div class="align-items-center pb-3 mt-0 me-3">
                                        <img src="/img/icon/allDoc.png"
                                            alt="Dokumen"
                                            height="50"
                                            width="45">
                                    </div>

                                    <!-- Tipe Dokumen & Judul -->
                                    <div class="d-flex flex-column w-100">

                                        <h5 class="card-title text-primary fw-bold mb-0 text-uppercase text-start">
                                            @if(in_array($result->tipeDok, ['Peraturan', 'Putusan']))
                                            {{ $result->jenis_dokumen_singkatan }}
                                            Nomor {{ $result->nomor }}
                                            Tahun {{ $result->tahun }}
                                            @else
                                            {{ $result->judul }}
                                            @endif
                                        </h5>

                                        <p class="card-text mb-3 text-muted text-justify"
                                            style="hyphens: auto; word-wrap: break-word;">
                                            {{ \Illuminate\Support\Str::limit($result->judul ?? 'Tidak ada deskripsi.', 150) }}
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
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($result->created_at)
                                        ->locale('id')
                                        ->translatedFormat('d F Y') }}
                                        </small>

                                        <small class="text-muted me-3">
                                            <i class="far fa-eye me-1"></i>
                                            {{ number_format($result->view_count) }} Dilihat
                                        </small>

                                        <small class="text-muted">
                                            <i class="far fa-download me-1"></i>
                                            {{ number_format($result->download_count) }} Diunduh
                                        </small>
                                    </div>

                                    <!-- Tombol aksi -->
                                    <div class="d-flex">
                                        <a href="{{ route('dokumen.detail', [
                                        'tipeDok' => $result->tipeDok,
                                        'slug'    => $result->slug
                                    ]) }}"
                                            class="btn btn-primary btn-sm me-2">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $results->links('pagination::simple-modern') }}
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
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toggle-more').forEach(button => {
            button.addEventListener('click', () => {
                const target = button.dataset.target;
                const items = document.querySelectorAll('.more-' + target);

                const isHidden = items[0].classList.contains('d-none');

                items.forEach(el => el.classList.toggle('d-none'));

                button.textContent = isHidden ?
                    'Lihat lebih sedikit' :
                    'Lihat lebih banyak...';
            });
        });
    });
</script>