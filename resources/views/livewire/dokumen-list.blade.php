<div>
    <div class="list-doc__wrap">

        {{-- ══════════════════════════════════════════
             SIDEBAR FILTER
        ══════════════════════════════════════════ --}}
        <div>
            <x-dokumen.filter-sidebar
                :filters="$filters"
                :availableFilters="$availableFilters"
                :filterCounts="$filterCounts" />
        </div>

        {{-- ══════════════════════════════════════════
             MAIN CONTENT
        ══════════════════════════════════════════ --}}
        <div class="list-doc__main">

            {{-- Header hasil & sort --}}
            <div class="list-doc__header">
                <div>
                    <div class="list-doc__result-count">
                        {{ number_format($numResults) }} dokumen ditemukan
                    </div>
                    <div class="list-doc__query-time">
                        dalam {{ $queryTime }} detik
                    </div>
                </div>

                <div class="list-doc__sort-wrap" id="ld-sort-wrap">
                    <button type="button"
                        class="list-doc__sort-btn"
                        id="ld-sort-toggle"
                        aria-expanded="false">
                        <i class="fa fa-sort-amount-down"></i>
                        <span id="ld-sort-label">
                            {{ $sortBy === 'latest' ? 'Terbaru' : 'Populer' }}
                        </span>
                        <i class="fa fa-chevron-down list-doc__sort-caret"></i>
                    </button>
                </div>

                {{-- Menu dirender di body level via JS agar bebas dari stacking context --}}
            </div>

            {{-- Active filter chips --}}
            @if(collect($filters)->flatten()->filter()->count())
            <div class="list-doc__chips">

                @if(filled($filters['judul']))
                <span class="list-doc__chip">
                    Judul: "{{ $filters['judul'] }}"
                    <button class="list-doc__chip-close"
                        wire:click="removeFilter('judul')">×</button>
                </span>
                @endif

                @if(filled($filters['nomor']))
                <span class="list-doc__chip">
                    No. {{ $filters['nomor'] }}
                    <button class="list-doc__chip-close"
                        wire:click="removeFilter('nomor')">×</button>
                </span>
                @endif

                @if(filled($filters['tahun']))
                <span class="list-doc__chip">
                    {{ $filters['tahun'] }}
                    <button class="list-doc__chip-close"
                        wire:click="removeFilter('tahun')">×</button>
                </span>
                @endif

                @foreach($filters['ref_jenis_dokumen_id'] as $id)
                @php $jenis = $availableFilters['jenis_dokumen']->firstWhere('id', $id); @endphp
                @if($jenis)
                <span class="list-doc__chip">
                    {{ $jenis->singkatan }}
                    <button class="list-doc__chip-close"
                        wire:click="removeFilter('ref_jenis_dokumen_id', {{ $id }})">×</button>
                </span>
                @endif
                @endforeach

                @foreach($filters['ref_bidang_hukum_id'] as $id)
                @php $bidang = $availableFilters['bidang_hukum']->firstWhere('id', $id); @endphp
                @if($bidang)
                <span class="list-doc__chip">
                    {{ $bidang->nama }}
                    <button class="list-doc__chip-close"
                        wire:click="removeFilter('ref_bidang_hukum_id', {{ $id }})">×</button>
                </span>
                @endif
                @endforeach

                @foreach($filters['subjek'] as $id)
                @php $subjek = $availableFilters['subjek']->firstWhere('id', $id); @endphp
                @if($subjek)
                <span class="list-doc__chip">
                    {{ $subjek->nama }}
                    <button class="list-doc__chip-close"
                        wire:click="removeFilter('subjek', {{ $id }})">×</button>
                </span>
                @endif
                @endforeach

                @foreach($filters['status'] as $status)
                <span class="list-doc__chip">
                    {{ ucfirst(strtolower($status)) }}
                    <button class="list-doc__chip-close"
                        wire:click="removeFilter('status', '{{ $status }}')">×</button>
                </span>
                @endforeach

            </div>
            @endif

            {{-- Loading --}}
            <div wire:loading.delay class="list-doc__loading">
                <i class="fa fa-spinner fa-spin"></i>
                <span>Memuat dokumen...</span>
            </div>

            {{-- List dokumen --}}
            <div wire:loading.remove>

                @forelse ($results as $result)
                <div class="list-doc__card">

                    {{-- Icon --}}
                    <div class="list-doc__icon">
                        <img src="/img/icon/allDoc.png" alt="Dokumen">
                    </div>

                    {{-- Content --}}
                    <div class="list-doc__card-body">

                        {{-- Badge row: tipe + status --}}
                        <div class="list-doc__badge-row">
                            @php
                            $tipeLabel = match($result->tipeDok) {
                            'Peraturan' => 'Peraturan Perundang-Undangan',
                            'Putusan' => 'Putusan',
                            'Monografi' => 'Monografi',
                            'Artikel' => 'Artikel',
                            default => $result->tipeDok,
                            };
                            $statusVal = strtoupper($result->status ?? '');
                            $isBerlaku = $statusVal === 'BERLAKU';
                            @endphp

                            <span class="list-doc__badge-type">
                                {{ $tipeLabel }}
                            </span>

                            @if($statusVal)
                            <span class="list-doc__badge-status
                                {{ $isBerlaku ? 'list-doc__badge-status--berlaku' : 'list-doc__badge-status--tidak' }}">
                                <span class="list-doc__badge-dot"></span>
                                {{ $isBerlaku ? 'Berlaku' : 'Tidak Berlaku' }}
                            </span>
                            @endif
                        </div>

                        <div class="list-doc__card-title">
                            @if(in_array($result->tipeDok, ['Peraturan', 'Putusan']))
                            {{ $result->jenis_dokumen_singkatan }}
                            Nomor {{ $result->nomor }}
                            Tahun {{ $result->tahun }}
                            @else
                            {{ $result->judul }}
                            @endif
                        </div>

                        <div class="list-doc__card-excerpt">
                            {{ \Illuminate\Support\Str::limit($result->judul ?? 'Tidak ada deskripsi.', 150) }}
                        </div>

                        <div class="list-doc__card-footer">
                            <div class="list-doc__meta">
                                <span class="list-doc__meta-item">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($result->created_at)->locale('id')->translatedFormat('d M Y') }}
                                </span>
                                <span class="list-doc__meta-item">
                                    <i class="far fa-eye"></i>
                                    {{ number_format($result->view_count) }}
                                </span>
                                <span class="list-doc__meta-item">
                                    <i class="far fa-download"></i>
                                    {{ number_format($result->download_count) }}
                                </span>
                            </div>

                            <a href="{{ route('dokumen.detail', ['tipeDok' => $result->tipeDok, 'slug' => $result->slug]) }}"
                                class="list-doc__btn-detail">
                                Detail <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                </div>
                @empty
                <div class="list-doc__empty">
                    <i class="fas fa-search"></i>
                    <p>Tidak ada dokumen hukum ditemukan.</p>
                </div>
                @endforelse

                <div class="list-doc__pagination">
                    {{ $results->links('pagination::simple-modern') }}
                </div>

            </div>{{-- end wire:loading.remove --}}

        </div>{{-- end main --}}
    </div>{{-- end wrap --}}
