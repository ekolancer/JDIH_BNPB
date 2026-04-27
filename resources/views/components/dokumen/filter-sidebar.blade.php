@props(['filters', 'availableFilters', 'filterCounts'])

@php
    $bidangHukumRefs = collect($availableFilters['bidang_hukum'] ?? []);
    $jenisDokumenRefs = collect($availableFilters['jenis_dokumen'] ?? []);
    $subjekRefs = collect($availableFilters['subjek'] ?? []);
    $statusRefs = collect($availableFilters['status'] ?? []);

    $selectedBidang = $filters['ref_bidang_hukum_id'] ?? [];
    $selectedJenis = $filters['ref_jenis_dokumen_id'] ?? [];
    $selectedSubjek = $filters['subjek'] ?? [];
    $selectedStatus = $filters['status'] ?? [];

    $visibleBidang = $bidangHukumRefs
        ->filter(fn($bidang) => ($filterCounts['bidang_hukum'][$bidang->id] ?? 0) > 0)
        ->values();
    $bidangMain = $visibleBidang->take(5);
    $bidangExtra = $visibleBidang->slice(5);

    $subjekMain = $subjekRefs->take(5);
    $subjekExtra = $subjekRefs->slice(5);

    $visibleStatus = $statusRefs
        ->filter(fn($status) => ($filterCounts['status'][$status] ?? 0) > 0)
        ->values();
@endphp

<div class="list-doc__sidebar">
    <div class="list-doc__sidebar-header">
        <span class="list-doc__sidebar-title">
            <i class="fa fa-sliders-h"></i> Filter
        </span>
        <button wire:click="resetFilters" class="list-doc__reset-btn">
            <i class="bi bi-x-circle"></i> Reset
        </button>
    </div>

    <div class="list-doc__sidebar-body">
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

        <div class="list-doc__slider-header">
            <div class="list-doc__filter-label" style="margin:0;">
                <i class="fa fa-calendar-alt"></i> Tahun Terbit
            </div>
            <span class="list-doc__slider-value" id="ld-tahun-val">
                {{ $filters['tahun'] ?: '-' }}
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

        <div class="list-doc__filter-label">
            <i class="fa-solid fa-scale-balanced"></i> Bidang Hukum
        </div>
        @foreach($bidangMain as $bidang)
            @php
                $count = $filterCounts['bidang_hukum'][$bidang->id] ?? 0;
                $isActive = in_array($bidang->id, $selectedBidang);
            @endphp
            <div class="list-doc__filter-item {{ $isActive ? 'active' : '' }}">
                <div class="list-doc__filter-item-left">
                    <input type="checkbox"
                        wire:model.live="filters.ref_bidang_hukum_id"
                        wire:change="applyFilters"
                        value="{{ $bidang->id }}"
                        id="ld-bidang-{{ $bidang->id }}">
                    <label for="ld-bidang-{{ $bidang->id }}">{{ $bidang->nama }}</label>
                </div>
                <span class="list-doc__filter-count">{{ $count }}</span>
            </div>
        @endforeach

        @if($bidangExtra->isNotEmpty())
            <div class="list-doc__filter-extra" id="ld-extra-bidang">
                @foreach($bidangExtra as $bidang)
                    @php
                        $count = $filterCounts['bidang_hukum'][$bidang->id] ?? 0;
                        $isActive = in_array($bidang->id, $selectedBidang);
                    @endphp
                    <div class="list-doc__filter-item {{ $isActive ? 'active' : '' }}">
                        <div class="list-doc__filter-item-left">
                            <input type="checkbox"
                                wire:model.live="filters.ref_bidang_hukum_id"
                                wire:change="applyFilters"
                                value="{{ $bidang->id }}"
                                id="ld-bidang-{{ $bidang->id }}">
                            <label for="ld-bidang-{{ $bidang->id }}">{{ $bidang->nama }}</label>
                        </div>
                        <span class="list-doc__filter-count">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
            <button type="button"
                class="list-doc__filter-more-btn"
                data-target="ld-extra-bidang"
                data-extra="{{ $bidangExtra->count() }}">
                <span class="list-doc__more-icon">
                    <i class="fa fa-chevron-down"></i>
                </span>
                <span class="list-doc__more-label">+{{ $bidangExtra->count() }} lainnya</span>
            </button>
        @endif

        <div class="list-doc__filter-divider"></div>

        <div class="list-doc__filter-label">
            <i class="fa-solid fa-book-open"></i> Jenis Dokumen
        </div>
        @foreach($jenisDokumenRefs as $jenis)
            @php
                $count = $filterCounts['jenis_dokumen'][$jenis->id] ?? 0;
                $isActive = in_array($jenis->id, $selectedJenis);
            @endphp
            <div class="list-doc__filter-item {{ $isActive ? 'active' : '' }}">
                <div class="list-doc__filter-item-left">
                    <input type="checkbox"
                        wire:model.live="filters.ref_jenis_dokumen_id"
                        wire:change="applyFilters"
                        value="{{ $jenis->id }}"
                        id="ld-jenis-{{ $jenis->id }}">
                    <label for="ld-jenis-{{ $jenis->id }}" class="text-uppercase">
                        {{ $jenis->singkatan ?: $jenis->nama }}
                    </label>
                </div>
                <span class="list-doc__filter-count">{{ $count }}</span>
            </div>
        @endforeach

        <div class="list-doc__filter-divider"></div>

        @if($visibleStatus->isNotEmpty())
            <div class="list-doc__filter-label">
                <i class="fa-solid fa-circle-info"></i> Status
            </div>
            @foreach($visibleStatus as $status)
                @php
                    $count = $filterCounts['status'][$status];
                    $isActive = in_array($status, $selectedStatus);
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

        <div class="list-doc__filter-label">
            <i class="fa-solid fa-tags"></i> Subjek
        </div>
        @foreach($subjekMain as $subjek)
            @php $isActive = in_array($subjek->id, $selectedSubjek); @endphp
            <div class="list-doc__filter-item {{ $isActive ? 'active' : '' }}">
                <div class="list-doc__filter-item-left">
                    <input type="checkbox"
                        wire:model.live="filters.subjek"
                        wire:change="applyFilters"
                        value="{{ $subjek->id }}"
                        id="ld-subjek-{{ $subjek->id }}">
                    <label for="ld-subjek-{{ $subjek->id }}">{{ $subjek->nama }}</label>
                </div>
                <span class="list-doc__filter-count">
                    {{ $filterCounts['subjek'][$subjek->id] ?? 0 }}
                </span>
            </div>
        @endforeach

        @if($subjekExtra->isNotEmpty())
            <div class="list-doc__filter-extra" id="ld-extra-subjek">
                @foreach($subjekExtra as $subjek)
                    @php $isActive = in_array($subjek->id, $selectedSubjek); @endphp
                    <div class="list-doc__filter-item {{ $isActive ? 'active' : '' }}">
                        <div class="list-doc__filter-item-left">
                            <input type="checkbox"
                                wire:model.live="filters.subjek"
                                wire:change="applyFilters"
                                value="{{ $subjek->id }}"
                                id="ld-subjek-{{ $subjek->id }}">
                            <label for="ld-subjek-{{ $subjek->id }}">{{ $subjek->nama }}</label>
                        </div>
                        <span class="list-doc__filter-count">
                            {{ $filterCounts['subjek'][$subjek->id] ?? 0 }}
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
    </div>
</div>
