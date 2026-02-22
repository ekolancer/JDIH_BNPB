@extends('layout.app-detail')

@section('content')

<style>
    /* Modern Badge Styles */
    .modern-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        margin: 4px 6px 4px 0;
        border-radius: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 12px;
        font-weight: 500;
        text-transform: capitalize;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .modern-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .modern-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .modern-badge:hover::before {
        left: 100%;
    }

    .modern-badge i {
        margin-right: 6px;
        font-size: 12px;
        opacity: 0.9;
    }

    /* ================= PDF OVERLAY FIXED ================= */

    .pdf-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(3px);

        display: none;
        /* PASTI none saat load */
        align-items: center;
        justify-content: center;

        z-index: 99999;
    }

    .pdf-overlay.active {
        display: flex;
        /* hanya aktif saat dibuka */
    }

    .pdf-container {
        width: 80%;
        height: 85%;
        background: #1e1e1e;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6);
        position: relative;
    }

    .pdf-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .pdf-close {
        position: absolute;
        top: 12px;
        right: 16px;
        background: rgba(0, 0, 0, 0.6);
        border: none;
        color: #fff;
        font-size: 16px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 100000;
    }

    .pdf-close:hover {
        background: red;
    }

    #toolbarContainer {
        background: #2c2f36 !important;
    }

    /* ================= MOBILE FULLSCREEN MODE ================= */

    @media (max-width: 768px) {

        .pdf-container {
            width: 100%;
            height: 100%;
            border-radius: 0;
        }

        .pdf-overlay {
            backdrop-filter: none;
            background: #000;
        }

        .pdf-close {
            top: 10px;
            right: 10px;
            width: 38px;
            height: 38px;
            font-size: 18px;
        }

    }
</style>

<!-- Section List Biru -->
<section class="file-title-section">
    <div class="container"></div>
</section>

