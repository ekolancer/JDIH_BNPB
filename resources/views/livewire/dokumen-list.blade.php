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
                    <button wire:click="$set('filters.judul', '')">×</button>
                </div>
                @endif

                {{-- Nomor --}}
                @if(filled($filters['nomor']))
                <div class="filter-chip">
                    Nomor: {{ $filters['nomor'] }}
                    <button wire:click="$set('filters.nomor', '')">×</button>
                </div>
                @endif

                {{-- Tahun --}}
                @if(filled($filters['tahun']))
                <div class="filter-chip">
                    Tahun: {{ $filters['tahun'] }}
                    <button wire:click="$set('filters.tahun', null)">×</button>
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
                    <button wire:click="$set('filters.ref_jenis_dokumen_id', [])">×</button>
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
                    <button wire:click="$set('filters.ref_bidang_hukum_id', [])">×</button>
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
                    <button wire:click="$set('filters.subjek', [])">×</button>
                </div>
                @endif
                @endforeach

                {{-- Status --}}
                @foreach($filters['status'] as $status)
                <div class="filter-chip">
                    Status: {{ ucfirst(strtolower($status)) }}
                    <button wire:click="$set('filters.status', [])">×</button>
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