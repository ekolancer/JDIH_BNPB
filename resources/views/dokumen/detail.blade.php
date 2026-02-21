@extends('layout.app-detail')

@section('content')

<style>
    .social-share {
        position: fixed;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        z-index: 1000;
    }

    .social-share .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-bottom: 10px;
    }

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

    /* ======================================================= */
    /* From Uiverse.io by stlyash */
    .redes {
        position: fixed;
        top: 50%;
        left: 0;
        width: 80px;
        height: auto;
        display: flex;
        flex-direction: column;
        z-index: 1000;
    }

    .redes a {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 40px;
        transition: all 0.2s ease-in-out;
    }

    .redes a svg {
        width: 30px;
        fill: #ffffff;
    }

    .redes .whatsapp {
        background-color: #25D366;
        border-radius: 0 0 10px 0;
    }

    .redes .instagram {
        background: linear-gradient(45deg, #833ab4, #fd1d1d, #f56040);
        border-radius: 0 10px 0 0;
    }

    .redes .telegram {
        background-color: #0088cc;
    }

    .redes .twitter {
        background-color: #000000;
    }

    .redes .facebook {
        background-color: #006aff;
    }

    .redes a:hover {
        width: 130%;
        border-radius: 0 10px 10px 0;
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
                            <i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($dokumen->created_at)->locale('id')->translatedFormat('d F Y') }}
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
                        @if($dokumen->tipeDok !== 'Artikel')
                        <span class="ms-auto bg-primary text-white px-3 py-2 d-inline-block">
                            <a href="javascript:void(0)"
                                onclick="openPdf('{{ asset('storage/'.$dokumen->lampiran) }}')"
                                class="text-white text-decoration-none">
                                <i class="fas fa-book-open-reader me-1"></i> {{ $buttonText }}
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

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content">
                    <!-- Tombol Kembali -->
                    <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Kembali</a>

                    <!-- Menampilkan Komponen Livewire -->
                    @livewire('media-file', ['dokumenId' => $dokumen->id])

                </div>

                <!-- Tombol Social Share Upgrade -->
                <div class="redes">
                    <a class="instagram" href="https://www.instagram.com/create/story/?url={{ urlencode(Request::fullUrl()) }}" onclick="openSharePopup(this.href, 'Instagram'); return false;">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="#ffffff"
                            viewBox="0,0,550,550"
                            width="25px"
                            height="25px"
                            fill-rule="nonzero">
                            <path
                                d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                        </svg>
                    </a>

                    <a class="twitter" href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}" onclick="openSharePopup(this.href, 'Twitter'); return false;">
                        <svg
                            fill="white"
                            class="svgIcon"
                            xmlns="http://www.w3.org/2000/svg"
                            width="25px"
                            height="25px"
                            viewBox="0 0 564 564">
                            <path
                                d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"></path>
                        </svg>
                    </a>

                    <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" onclick="openSharePopup(this.href, 'Facebook'); return false;">
                        <svg
                            class="icon icons8-Facebook-Filled"
                            viewBox="0 0 50 50"
                            height="25px"
                            width="25px"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M40,0H10C4.486,0,0,4.486,0,10v30c0,5.514,4.486,10,10,10h30c5.514,0,10-4.486,10-10V10C50,4.486,45.514,0,40,0z M39,17h-3 c-2.145,0-3,0.504-3,2v3h6l-1,6h-5v20h-7V28h-3v-6h3v-3c0-4.677,1.581-8,7-8c2.902,0,6,1,6,1V17z"></path>
                        </svg>
                    </a>

                    <a class="telegram" href="https://t.me/share/url?url={{ urlencode(Request::fullUrl()) }}" onclick="openSharePopup(this.href, 'Telegram'); return false;">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="25"
                            height="25"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="telegram">
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.414 7.343l-1.82 8.575c-.138.62-.504.77-1.022.479l-2.835-2.09-1.364 1.315c-.15.148-.278.273-.571.273l.206-2.977 5.416-4.901c.236-.21-.051-.326-.366-.118l-6.693 4.226-2.892-.905c-.627-.196-.637-.627.131-.923l11.261-4.35c.522-.188.975.126.807.92z" />
                        </svg>
                    </a>

                    <a class="whatsapp" href="https://api.whatsapp.com/send?text={{ urlencode(Request::fullUrl()) }}" onclick="openSharePopup(this.href, 'WhatsApp'); return false;">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="25"
                            height="25"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="whatsapp">
                            <path d="M12.02 2C6.48 2 2 6.48 2 12.02c0 2.046.55 3.996 1.594 5.704L2 22l4.376-1.552A10.03 10.03 0 0 0 12.02 22C17.564 22 22 17.524 22 12.02S17.564 2 12.02 2zm0 18c-1.766 0-3.49-.495-4.964-1.428l-.355-.225-2.59.917.893-2.538-.27-.412A7.988 7.988 0 0 1 4 12.02C4 7.598 7.598 4 12.02 4S20 7.598 20 12.02C20 16.424 16.414 20 12.02 20zm5.47-6.628l-.003-.002c-.098-.049-1.377-.677-1.591-.755-.213-.078-.368-.098-.523.098-.155.196-.597.755-.732.91-.135.156-.27.176-.493.049a6.512 6.512 0 0 1-1.914-1.18 7.048 7.048 0 0 1-1.31-1.597c-.135-.235-.014-.362.103-.49.106-.117.237-.306.354-.459a1.692 1.692 0 0 0 .213-.402c.067-.137.034-.255-.017-.353-.049-.098-.523-1.255-.718-1.722-.189-.455-.383-.394-.523-.402a1.394 1.394 0 0 0-.437.049c-.144.05-.524.205-.716.352-.196.156-.747.732-.747 1.785 0 1.053.764 2.067.871 2.214.106.147 1.503 2.294 3.644 3.216.51.22.907.353 1.22.453.512.162.978.14 1.344.085.41-.062 1.377-.563 1.571-1.109.195-.546.195-1.012.137-1.109-.058-.097-.21-.156-.44-.274z" />
                        </svg>
                    </a>
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

            @if($dokumen->tipeDok !== 'Monografi' && $dokumen->tipeDok !== 'Artikel')
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

            @php
            $bolehDownload = $dokumen->status !== 'TIDAK BERLAKU';
            @endphp

            @if($bolehDownload)
            <div>
                <div class="file-info flex-grow-1">
                    <a href="{{ route('dokumen.unduhLampiran', $dokumen->slug) }}" target="_self" style="text-decoration: none">
                        <button class="btnDL w-100 d-flex justify-content-between align-items-center" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="20" viewBox="0 0 640 512">
                                <path d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128H144zm79-167l80 80c9.4 9.4 24.6 9.4 33.9 0l80-80c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-39 39V184c0-13.3-10.7-24-24-24s-24 10.7-24 24V318.1l-39-39c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9z" fill="white"></path>
                            </svg>
                            <span>DOWNLOAD</span>
                            <span class="fw-light small">
                                @if($lampiranSizeInKb < 1000)
                                    ({{ number_format($lampiranSizeInKb) }}) KB
                                    @else
                                    {{ number_format($lampiranSizeInMb, 2) }} MB
                                    @endif
                                    </span>
                        </button>
                    </a>
                </div>
            </div>
            @endif
            @endif

            @if($dokumen->tipeDok === 'Monografi')
            <div class="file-card">
                <div class="header">COVER BUKU</div>
                <hr>
                <div class="d-flex justify-content-center align-items-center">
                    @php
                    $fileExists = $dokumen->lampiran && file_exists(public_path('storage/' . $dokumen->lampiran));
                    $imagePath = $fileExists ? Storage::url($dokumen->lampiran) : asset('img/noimage.png');
                    @endphp
                    <img src="{{ $imagePath }}" alt="Cover Buku" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                </div>
            </div>
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
    function openPdf(fileUrl) {
        const viewerUrl = "/pdfjs/web/viewer.html?file=" + encodeURIComponent(fileUrl);

        const overlay = document.getElementById('pdfOverlay');
        const frame = document.getElementById('pdfFrame');

        frame.src = viewerUrl;

        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closePdf() {
        const overlay = document.getElementById('pdfOverlay');
        const frame = document.getElementById('pdfFrame');

        overlay.classList.remove('active');
        frame.src = '';

        document.body.style.overflow = 'auto';
    }
</script>