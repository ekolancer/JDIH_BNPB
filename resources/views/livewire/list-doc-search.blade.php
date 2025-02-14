<div>
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm bg-light">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="fw-bold mb-0">Filter</h5>
                    <button wire:click="resetFilters" class="btn btn-light btn-sm text-danger fw-bold">
                        <i class="bi bi-trash3"></i> Reset
                    </button>
                </div>
                <div class="card-body">
                    @foreach($availableFilters as $column => $values)
                    <div class="filter-section mb-3">
                        <h6 class="fw-bold">{{ ucfirst(str_replace('_', ' ', $column)) }}</h6>
                        <div id="filter_{{ $column }}">
                            @foreach($values as $key => $value)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model="filters.{{ $column }}" value="{{ $value }}" id="{{ $column }}_{{ $value }}">
                                <label class="form-check-label d-flex justify-content-between w-100" for="{{ $column }}_{{ $value }}">
                                    <span>{{ $value }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- List Dokumen -->
        <div class="col-md-8">
            <div class="row">
                @forelse ($results as $result)
                <div class="col-12 mb-3">
                    <div class="card shadow-lg border-0 zoom-card">
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold mb-0">
                                {{ $result->jenisDok }} Nomor {{ $result->nomor }} Tahun {{ $result->tahun }}
                            </h5>
                            <p class="card-text mb-3 text-muted">
                                {{ $result->judul ?? 'Tidak ada deskripsi.' }}
                            </p>
                        </div>
                        <div class="card-footer bg-light border-top">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted"><i class="far fa-eye me-1"></i> {{ $result->view_count }}x Tayang</small>
                                <small class="text-muted"><i class="far fa-download me-1"></i> {{ $result->download_count }}x Unduhan</small>
                                <a href="{{ route('dokumen.detail', ['tipeDok' => $result->tipeDok, 'slug' => $result->slug]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Tidak ada data peraturan ditemukan.
                    </div>
                </div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $results->links() }}
            </div>
        </div>
    </div>
</div>