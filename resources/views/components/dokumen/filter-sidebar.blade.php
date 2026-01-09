@props(['filters','availableFilters','filterCounts'])

<div class="filter-sidebar">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items mb-2">
        <strong><i class="fa fa-filter me-1"></i> Filter</strong>
        <button wire:click="resetFilters" class="btn btn-sm text-danger">Reset</button>
    </div>

    {{-- CARI JUDUL --}}
    <div class="filter-title">judul</div>
    <div class="d-flex gap-2">
        <input type="text" wire:model.defer="filters.judul"
            class="form-control form-control-sm"
            placeholder="Cari judul...">

        <button wire:click="applyFilters"
            class="btn btn-outline-primary btn-sm">
            <i class="fa fa-search"></i>
        </button>
    </div>

    <div class="filter-divider"></div>

    {{-- NOMOR DOKUMEN --}}
    <div class="filter-title">Nomor</div>

    <div class="d-flex gap-2">
        <input type="number"
            wire:model.defer="filters.nomor"
            class="form-control form-control-sm"
            placeholder="Cari nomor...">

        <button wire:click="applyFilters"
            class="btn btn-outline-primary btn-sm">
            <i class="fa fa-search"></i>
        </button>
    </div>

    <div class="filter-divider"></div>

    {{-- Tahun Terbit --}}
    <div class="filter-title d-flex justify-content-between">
        <span>Tahun Terbit</span>
        <span class="text-primary fw-bold">
            {{ $filters['tahun'] ?: '—' }}
        </span>
    </div>

    <div class="tahun-slider-wrapper">
        <input type="range"
            wire:model.live="filters.tahun"
            min="1990"
            max="{{ date('Y') }}"
            class="form-range tahun-slider"
            id="tahunSlider">

        <div class="tahun-tooltip" id="tahunTooltip">
            {{ $filters['tahun'] }}
        </div>
    </div>

    <div class="filter-divider"></div>

    {{-- JENIS DOKUMEN --}}
    <div class="filter-title">
        <i class="fa-solid fa-book-open me-2"></i>JENIS DOKUMEN
    </div>
    @php
    $selectedJenis = $filters['ref_jenis_dokumen_id'] ?? [];
    @endphp

    @foreach($availableFilters['jenis_dokumen'] as $j)
    @php
    $count = $filterCounts['jenis_dokumen'][$j->id] ?? 0;
    $isActive = in_array($j->id, $selectedJenis);
    @endphp

    <div class="filter-item {{ $isActive ? 'active' : '' }}">
        <div class="d-flex align-items">
            <input type="checkbox"
                wire:model.defer="filters.ref_jenis_dokumen_id"
                value="{{ $j->id }}">
            <label class="ms-2 text-uppercase">
                {{ $j->singkatan }}
            </label>
        </div>

        {{-- count tetap tampil walau 0 (optional) --}}
        <span class="filter-count">
            {{ $count }}
        </span>
    </div>
    @endforeach

    <div class="filter-divider"></div>

    {{-- BIDANG HUKUM --}}
    <div class="filter-title">
        <i class="fa-solid fa-scale-balanced me-2"></i>Bidang Hukum
    </div>
    @php $shown=0; @endphp
    <div class="filter-collapse expanded" data-collapse="bidang">
        @foreach($availableFilters['bidang_hukum'] as $b)
        @php $c=$filterCounts['bidang_hukum'][$b->id]??0; @endphp
        @if($c>0)
        @php $shown++; @endphp
        <div class="filter-item {{ in_array($b->id,$filters['ref_bidang_hukum_id'])?'active':'' }}
                    {{ $shown>5?'d-none more-bidang':'' }}">
            <div class="d-flex align-items">
                <input type="checkbox" wire:model.defer="filters.ref_bidang_hukum_id" value="{{ $b->id }}">
                <label class="ms-2">{{ $b->nama }}</label>
            </div>
            <span class="filter-count">{{ $c }}</span>
        </div>
        @endif
        @endforeach
    </div>
    @if($shown>5)
    <button class="filter-toggle" data-toggle="bidang">Lihat lainnya</button>
    @endif

    <div class="filter-divider"></div>

    {{-- STATUS --}}

    @php
    $visibleStatus = collect($availableFilters['status'])
    ->filter(fn($s) => ($filterCounts['status'][$s] ?? 0) > 0)
    ->values();
    @endphp

    @if($visibleStatus->isNotEmpty())
    <div class="filter-divider"></div>

    <div class="filter-title">
        <i class="fa-solid fa-info-circle me-2"></i>STATUS
    </div>

    @foreach($visibleStatus as $status)
    @php
    $count = $filterCounts['status'][$status];
    $isActive = in_array($status, $filters['status']);
    @endphp

    <div class="filter-item {{ $isActive ? 'active' : '' }}">
        <div class="d-flex align-items">
            <input type="checkbox"
                wire:model.defer="filters.status"
                value="{{ $status }}">
            <label class="ms-2">
                {{ ucfirst(strtolower($status)) }}
            </label>
        </div>

        <span class="filter-count">
            {{ $count }}
        </span>
    </div>
    @endforeach
    @endif

    <div class="filter-divider"></div>

    {{-- SUBJEK --}}
    <div class="filter-title">
        <i class="fa-solid fa-tags me-2"></i>SUBJEK
    </div>

    @php
    $selected = $filters['subjek'] ?? [];

    // Ambil subjek yang punya dokumen (count > 0)
    $visibleSubjek = collect($availableFilters['subjek'])
    ->filter(fn($s) => ($filterCounts['subjek'][$s->id] ?? 0) > 0)
    ->values();
    @endphp

    <div class="filter-group" data-group="subjek">

        {{-- Subjek Option --}}
        @if(!empty($selected))
        @foreach($visibleSubjek as $s)
        @if(in_array($s->id, $selected))
        <div class="filter-item active">
            <div class="d-flex align-items">
                <input type="checkbox"
                    wire:model.defer="filters.subjek"
                    value="{{ $s->id }}">
                <label class="ms-2 fw-bold">{{ $s->nama }}</label>
            </div>
            <span class="filter-count">
                {{ $filterCounts['subjek'][$s->id] }}
            </span>
        </div>
        @endif
        @endforeach

        {{-- No Option --}}
        @else
        {{-- 5 ITEM PERTAMA --}}
        @foreach($visibleSubjek->take(5) as $s)
        <div class="filter-item">
            <div class="d-flex align-items">
                <input type="checkbox"
                    wire:model.defer="filters.subjek"
                    value="{{ $s->id }}">
                <label class="ms-2">{{ $s->nama }}</label>
            </div>
            <span class="filter-count">
                {{ $filterCounts['subjek'][$s->id] }}
            </span>
        </div>
        @endforeach

        {{-- ITEM TAMBAHAN (COLLAPSE CONTAINER) --}}
        @if($visibleSubjek->count() > 5)
        <div class="filter-extra collapsed">
            @foreach($visibleSubjek->slice(5) as $s)
            <div class="filter-item">
                <div class="d-flex align-items">
                    <input type="checkbox"
                        wire:model.defer="filters.subjek"
                        value="{{ $s->id }}">
                    <label class="ms-2">{{ $s->nama }}</label>
                </div>
                <span class="filter-count">
                    {{ $filterCounts['subjek'][$s->id] }}
                </span>
            </div>
            @endforeach
        </div>

        {{-- TOMBOL TOGGLE --}}
        @php
        $extraCount = max(0, $visibleSubjek->count() - 5);
        @endphp

        <button type="button"
            class="filter-more-btn"
            data-toggle="subjek"
            data-extra="{{ $extraCount }}">
            <span class="label">+{{ $extraCount }} lainnya</span>
            <i class="fa fa-chevron-down"></i>
        </button>
        @endif
        @endif
    </div>

    <button wire:click="applyFilters" class="btn btn-primary btn-sm w-100 filter-apply">
        Terapkan Filter
    </button>
</div>