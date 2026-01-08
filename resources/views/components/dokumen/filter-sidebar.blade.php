@props(['filters','availableFilters','filterCounts'])

<div class="filter-sidebar">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong><i class="fa fa-filter me-1"></i> Filter</strong>
        <button wire:click="resetFilters" class="btn btn-sm text-danger">Reset</button>
    </div>

    {{-- CARI JUDUL --}}
    <div class="mb-2">
        <input type="text" wire:model.defer="filters.judul"
            class="form-control form-control-sm"
            placeholder="Cari judul...">
    </div>

    <div class="filter-divider"></div>

    {{-- NOMOR DOKUMEN --}}
    <div class="filter-title">Nomor Dokumen</div>

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

    <input type="range"
        wire:model.live="filters.tahun"
        min="1990"
        max="{{ date('Y') }}"
        class="form-range">

    <div class="filter-divider"></div>

    {{-- JENIS DOKUMEN --}}
    <div class="filter-title">Jenis Dokumen</div>
    @php $shown=0; @endphp
    <div class="filter-collapse expanded" data-collapse="jenis">
        @foreach($availableFilters['jenis_dokumen'] as $j)
        @php $c=$filterCounts['jenis_dokumen'][$j->id]??0; @endphp
        @if($c>0)
        @php $shown++; @endphp
        <div class="filter-item {{ in_array($j->id,$filters['ref_jenis_dokumen_id'])?'active':'' }}
                    {{ $shown>5?'d-none more-jenis':'' }}">
            <div class="d-flex align-items">
                <input type="checkbox" wire:model.defer="filters.ref_jenis_dokumen_id" value="{{ $j->id }}">
                <label class="ms-2 text-uppercase">{{ $j->singkatan }}</label>
            </div>
            <span class="filter-count">{{ $c }}</span>
        </div>
        @endif
        @endforeach
    </div>
    @if($shown>5)
    <button class="filter-toggle" data-toggle="jenis">Lihat lainnya</button>
    @endif

    <div class="filter-divider"></div>

    {{-- BIDANG HUKUM --}}
    <div class="filter-title">Bidang Hukum</div>
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

    {{-- SUBJEK --}}
    <div class="filter-title">Subjek</div>
    @php
    $selected=$filters['subjek']??[];
    $shown=0;
    @endphp
    <div class="filter-collapse expanded" data-collapse="subjek">
        @foreach($availableFilters['subjek'] as $s)
        @php
        $c=$filterCounts['subjek'][$s->id]??0;
        $sel=in_array($s->id,$selected);
        @endphp

        @if(!empty($selected))
        @if($sel)
        <div class="filter-item active">
            <div class="d-flex align-items">
                <input type="checkbox" wire:model.defer="filters.subjek" value="{{ $s->id }}">
                <label class="ms-2 fw-bold">{{ $s->nama }}</label>
            </div>
            <span class="filter-count">{{ $c }}</span>
        </div>
        @endif
        @else
        @if($c>0)
        @php $shown++; @endphp
        <div class="filter-item {{ $shown>5?'d-none more-subjek':'' }}">
            <div class="d-flex align-items">
                <input type="checkbox" wire:model.defer="filters.subjek" value="{{ $s->id }}">
                <label class="ms-2">{{ $s->nama }}</label>
            </div>
            <span class="filter-count">{{ $c }}</span>
        </div>
        @endif
        @endif
        @endforeach
    </div>
    @if(empty($selected) && $shown>5)
    <button class="filter-toggle" data-toggle="subjek">Lihat lainnya</button>
    @endif

    <button wire:click="applyFilters" class="btn btn-primary btn-sm w-100 filter-apply">
        Terapkan Filter
    </button>
</div>