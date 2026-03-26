@extends('layout.app-detail')

<!-- push css halaman detail dokumen -->
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="{{ asset('css/detail-doc.css') }}?v={{ filemtime(public_path('css/detail-doc.css')) }}" rel="stylesheet">
@endpush

@section('content')

<div class="doc-detail-wrapper">
    <div class="container mt-4 mb-5">

        <div class="doc-detail-topbar d-flex justify-content-between align-items-center mb-4">
            <a href="{{ url()->previous() }}" class="doc-detail-btn-back">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <div class="row align-items-start g-4">

            <main class="col-lg-8">
                <article class="doc-detail-card main-content shadow-sm mb-4">
                    <header class="doc-detail-header">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="doc-detail-badge-type">{{ $tipeDokLabel }}</span>
                            @if(!$dokumen->is_monografi && !$dokumen->is_artikel)
                            <span class="doc-detail-badge-type">{{ $dokumen->jenis_dokumen_singkatan }}</span>
                            @endif
                            @if(!$dokumen->is_monografi && !$dokumen->is_artikel)
                            <span class="doc-detail-badge-status {{ $dokumen->status === 'TIDAK BERLAKU' ? 'status-danger' : 'status-success' }}">
                                {{ $dokumen->status }}
                            </span>
                            @endif
                        </div>
                        <h1 class="doc-detail-title">Detail Metadata Dokumen Hukum</h1>

                        <div class="doc-detail-stats mt-4">
                            <div class="stat-item">
                                <i class="fas fa-eye text-muted"></i>
                                <div>
                                    <span class="stat-value">{{ number_format($dokumen->view_count) }}</span>
                                    <span class="stat-label">Kunjungan</span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-download text-muted"></i>
                                <div>
                                    <span class="stat-value">{{ number_format($dokumen->download_count) }}</span>
                                    <span class="stat-label">Unduhan</span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-calendar-alt text-muted"></i>
                                <div>
                                    <span class="stat-value">{{ $dokumen->created_at_formatted }}</span>
                                    <span class="stat-label">Terakhir Diperbarui</span>
                                </div>
                            </div>
                        </div>
                    </header>

                    <section class="doc-detail-metadata mt-4">
                        <!-- <h3 class="section-title">Informasi Dokumen</h3> -->
                        <div class="metadata-grid">
                            @foreach($metadata as $label => $value)
                            <div class="metadata-row">
                                <div class="metadata-label">{{ $label }}</div>
                                <div class="metadata-value">
                                    @if($label === 'Subjek' && $value instanceof \Illuminate\Support\Collection)
                                    <div class="d-flex flex-wrap gap-2">
                                        @forelse($value as $subjek)
                                        <a href="{{ route('list-doc', ['subjek' => $subjek->id]) }}" class="metadata-tag tag-primary">
                                            <i class="fas fa-tag me-1"></i> {{ $subjek->nama }}
                                        </a>
                                        @empty <span class="text-muted">—</span> @endforelse
                                    </div>
                                    @elseif($label === 'Bidang Hukum' && $value instanceof \Illuminate\Database\Eloquent\Model)
                                    <a href="{{ route('list-doc', ['bidang' => $value->id]) }}" class="metadata-tag tag-info">
                                        <i class="fas fa-gavel me-1"></i> {{ $value->nama }}
                                    </a>
                                    @elseif($label === 'Jenis Dokumen' && $value instanceof \Illuminate\Database\Eloquent\Model)
                                    <span class="fw-semibold text-dark">{{ $value->nama }}</span>
                                    @else
                                    {{ $value ?: '—' }}
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>
                </article>

                <div class="mt-2">
                    @livewire('media-file', ['dokumenId' => $dokumen->id])
                </div>
            </main>

            <!-- sidebar action -->
            <aside class="col-lg-4">
                <div class="doc-detail-card sticky-sidebar shadow-sm">
                    <h3 class="sidebar-title">Aksi Dokumen</h3>

                    <div class="sidebar-actions d-flex flex-column gap-3">
                        @if($dokumen->file_tersedia)
                        <button onclick="openFile('{{ $dokumen->pdf_view_url }}', '{{ $dokumen->file_extension }}')" class="doc-btn doc-btn-outline">
                            <i class="fas fa-book-open"></i> {{ $dokumen->button_text ?? 'Baca Dokumen' }}
                        </button>
                        @endif

                        @if($dokumen->download_allowed)
                        @if($dokumen->file_tersedia)
                        <a href="{{ route('dokumen.unduhLampiran', $dokumen->slug) }}" class="doc-btn doc-btn-primary text-decoration-none">
                            <i class="fas fa-download"></i> Unduh File
                        </a>
                        <div class="file-size-info text-center mt-1">
                            Ukuran:
                            @if($dokumen->lampiran_size['kb'] < 1000)
                                {{ number_format($dokumen->lampiran_size['kb']) }} KB
                                @else
                                {{ number_format($dokumen->lampiran_size['mb'], 2) }} MB
                                @endif
                                </div>
                                @else
                                <div class="doc-alert doc-alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> File fisik belum tersedia.
                                </div>
                                @endif
                                @endif
                        </div>

                        @if($dokumen->is_monografi && $dokumen->cover_image_url)
                        <div class="sidebar-cover mt-3 pt-3 border-top">
                            <span class="info-label d-block mb-2 text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase;">Cover Buku</span>
                            <img src="{{ $dokumen->cover_image_url }}" alt="Cover Dokumen" class="img-fluid rounded shadow-sm border">
                        </div>
                        @endif

                        <div class="sidebar-share-compact mt-3 pt-3 border-top">
                            <span class="info-label d-block mb-3 text-muted fw-bold text-center" style="font-size: 0.75rem; text-transform: uppercase;">Bagikan ke:</span>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="#" class="share-btn-sm share-fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="share-btn-sm share-x" title="X"><i class="fab fa-x-twitter"></i></a>
                                <a href="#" class="share-btn-sm share-wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                <a href="#" class="share-btn-sm share-tg" title="Telegram"><i class="fab fa-telegram-plane"></i></a>
                            </div>
                        </div>

                    </div>
            </aside>

        </div>
    </div>