</div>

{{-- ══════════════════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════════════════ --}}
<script>
    window.__ldSortComponentId = '{{ $this->getId() }}';

    document.addEventListener('DOMContentLoaded', () => {

        // ── Slider tahun ──────────────────────────────────────
        const slider = document.getElementById('ld-tahun-slider');
        const valEl = document.getElementById('ld-tahun-val');

        function updateSlider() {
            if (!slider || !valEl) return;
            const min = +slider.min || 1990;
            const max = +slider.max || new Date().getFullYear();
            const val = +slider.value;
            const pct = ((val - min) / (max - min)) * 100;
            slider.style.setProperty('--slider-pct', pct + '%');
            valEl.textContent = val;
        }

        if (slider) {
            updateSlider();
            slider.addEventListener('input', updateSlider);
        }

        // ── More/collapse toggle ──────────────────────────────
        document.addEventListener('click', e => {
            const btn = e.target.closest('.list-doc__filter-more-btn');
            if (!btn) return;

            const targetId = btn.dataset.target;
            const extra = document.getElementById(targetId);
            const label = btn.querySelector('.list-doc__more-label');
            const icon = btn.querySelector('i');
            const count = btn.dataset.extra;

            if (!extra) return;

            const expanded = extra.classList.contains('expanded');
            extra.classList.toggle('expanded', !expanded);
            btn.classList.toggle('expanded', !expanded);

            if (icon) icon.style.transform = expanded ? '' : 'rotate(180deg)';
            if (label) label.textContent = expanded ? `+${count} lainnya` : 'Tutup';
        });

        // ── Custom sort dropdown (fixed + append to body) ─────
        const sortWrap = document.getElementById('ld-sort-wrap');
        const sortToggle = document.getElementById('ld-sort-toggle');

        // Buat menu dan append ke body agar bebas dari stacking context
        let sortMenu = document.getElementById('ld-sort-menu-portal');
        if (!sortMenu) {
            sortMenu = document.createElement('div');
            sortMenu.id = 'ld-sort-menu-portal';
            sortMenu.className = 'list-doc__sort-menu';
            sortMenu.style.display = 'none';
            sortMenu.innerHTML = `
<button type="button" class="list-doc__sort-option" data-value="latest" data-label="Terbaru">
    <i class="fa fa-clock"></i> Terbaru
</button>
<button type="button" class="list-doc__sort-option" data-value="popular" data-label="Populer">
    <i class="fa fa-fire"></i> Populer
</button>
`;
            document.body.appendChild(sortMenu);
        }

        // Tandai option yang aktif sesuai Livewire state
        const currentSort = sortToggle?.querySelector('#ld-sort-label')?.textContent?.trim();
        sortMenu.querySelectorAll('.list-doc__sort-option').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.label === currentSort);
        });

        function openSortMenu() {
            if (!sortToggle) return;
            const rect = sortToggle.getBoundingClientRect();
            const menuW = 150;
            let left = rect.right - menuW + window.scrollX;
            if (left < 8) left = rect.left + window.scrollX;
            sortMenu.style.top = (rect.bottom + window.scrollY + 6) + 'px';
            sortMenu.style.left = left + 'px';
            sortMenu.style.width = menuW + 'px';
            sortMenu.style.display = 'block';
            sortToggle.setAttribute('aria-expanded', 'true');
            sortToggle.classList.add('active');
        }

        function closeSortMenu() {
            sortMenu.style.display = 'none';
            sortToggle?.setAttribute('aria-expanded', 'false');
            sortToggle?.classList.remove('active');
        }

        if (sortToggle) {
            sortToggle.addEventListener('click', e => {
                e.stopPropagation();
                sortMenu.style.display === 'block' ? closeSortMenu() : openSortMenu();
            });
        }

        sortMenu.addEventListener('click', e => {
            const btn = e.target.closest('.list-doc__sort-option');
            if (!btn) return;
            // Update label di tombol
            const label = document.getElementById('ld-sort-label');
            if (label) label.textContent = btn.dataset.label;
            // Tandai active
            sortMenu.querySelectorAll('.list-doc__sort-option')
                .forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            closeSortMenu();
            // Panggil method Livewire via component ID yang di-embed dari Blade
            const value = btn.dataset.value;
            if (window.Livewire && window.__ldSortComponentId) {
                Livewire.find(window.__ldSortComponentId)?.call('updateSortBy', value);
            }
        });

        document.addEventListener('click', e => {
            if (!sortWrap?.contains(e.target) && !sortMenu?.contains(e.target)) {
                closeSortMenu();
            }
        });

        window.addEventListener('scroll', closeSortMenu, {
            passive: true
        });
        window.addEventListener('resize', closeSortMenu, {
            passive: true
        });
        // ── Scroll to top on pagination ───────────────────────
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        // ── URL update ────────────────────────────────────────
        window.addEventListener('update-url', e => {
            const newUrl = e.detail.filters ?
                '?' + e.detail.filters :
                window.location.pathname;
            window.history.replaceState({}, '', newUrl);
        });
    });
</script>