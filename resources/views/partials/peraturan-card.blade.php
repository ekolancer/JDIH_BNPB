<div class="col-12 col-md-6 col-lg-3 mb-4">
    <div class="card card-peraturan h-100 border-0 shadow-sm">
        <div class="card-badge">
            {{ $item->jenis_dokumen_singkatan }}
        </div>

        <div class="card-body pt-5 px-4 d-flex flex-column">
            <div class="mb-3">
                <span class="badge-number">No. {{ $item->nomor }} Tahun {{ $item->tahun }}</span>
            </div>

            <h5 class="card-title mb-4 fw-bold">
                {{ Str::limit(strip_tags($item->judul), 150, '...') }}
            </h5>
        </div>

        <div class="card-footer bg-transparent border-0 pb-4 px-4 mt-auto">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="far fa-calendar-alt me-1 text-primary"></i>
                    {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d M Y') }}
                </small>

                <a href="{{ route('dokumen.detail', ['tipeDok' => $item->tipeDok, 'slug' => $item->slug]) }}"
                    class="btn-view-modern text-decoration-none">
                    Lihat <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