</div>

<div id="docPdfOverlay" class="doc-overlay-viewer">
    <div class="doc-viewer-container">
        <div class="doc-viewer-header">
            <h5 class="mb-0 text-white fs-6">Pratinjau Dokumen</h5>
            <button class="doc-viewer-close" onclick="closeFile()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="doc-viewer-body" id="docViewerBody">
        </div>
    </div>
</div>

<script>
    function openFile(fileUrl, extension) {
        const overlay = document.getElementById('docPdfOverlay');
        const container = document.getElementById('docViewerBody');

        container.innerHTML = ''; // Clear previous content

        if (extension === 'pdf') {
            const iframe = document.createElement('iframe');
            iframe.src = "/pdfjs/web/viewer.html?file=" + encodeURIComponent(fileUrl);
            iframe.className = "w-100 h-100 border-0";
            container.appendChild(iframe);
        } else if (['jpg', 'jpeg', 'png', 'webp'].includes(extension.toLowerCase())) {
            const img = document.createElement('img');
            img.src = fileUrl;
            img.className = "doc-viewer-img";
            container.appendChild(img);
        } else {
            container.innerHTML = `<div class="d-flex h-100 align-items-center justify-content-center text-white"><p>Format file (.${extension}) tidak dapat dipratinjau.</p></div>`;
        }

        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeFile() {
        const overlay = document.getElementById('docPdfOverlay');
        const container = document.getElementById('docViewerBody');

        overlay.classList.remove('active');
        setTimeout(() => {
            container.innerHTML = '';
        }, 300); // clear after transition
        document.body.style.overflow = '';
    }
</script>

@endsection