<!-- Kontainer Detail Dokumen -->
<div class="container mt-5">
    <div class="row align-items-start">
        <!-- Detail Container -->
        <div class="col-md-8">
            <div class="container-detail shadow-lg p-4 bg-white rounded">
                <div class="title-section mb-3">
                    <h2 class="fw-bold text-primary">
                        <i class="fas fa-list me-2"></i> DETAIL DOKUMEN
                        <!-- @if(in_array($dokumen->tipeDok, ['Peraturan', 'Putusan']))
                        {{ $dokumen->jenis_dokumen_singkatan }} Nomor {{ $dokumen->nomor }} Tahun {{ $dokumen->tahun }}
                        @else
                        {{ $dokumen->judul }}
                        @endif -->
                    </h2>
                    <hr>
                    <!-- sekilas info -->
                    <div class="d-flex text-muted justify-content mt-2">
                        <!-- Created At -->
                        <span class="bg-info text-dark px-3 py-2 d-inline-block">
                            <i class="fas fa-calendar-alt me-1"></i> {{ $dokumen->created_at_formatted }}
                        </span>

                        <!-- View Count -->
                        <span class="bg-success text-white px-3 py-2 d-inline-block">
                            <i class="fas fa-eye me-1"></i> {{ $dokumen->view_count }} Dilihat
                        </span>

                        <!-- Download Count -->
                        <span class="bg-warning text-dark px-3 py-2 d-inline-block">
                            <i class="fas fa-download me-1"></i> {{ $dokumen->download_count }} Diunduh
                        </span>

                        <!-- Tombol Baca -->
                        @if($dokumen->file_tersedia)
                        <span class="ms-auto bg-primary text-white px-3 py-2 d-inline-block">

                            <a href="javascript:void(0)"
                                onclick="openFile('{{ $dokumen->pdf_view_url }}', '{{ $dokumen->file_extension }}')"
                                class="text-white text-decoration-none">

                                <i class="fas fa-book-open-reader me-1"></i>
                                {{ $dokumen->button_text }}

                            </a>
                        </span>
                        @endif
                    </div>

                </div>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th class="table-primary">Metadata</th>
                            <th class="table-primary">Keterangan</th>
                        </tr>

                        @foreach($metadata as $label => $value)
                        <tr>
                            <th>{{ $label }}</th>
                            <td>

                                {{-- SUBJEK (many to many + link filter) --}}
                                @if($label === 'Subjek' && $value instanceof \Illuminate\Support\Collection)
                                @forelse($value as $subjek)
                                <a href="{{ url('/dokumen', ['subjek' => $subjek->nama]) }}"
                                    class="modern-badge me-1 text-decoration-none text-capitalize">
                                    <i class="fas fa-tag"></i> {{ $subjek->nama }}
                                </a>
                                @empty
                                —
                                @endforelse

                                {{-- BIDANG HUKUM (ref + link filter) --}}
                                @elseif($label === 'Bidang Hukum' && $value instanceof \Illuminate\Database\Eloquent\Model)
                                <a href="{{ url('/dokumen', ['bidang' => $value->nama]) }}"
                                    class="modern-badge me-1 text-decoration-none text-capitalize">
                                    <i class="fas fa-tag"></i>{{ $value->nama }}
                                </a>

                                {{-- JENIS DOKUMEN (ref) --}}
                                @elseif($label === 'Jenis Dokumen' && $value instanceof \Illuminate\Database\Eloquent\Model)
                                {{ $value->nama }}

                                {{-- STATUS --}}
                                @elseif($label === 'Status')
                                <span class="badge bg-info">
                                    {{ ucfirst(strtolower($value)) }}
                                </span>

                                {{-- DEFAULT --}}
                                @else
                                {{ $value ?: '—' }}

                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- ACTION BAR --}}
                <div class="action-bar mt-4">

                    <div class="back-wrapper">
                        <!-- Tombol Kembali -->
                        <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Kembali</a>

                        <!-- Menampilkan Komponen Livewire -->
                        @livewire('media-file', ['dokumenId' => $dokumen->id])
                    </div>

                    <div class="share-wrapper">
                        <span class="share-label">Bagikan Dokumen:</span>

                        <div class="share-icons">

                            {{-- X / Twitter --}}
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}"
                                onclick="openSharePopup(this.href, 'Twitter'); return false;"
                                class="share-btn twitter"
                                data-tooltip="Bagikan ke X">
                                <svg viewBox="0 0 24 24">
                                    <path d="M18.244 2H21l-6.561 7.506L22 22h-6.828l-5.34-7.01L3.9 22H1l7.024-8.027L2 2h6.828l4.83 6.337L18.244 2z" />
                                </svg>
                            </a>

                            {{-- Facebook --}}
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}"
                                onclick="openSharePopup(this.href, 'Facebook'); return false;"
                                class="share-btn facebook"
                                data-tooltip="Bagikan ke Facebook">
                                <svg viewBox="0 0 24 24">
                                    <path d="M22 12A10 10 0 1 0 10.5 21.95V14.89H7.9V12h2.6V9.8c0-2.57 1.53-3.99 3.88-3.99 1.12 0 2.29.2 2.29.2v2.5h-1.29c-1.27 0-1.67.79-1.67 1.6V12h2.84l-.45 2.89h-2.39v7.06A10 10 0 0 0 22 12z" />
                                </svg>
                            </a>

                            {{-- Telegram --}}
                            <a href="https://t.me/share/url?url={{ urlencode(Request::fullUrl()) }}"
                                onclick="openSharePopup(this.href, 'Telegram'); return false;"
                                class="share-btn telegram"
                                data-tooltip="Bagikan ke Telegram">
                                <svg viewBox="0 0 24 24">
                                    <path d="M9.04 15.5l-.38 5.34c.54 0 .77-.23 1.05-.51l2.52-2.41 5.22 3.82c.96.53 1.64.25 1.9-.89l3.45-16.2c.32-1.49-.54-2.08-1.47-1.73L1.59 9.73c-1.44.56-1.42 1.36-.25 1.72l5.43 1.69L19.5 5.8c.59-.37 1.13-.16.69.21" />
                                </svg>
                            </a>

                            {{-- WhatsApp --}}
                            <a href="https://api.whatsapp.com/send?text={{ urlencode(Request::fullUrl()) }}"
                                onclick="openSharePopup(this.href, 'WhatsApp'); return false;"
                                class="share-btn whatsapp"
                                data-tooltip="Bagikan ke WhatsApp">
                                <svg viewBox="0 0 24 24">
                                    <path d="M20.52 3.48A11.86 11.86 0 0 0 12.06 0C5.5 0 .16 5.34.16 11.9c0 2.1.55 4.15 1.6 5.96L0 24l6.32-1.65a11.84 11.84 0 0 0 5.74 1.46h.01c6.56 0 11.9-5.34 11.9-11.9 0-3.18-1.24-6.17-3.45-8.43z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Container -->
        <div class="col-md-3">
            <!-- Jenis Dokumen -->
            <div class="card-status">
                <div class="header text-uppercase">Tipe Dokumen</div>
                <hr style="margin-top: 0.5rem; margin-bottom: 0.5rem; height: 2px;">
                <div class="content text-uppercase">{{ $dokumen->tipeDok }}</div>
            </div>

            @if(!$dokumen->is_monografi && !$dokumen->is_artikel)
            <!-- Singkatan -->
            <div class="card-status">
                <div class="header text-uppercase">Singkatan</div>
                <hr style="margin-top: 0.5rem; margin-bottom: 0.5rem; height: 2px;">
                <div class="content text-uppercase">{{ $dokumen->jenis_dokumen_singkatan }}</div>
            </div>
            <!-- Status -->
            <div class="card-status">
                <div class="header text-uppercase">Status</div>
                <hr style="margin-top: 0.5rem; margin-bottom: 0.5rem; height: 2px;">
                <div class="{{ $dokumen->status === 'TIDAK BERLAKU' ? 'content-negatif' : 'content' }}">
                    {{ $dokumen->status }}
                </div>
            </div>

            {{-- ================= DOWNLOAD SECTION ================= --}}
            @if($dokumen->download_allowed)

            <div>
                <div class="file-info flex-grow-1">

                    @if($dokumen->file_tersedia)
                    <a href="{{ route('dokumen.unduhLampiran', $dokumen->slug) }}"
                        target="_self"
                        class="text-decoration-none">

                        <button class="btnDL w-100 d-flex justify-content-between align-items-center" type="button">

                            {{-- ICON --}}
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="20" viewBox="0 0 640 512">
                                <path d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128H144zm79-167l80 80c9.4 9.4 24.6 9.4 33.9 0l80-80c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-39 39V184c0-13.3-10.7-24-24-24s-24 10.7-24 24V318.1l-39-39c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9z" fill="white" />
                            </svg>

                            {{-- TEXT --}}
                            <span>DOWNLOAD</span>

                            {{-- SIZE --}}
                            <span class="fw-light small">
                                @if($dokumen->lampiran_size['kb'] < 1000)
                                    ({{ number_format($dokumen->lampiran_size['kb']) }}) KB
                                    @else
                                    {{ number_format($dokumen->lampiran_size['mb'], 2) }} MB
                                    @endif
                                    </span>

                        </button>
                    </a>
                    @else
                    {{-- Jika file tidak tersedia --}}
                    <div class="btnDLdisabled w-100 d-flex justify-content-between align-items-center" type="button">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff0000">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g clip-path="url(#clip0_429_11057)">
                                    <path d="M5.63604 5.63598C4.00736 7.26466 3 9.51466 3 11.9999C3 16.9705 7.02944 20.9999 12 20.9999C14.4853 20.9999 16.7353 19.9926 18.364 18.3639M5.63604 5.63598C7.26472 4.0073 9.51472 2.99994 12 2.99994C16.9706 2.99994 21 7.02938 21 11.9999C21 14.4852 19.9926 16.7352 18.364 18.3639M5.63604 5.63598L18.364 18.3639" stroke="#ff0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_429_11057">
                                        <rect width="24" height="24" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </g>
                        </svg>
                        <span>Dokumen tidak tersedia</span>
                    </div>
                    @endif

                </div>
            </div>

            @endif



            {{-- ================= MONOGRAFI COVER ================= --}}
            @if($dokumen->is_monografi)

            <div class="file-card">
                <div class="header">COVER BUKU</div>
                <hr>

                <div class="d-flex justify-content-center align-items-center">
                    <img src="{{ $dokumen->cover_image_url }}" alt="Cover Buku" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>

@endsection

<div id="pdfOverlay" class="pdf-overlay">
    <div class="pdf-container">
        <button class="pdf-close" onclick="closePdf()">
            <i class="fas fa-times"></i>
        </button>

        <iframe id="pdfFrame"></iframe>
    </div>
</div>

<!-- Script untuk PDF Viewer dengan PDF.js -->
<script>
    function openFile(fileUrl, extension) {

        const overlay = document.getElementById('pdfOverlay');
        const container = document.querySelector('.pdf-container');

        container.innerHTML = `
        <button class="pdf-close" onclick="closeFile()">
            <i class="fas fa-times"></i>
        </button>
    `;

        if (extension === 'pdf') {

            const iframe = document.createElement('iframe');
            iframe.src = "/pdfjs/web/viewer.html?file=" + encodeURIComponent(fileUrl);
            container.appendChild(iframe);

        } else if (['jpg', 'jpeg', 'png', 'webp'].includes(extension)) {

            const img = document.createElement('img');
            img.src = fileUrl;
            img.style.width = "100%";
            img.style.height = "100%";
            img.style.objectFit = "contain";
            img.style.background = "#000";
            container.appendChild(img);

        } else {
            container.innerHTML += "<p style='color:white;text-align:center'>File tidak dapat ditampilkan</p>";
        }

        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeFile() {
        const overlay = document.getElementById('pdfOverlay');
        const container = document.querySelector('.pdf-container');

        overlay.classList.remove('active');
        container.innerHTML = "";
        document.body.style.overflow = 'auto';
    }
</script>