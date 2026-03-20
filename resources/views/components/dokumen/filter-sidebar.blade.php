@props(['filters', 'availableFilters', 'filterCounts'])

<div class="list-doc__sidebar">

    {{-- ── Header ───────────────────────────────────── --}}
    <div class="list-doc__sidebar-header">
        <span class="list-doc__sidebar-title">
            <i class="fa fa-sliders-h"></i> Filter
        </span>
        <button wire:click="resetFilters" class="list-doc__reset-btn">
            <i class="bi bi-x-circle"></i> Reset
        </button>
    </div>

    <div class="list-doc__sidebar-body">

        {{-- ── Judul ────────────────────────────────── --}}
        <div class="list-doc__filter-label">
            <i class="fa fa-search"></i> Judul
        </div>
        <div class="list-doc__filter-input-row">
            <input type="text"
                wire:model.defer="filters.judul"
                class="list-doc__filter-input"
                placeholder="Cari judul...">
            <button wire:click="applyFilters" class="list-doc__filter-search-btn">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <div class="list-doc__filter-divider"></div>

        {{-- ── Nomor ────────────────────────────────── --}}
        <div class="list-doc__filter-label">
            <i class="fa fa-hashtag"></i> Nomor
        </div>
        <div class="list-doc__filter-input-row">
            <input type="number"
                wire:model.defer="filters.nomor"
                class="list-doc__filter-input"
                placeholder="Cari nomor...">
            <button wire:click="applyFilters" class="list-doc__filter-search-btn">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <div class="list-doc__filter-divider"></div>

        {{-- ── Tahun ────────────────────────────────── --}}
        <div class="list-doc__slider-header">
            <div class="list-doc__filter-label" style="margin:0;">
                <i class="fa fa-calendar-alt"></i> Tahun Terbit
            </div>
            <span class="list-doc__slider-value" id="ld-tahun-val">
                {{ $filters['tahun'] ?: '—' }}
            </span>
        </div>
        <div class="list-doc__slider-wrap">
            <input type="range"
                wire:model.live="filters.tahun"
                wire:change="applyFilters"
                min="1990"
                max="{{ date('Y') }}"
                class="list-doc__slider"
                id="ld-tahun-slider">
        </div>

        <div class="list-doc__filter-divider"></div>

        {{-- ── Jenis Dokumen ────────────────────────── --}}
        <div class="list-doc__filter-label">
            <i class="fa-solid fa-book-open"></i> Jenis Dokumen
        </div>
        @php $selectedJenis = $filters['ref_jenis_dokumen_id'] ?? []; @endphp

        @foreach($availableFilters['jenis_dokumen'] as $j)
        @php
        $count = $filterCounts['jenis_dokumen'][$j->id] ?? 0;
        $isActive = in_array($j->id, $selectedJenis);
        @endphp
        <div class="list-doc__filter-item {{ $isActive ? 'active' : '' }}">
            <div class="list-doc__filter-item-left">
                <input type="checkbox"
                    wire:model.live="filters.ref_jenis_dokumen_id"
                    wire:change="applyFilters"
                    value="{{ $j->id }}"
                    id="ld-jenis-{{ $j->id }}">
                <label for="ld-jenis-{{ $j->id }}" class="text-uppercase">
                    {{ $j->singkatan }}
                </label>
            </div>
            <span class="list-doc__filter-count">{{ $count }}</span>
        </div>
        @endforeach

        <div class="list-doc__filter-divider"></div>

        {{-- ── Bidang Hukum ─────────────────────────── --}}
        <div class="list-doc__filter-label">
            <i class="fa-solid fa-scale-balanced"></i> Bidang Hukum
        </div>
        @php $shownBidang = 0; @endphp

        @foreach($availableFilters['bidang_hukum'] as $b)
        @php
        $c = $filterCounts['bidang_hukum'][$b->id] ?? 0;
        $isActive = in_array($b->id, $filters['ref_bidang_hukum_id']);
        @endphp
        @if($c > 0)
        @php $shownBidang++; @endphp
        @if($shownBidang <= 5)
            <div class="list-doc__filter-item {{ $isActive ? 'active' : '' }}">
            <div class="list-doc__filter-item-left">
                <input type="checkbox"
                    wire:model.live="filters.ref_bidang_hukum_id"
                    wire:change="applyFilters"
                    value="{{ $b->id }}"
                    id="ld-bidang-{{ $b->id }}">
                <label for="ld-bidang-{{ $b->id }}">{{ $b->nama }}</label>
            </div>
            <span class="list-doc__filter-count">{{ $c }}</span>
    </div>
    @endif
    @endif
    @endforeach

    @php
    $extraBidang = collect($availableFilters['bidang_hukum'])
    ->filter(fn($b) => ($filterCounts['bidang_hukum'][$b->id] ?? 0) > 0)
    ->slice(5);
    @endphp

    @if($extraBidang->isNotEmpty())
    <div class="list-doc__filter-extra" id="ld-extra-bidang">
        @foreach($extraBidang as $b)
        @php $isActive = in_array($b->id, $filters['ref_bidang_hukum_id']); @endphp
        <div class="list-doc__filter-item {{ $isActive ? 'active' : '' }}">
            <div class="list-doc__filter-item-left">
                <input type="checkbox"
                    wire:model.live="filters.ref_bidang_hukum_id"
                    wire:change="applyFilters"
                    value="{{ $b->id }}"
                    id="ld-bidang-{{ $b->id }}">
                <label for="ld-bidang-{{ $b->id }}">{{ $b->nama }}</label>
            </div>
            <span class="list-doc__filter-count">
                {{ $filterCounts['bidang_hukum'][$b->id] ?? 0 }}
            </span>
        </div>
        @endforeach
    </div>
    <button type="button"
        class="list-doc__filter-more-btn"
        data-target="ld-extra-bidang"
        data-extra="{{ $extraBidang->count() }}">
        <span class="list-doc__more-icon">
            <i class="fa fa-chevron-down"></i>
        </span>
        <span class="list-doc__more-label">+{{ $extraBidang->count() }} lainnya</span>
    </button>
    @endif

    <div class="list-doc__filter-divider"></div>

    {{-- ── Status ───────────────────────────────── --}}
    @php
    $visibleStatus = collect($availableFilters['status'])
    ->filter(fn($s) => ($filterCounts['status'][$s] ?? 0) > 0)
    ->values();
    @endphp

    @if($visibleStatus->isNotEmpty())
    <div class="list-doc__filter-label">
        <i class="fa-solid fa-circle-info"></i> Status
    </div>
    @foreach($visibleStatus as $status)
    @php
    $count = $filterCounts['status'][$status];
    $isActive = in_array($status, $filters['status']);
    @endphp
    <div class="list-doc__filter-item {{ $isActive ? 'active' : '' }}">
        <div class="list-doc__filter-item-left">
            <input type="checkbox"
                wire:model.live="filters.status"
                wire:change="applyFilters"
                value="{{ $status }}"
                id="ld-status-{{ Str::slug($status) }}">
            <label for="ld-status-{{ Str::slug($status) }}">
                {{ ucfirst(strtolower($status)) }}
            </label>
        </div>
        <span class="list-doc__filter-count">{{ $count }}</span>
    </div>
    @endforeach

    <div class="list-doc__filter-divider"></div>
    @endif

    {{-- ── Subjek ───────────────────────────────── --}}
    <div class="list-doc__filter-label">
        <i class="fa-solid fa-tags"></i> Subjek
    </div>
    @php
    $selectedSubjek = $filters['subjek'] ?? [];
    $visibleSubjek = collect($availableFilters['subjek'])
    ->filter(fn($s) => ($filterCounts['subjek'][$s->id] ?? 0) > 0)
    ->values();
    $subjekMain = $visibleSubjek->take(5);
    $subjekExtra = $visibleSubjek->slice(5);
    @endphp

    {{-- Jika ada yang dipilih, tampilkan yang dipilih saja --}}
    @if(!empty($selectedSubjek))
    @foreach($visibleSubjek as $s)
    @if(in_array($s->id, $selectedSubjek))
    <div class="list-doc__filter-item active">
        <div class="list-doc__filter-item-left">
            <input type="checkbox"
                wire:model.live="filters.subjek"
                wire:change="applyFilters"
                value="{{ $s->id }}"
                id="ld-subjek-{{ $s->id }}">
            <label for="ld-subjek-{{ $s->id }}" style="font-weight:600;">
                {{ $s->nama }}
            </label>
        </div>
        <span class="list-doc__filter-count">
            {{ $filterCounts['subjek'][$s->id] ?? 0 }}
        </span>
    </div>
    @endif
    @endforeach
    @else
    {{-- 5 item pertama --}}
    @foreach($subjekMain as $s)
    <div class="list-doc__filter-item">
        <div class="list-doc__filter-item-left">
            <input type="checkbox"
                wire:model.live="filters.subjek"
                wire:change="applyFilters"
                value="{{ $s->id }}"
                id="ld-subjek-{{ $s->id }}">
            <label for="ld-subjek-{{ $s->id }}">{{ $s->nama }}</label>
        </div>
        <span class="list-doc__filter-count">
            {{ $filterCounts['subjek'][$s->id] ?? 0 }}
        </span>
    </div>
    @endforeach

    {{-- Extra items --}}
    @if($subjekExtra->isNotEmpty())
    <div class="list-doc__filter-extra" id="ld-extra-subjek">
        @foreach($subjekExtra as $s)
        <div class="list-doc__filter-item">
            <div class="list-doc__filter-item-left">
                <input type="checkbox"
                    wire:model.live="filters.subjek"
                    wire:change="applyFilters"
                    value="{{ $s->id }}"
                    id="ld-subjek-{{ $s->id }}">
                <label for="ld-subjek-{{ $s->id }}">{{ $s->nama }}</label>
            </div>
            <span class="list-doc__filter-count">
                {{ $filterCounts['subjek'][$s->id] ?? 0 }}
            </span>
        </div>
        @endforeach
    </div>
    <button type="button"
        class="list-doc__filter-more-btn"
        data-target="ld-extra-subjek"
        data-extra="{{ $subjekExtra->count() }}">
        <span class="list-doc__more-icon">
            <i class="fa fa-chevron-down"></i>
        </span>
        <span class="list-doc__more-label">+{{ $subjekExtra->count() }} lainnya</span>
    </button>
    @endif
    @endif

</div>{{-- end sidebar-body --}}
</div>