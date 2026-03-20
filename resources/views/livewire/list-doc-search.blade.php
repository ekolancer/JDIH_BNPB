<div>
    <div class="list-doc__wrap">

        {{-- ══════════════════════════════════════════
             SIDEBAR FILTER
        ══════════════════════════════════════════ --}}
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
                @foreach($availableFilters as $column => $values)
                <div class="list-doc__filter-label">
                    {{ ucfirst(str_replace('_', ' ', $column)) }}
                </div>

                @foreach($values as $value)
                <div class="list-doc__filter-item {{ in_array($value, $filters[$column] ?? []) ? 'active' : '' }}">
                    <div class="list-doc__filter-item-left">
                        <input type="checkbox"
                            wire:model="filters.{{ $column }}"
                            value="{{ $value }}"
                            id="sf-{{ $column }}-{{ Str::slug($value) }}">
                        <label for="sf-{{ $column }}-{{ Str::slug($value) }}">
                            {{ $value }}
                        </label>
                    </div>
                </div>
                @endforeach

                <div class="list-doc__filter-divider"></div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════
             LIST DOKUMEN
        ══════════════════════════════════════════ --}}
        <div class="list-doc__main">

            @forelse ($results as $result)
            <div class="list-doc__card">

                <div class="list-doc__icon">
                    <img src="/img/icon/allDoc.png" alt="Dokumen">
                </div>

                <div class="list-doc__card-body">

                    <div class="list-doc__card-title">
                        {{ $result->jenisDok }}
                        Nomor {{ $result->nomor }}
                        Tahun {{ $result->tahun }}
                    </div>

                    <div class="list-doc__card-excerpt">
                        {{ $result->judul ?? 'Tidak ada deskripsi.' }}
                    </div>

                    <div class="list-doc__card-footer">
                        <div class="list-doc__meta">
                            <span class="list-doc__meta-item">
                                <i class="far fa-eye"></i>
                                {{ $result->view_count }}x Tayang
                            </span>
                            <span class="list-doc__meta-item">
                                <i class="far fa-download"></i>
                                {{ $result->download_count }}x Unduh
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
                <p>Tidak ada data peraturan ditemukan.</p>
            </div>
            @endforelse

            <div class="list-doc__pagination">
                {{ $results->links() }}
            </div>

        </div>
    </div>
</div>