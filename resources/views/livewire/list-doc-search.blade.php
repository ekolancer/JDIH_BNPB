<div>
    <!-- Search Form -->
    <form wire:submit.prevent="search">
        <div class="input-group mb-3 w-75 mx-auto">
            <input type="text"
                class="form-control"
                placeholder="Masukkan kata kunci pencarian"
                aria-label="Search"
                wire:model.defer="q">
            <button type="submit" class="btn btn-outline-warning">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <!-- Advanced Filters -->
    <button class="btn btn-outline-secondary mb-3" type="button" wire:click="$toggle('showFilters')">
        <i class="fas fa-filter"></i> Advanced Search
    </button>

    @if($showFilters)
    <div class="row mb-3">
        <div class="col-md-4">
            <select class="form-select" wire:model="tipe_dokumen">
                <option value="">Jenis Dokumen</option>
                @foreach($dokumenAttributes['tipeDok'] as $tipe)
                <option value="{{ $tipe }}">{{ $tipe }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <select class="form-select" wire:model="bentuk_dokumen">
                <option value="">Bentuk Dokumen</option>
                @foreach($dokumenAttributes['jenisDok'] as $bentuk)
                <option value="{{ $bentuk }}">{{ $bentuk }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control" placeholder="Tahun" wire:model="tahun">
        </div>
    </div>
    @endif

    <!-- List Dokumen -->
    <div class="row">
        @forelse ($results as $result)
        <div class="col-12 mb-3">
            <div class="card shadow-lg border-0 zoom-card">
                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold">
                        {{ $result->jenisDok }} Nomor {{ $result->nomor }} Tahun {{ $result->tahun }}
                    </h5>
                    <p class="card-text text-muted">{{ $result->judul ?? 'Tidak ada deskripsi.' }}</p>
                </div>
                <div class="card-footer bg-light border-top">
                    <small class="text-muted"><i class="far fa-calendar-alt"></i> {{ $result->created_at->format('d F Y') }}</small>
                    <small class="text-muted"><i class="far fa-eye"></i> {{ $result->view_count }} Dilihat</small>
                    <a href="{{ route('dokumen.detail', ['tipeDok' => $result->tipeDok, 'slug' => $result->slug]) }}" class="btn btn-primary btn-sm">Lihat</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">Tidak ada data ditemukan.</div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $results->links() }}
    </div>
</div>