<div>
    <div class="row">

        {{-- ======================================================
             SIDEBAR FILTER
        ======================================================= --}}
        <div class="col-md-4 mb-3">
            <x-dokumen.filter-sidebar
                :filters="$filters"
                :availableFilters="$availableFilters"
                :filterCounts="$filterCounts" />
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

            {{-- ACTIVE FILTER CHIPS --}}
            @if(collect($filters)->flatten()->filter()->count())
            <div class="filter-chips">

                {{-- Judul --}}
                @if(filled($filters['judul']))
                <div class="filter-chip">
                    Judul: "{{ $filters['judul'] }}"
                    <button wire:click="removeFilter('judul')">×</button>
                </div>
                @endif

                {{-- Nomor --}}
                @if(filled($filters['nomor']))
                <div class="filter-chip">
                    Nomor: {{ $filters['nomor'] }}
                    <button wire:click="removeFilter('nomor')">×</button>
                </div>
                @endif

                {{-- Tahun --}}
                @if(filled($filters['tahun']))
                <div class="filter-chip">
                    Tahun: {{ $filters['tahun'] }}
                    <button wire:click="removeFilter('tahun')">×</button>
                </div>
                @endif

                {{-- Jenis Dokumen --}}
                @foreach($filters['ref_jenis_dokumen_id'] as $id)
                @php
                $jenis = $availableFilters['jenis_dokumen']->firstWhere('id', $id);
                @endphp
                @if($jenis)
                <div class="filter-chip">
                    {{ $jenis->singkatan }}
                    <button wire:click="removeFilter('ref_jenis_dokumen_id', {{ $id }})">×</button>
                </div>
                @endif
                @endforeach

                {{-- Bidang Hukum --}}
                @foreach($filters['ref_bidang_hukum_id'] as $id)
                @php
                $bidang = $availableFilters['bidang_hukum']->firstWhere('id', $id);
                @endphp
                @if($bidang)
                <div class="filter-chip">
                    {{ $bidang->nama }}
                    <button wire:click="removeFilter('ref_bidang_hukum_id', {{ $id }})">×</button>
                </div>
                @endif
                @endforeach

                {{-- Subjek --}}
                @foreach($filters['subjek'] as $id)
                @php
                $subjek = $availableFilters['subjek']->firstWhere('id', $id);
                @endphp
                @if($subjek)
                <div class="filter-chip">
                    {{ $subjek->nama }}
                    <button wire:click="removeFilter('subjek', {{ $id }})">×</button>
                </div>
                @endif
                @endforeach

                {{-- Status --}}
                @foreach($filters['status'] as $status)
                <div class="filter-chip">
                    Status: {{ ucfirst(strtolower($status)) }}
                    <button wire:click="removeFilter('status', '{{ $status }}')">×</button>
                </div>
                @endforeach

            </div>
            @endif


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
                    <div class="col-12 mb-4">
                        <div class="card doc-card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="row align-items-center">

                                    <div class="col-auto">
                                        <div class="icon-box">
                                            <img src="/img/icon/allDoc.png" alt="Icon" class="img-fluid">
                                        </div>
                                    </div>

                                    <div class="col mt-3 mt-md-0">
                                        <div class="d-flex flex-column">
                                            <div class="mb-1">
                                                <span class="badge badge-type rounded-pill">
                                                    {{ $result->tipeDok }}
                                                </span>
                                            </div>
                                            <h5 class="doc-title fw-bold mb-2">
                                                @if(in_array($result->tipeDok, ['Peraturan', 'Putusan']))
                                                {{ $result->jenis_dokumen_singkatan }} Nomor {{ $result->nomor }} Tahun {{ $result->tahun }}
                                                @else
                                                {{ $result->judul }}
                                                @endif
                                            </h5>
                                            <p class="doc-excerpt text-muted mb-3">
                                                {{ \Illuminate\Support\Str::limit($result->judul ?? 'Tidak ada deskripsi.', 150) }}
                                            </p>
                                        </div>

                                        <div class="d-flex flex-wrap justify-content-between align-items-center pt-3 border-top gap-3">
                                            <div class="doc-meta d-flex flex-wrap gap-3">
                                                <span class="small text-muted">
                                                    <i class="far fa-calendar-alt text-primary me-1"></i>
                                                    {{ \Carbon\Carbon::parse($result->created_at)->locale('id')->translatedFormat('d M Y') }}
                                                </span>
                                                <span class="small text-muted">
                                                    <i class="far fa-eye text-primary me-1"></i>
                                                    {{ number_format($result->view_count) }} Dilihat
                                                </span>
                                                <span class="small text-muted">
                                                    <i class="far fa-download text-primary me-1"></i>
                                                    {{ number_format($result->download_count) }} Diunduh
                                                </span>
                                            </div>

                                            <div class="doc-action">
                                                <a href="{{ route('dokumen.detail', ['tipeDok' => $result->tipeDok, 'slug' => $result->slug]) }}"
                                                    class="btn btn-detail rounded-pill px-4 shadow-sm">
                                                    Detail <i class="fas fa-arrow-right ms-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-soft-warning rounded-4 border-0 text-center py-4">
                            <i class="fas fa-search mb-2 d-block fs-3"></i>
                            Tidak ada dokumen hukum ditemukan.
                        </div>
                    </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-5">
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

        if (slider && output) {
            output.textContent = slider.value || '—';
            slider.oninput = () => output.textContent = slider.value;
        }
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
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.filter-more-btn');
        if (!btn) return;

        const group = btn.closest('.filter-group');
        const extra = group.querySelector('.filter-extra');
        const label = btn.querySelector('.label');

        const expanded = extra.classList.contains('expanded');
        const extraCount = btn.dataset.extra;

        extra.classList.toggle('expanded', !expanded);
        extra.classList.toggle('collapsed', expanded);

        btn.classList.toggle('expanded', !expanded);
        label.textContent = expanded ? `+${extraCount} lainnya` : 'Tutup';
    });
</script>

{{-- Script untuk filter sidebar --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.filter-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.dataset.toggle;
                document.querySelectorAll('.more-' + key)
                    .forEach(el => el.classList.toggle('d-none'));
            });
        });
    });
</script>

{{-- Script untuk tooltip slider tahun --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('tahunSlider');
        const tooltip = document.getElementById('tahunTooltip');

        if (!slider || !tooltip) return;

        const updateTooltip = () => {
            const value = slider.value;
            const min = slider.min;
            const max = slider.max;

            const percent = (value - min) / (max - min);
            const sliderWidth = slider.offsetWidth;

            tooltip.style.left = `${percent * sliderWidth}px`;
            tooltip.textContent = value;
        };

        slider.addEventListener('input', () => {
            tooltip.style.opacity = 1;
            updateTooltip();
        });

        slider.addEventListener('mouseleave', () => {
            tooltip.style.opacity = 0;
        });

        updateTooltip();
    });
</script>