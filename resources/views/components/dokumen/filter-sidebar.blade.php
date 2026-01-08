@props([
    'filters',
    'availableFilters',
    'filterCounts',
])

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

        {{-- ======================================================
             CARI JUDUL
        ======================================================= --}}
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

        {{-- ======================================================
             JENIS DOKUMEN
        ======================================================= --}}
        <h6 class="fw-bold mb-2">
            <i class="fa-solid fa-list me-2"></i>Jenis Dokumen
        </h6>

        @php
            $shownJenis = 0;
            $visibleJenis = collect($availableFilters['jenis_dokumen'])
                ->filter(fn ($j) => ($filterCounts['jenis_dokumen'][$j->id] ?? 0) > 0)
                ->count();
        @endphp

        @foreach($availableFilters['jenis_dokumen'] as $jenis)
            @php $count = $filterCounts['jenis_dokumen'][$jenis->id] ?? 0; @endphp

            @if($count > 0)
                @php $shownJenis++; @endphp
                <div class="form-check d-flex justify-content-between align-items-center mb-1
                    {{ $shownJenis > 5 ? 'd-none more-jenis-dokumen' : '' }}">
                    <div>
                        <input class="form-check-input"
                            type="checkbox"
                            wire:model.defer="filters.ref_jenis_dokumen_id"
                            value="{{ $jenis->id }}">
                        <label class="form-check-label text-uppercase">
                            {{ $jenis->singkatan }}
                        </label>
                    </div>
                    <span class="badge bg-primary">{{ $count }}</span>
                </div>
            @endif
        @endforeach

        @if($visibleJenis > 5)
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

        {{-- ======================================================
             NOMOR
        ======================================================= --}}
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

        {{-- ======================================================
             TAHUN
        ======================================================= --}}
        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <strong>Tahun Terbit</strong>
                <span class="fw-bold text-primary" id="tahunValue">
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

        {{-- ======================================================
             BIDANG HUKUM
        ======================================================= --}}
        <h6 class="fw-bold mb-2">
            <i class="fa-solid fa-scale-balanced me-2"></i>Bidang Hukum
        </h6>

        @php
            $shownBidang = 0;
            $visibleBidang = collect($availableFilters['bidang_hukum'])
                ->filter(fn ($b) => ($filterCounts['bidang_hukum'][$b->id] ?? 0) > 0)
                ->count();
        @endphp

        @foreach($availableFilters['bidang_hukum'] as $bidang)
            @php $count = $filterCounts['bidang_hukum'][$bidang->id] ?? 0; @endphp

            @if($count > 0)
                @php $shownBidang++; @endphp
                <div class="form-check d-flex justify-content-between align-items-center mb-1
                    {{ $shownBidang > 5 ? 'd-none more-bidang-hukum' : '' }}">
                    <div>
                        <input class="form-check-input"
                            type="checkbox"
                            wire:model.defer="filters.ref_bidang_hukum_id"
                            value="{{ $bidang->id }}">
                        <label class="form-check-label">
                            {{ $bidang->nama }}
                        </label>
                    </div>
                    <span class="badge bg-primary">{{ $count }}</span>
                </div>
            @endif
        @endforeach

        @if($visibleBidang > 5)
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

        {{-- ======================================================
             SUBJEK (HANYA TAMPILKAN YANG DIPILIH JIKA ADA)
        ======================================================= --}}
        <h6 class="fw-bold mb-2">
            <i class="fa-solid fa-tags me-2"></i>Subjek
        </h6>

        @php
            $selectedSubjek = array_filter($filters['subjek'] ?? []);
            $shownSubjek = 0;
            $visibleSubjek = collect($availableFilters['subjek'])
                ->filter(fn ($s) => ($filterCounts['subjek'][$s->id] ?? 0) > 0)
                ->count();
        @endphp

        @foreach($availableFilters['subjek'] as $subjek)
            @php
                $count = $filterCounts['subjek'][$subjek->id] ?? 0;
                $isSelected = in_array($subjek->id, $selectedSubjek);
            @endphp

            @if(!empty($selectedSubjek))
                @if($isSelected)
                    <div class="form-check d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <input class="form-check-input"
                                type="checkbox"
                                wire:model.defer="filters.subjek"
                                value="{{ $subjek->id }}">
                            <label class="form-check-label fw-bold text-primary">
                                {{ $subjek->nama }}
                            </label>
                        </div>
                        <span class="badge bg-primary">{{ $count }}</span>
                    </div>
                @endif
            @else
                @if($count > 0)
                    @php $shownSubjek++; @endphp
                    <div class="form-check d-flex justify-content-between align-items-center mb-1
                        {{ $shownSubjek > 5 ? 'd-none more-subjek' : '' }}">
                        <div>
                            <input class="form-check-input"
                                type="checkbox"
                                wire:model.defer="filters.subjek"
                                value="{{ $subjek->id }}">
                            <label class="form-check-label">
                                {{ $subjek->nama }}
                            </label>
                        </div>
                        <span class="badge bg-primary">{{ $count }}</span>
                    </div>
                @endif
            @endif
        @endforeach

        @if(empty($selectedSubjek) && $visibleSubjek > 5)
            <button class="btn btn-link btn-sm px-0 toggle-more"
                data-target="subjek">
                Lihat lebih banyak...
            </button>
        @endif

        <hr>

        {{-- ======================================================
             STATUS
        ======================================================= --}}
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
