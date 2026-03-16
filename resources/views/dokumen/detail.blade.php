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
        <div class="col-md-8 mb-5">
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
                                <a href="{{ route('list-doc', [
                                        'subjek' => $subjek->id
                                ]) }}"
                                    class="modern-badge me-1 text-decoration-none text-capitalize">
                                    <i class="fas fa-tag"></i>
                                    {{ \Illuminate\Support\Str::title($subjek->nama) }}
                                </a>
                                @empty
                                —
                                @endforelse

                                {{-- BIDANG HUKUM (ref + link filter) --}}
                                @elseif($label === 'Bidang Hukum' && $value instanceof \Illuminate\Database\Eloquent\Model)
                                <a href="{{ route('list-doc', [
                                        'bidang' => $value->id
                                ]) }}"
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
                                class="share-btn"
                                data-tooltip="Bagikan ke X">
                                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <circle cx="24" cy="24" r="20" fill="#1DA1F2"></circle>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M36 16.3086C35.1177 16.7006 34.1681 16.9646 33.1722 17.0838C34.1889 16.4742 34.9697 15.5095 35.3368 14.36C34.3865 14.9247 33.3314 15.3335 32.2107 15.5551C31.3123 14.5984 30.0316 14 28.6165 14C25.8975 14 23.6928 16.2047 23.6928 18.9237C23.6928 19.3092 23.7368 19.6852 23.8208 20.046C19.7283 19.8412 16.1005 17.8805 13.6719 14.9015C13.2479 15.6287 13.0055 16.4742 13.0055 17.3766C13.0055 19.0845 13.8735 20.5916 15.1958 21.4747C14.3878 21.4491 13.6295 21.2275 12.9647 20.8587V20.9203C12.9647 23.3066 14.663 25.296 16.9141 25.7496C16.5013 25.8616 16.0661 25.9224 15.6174 25.9224C15.2998 25.9224 14.991 25.8912 14.6902 25.8336C15.3166 27.7895 17.1357 29.2134 19.2899 29.2534C17.6052 30.5733 15.4822 31.3612 13.1751 31.3612C12.7767 31.3612 12.3848 31.338 12 31.2916C14.1791 32.6884 16.7669 33.5043 19.5475 33.5043C28.6037 33.5043 33.5562 26.0016 33.5562 19.4956C33.5562 19.282 33.5522 19.0693 33.5418 18.8589C34.5049 18.1629 35.34 17.2958 36 16.3086Z" fill="white"></path>
                                    </g>
                                </svg>
                            </a>

                            {{-- Facebook --}}
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}"
                                onclick="openSharePopup(this.href, 'Facebook'); return false;"
                                class="share-btn"
                                data-tooltip="Bagikan ke">
                                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <circle cx="16" cy="16" r="14" fill="url(#paint0_linear_87_7208)"></circle>
                                        <path d="M21.2137 20.2816L21.8356 16.3301H17.9452V13.767C17.9452 12.6857 18.4877 11.6311 20.2302 11.6311H22V8.26699C22 8.26699 20.3945 8 18.8603 8C15.6548 8 13.5617 9.89294 13.5617 13.3184V16.3301H10V20.2816H13.5617V29.8345C14.2767 29.944 15.0082 30 15.7534 30C16.4986 30 17.2302 29.944 17.9452 29.8345V20.2816H21.2137Z" fill="white"></path>
                                        <defs>
                                            <linearGradient id="paint0_linear_87_7208" x1="16" y1="2" x2="16" y2="29.917" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#18ACFE"></stop>
                                                <stop offset="1" stop-color="#0163E0"></stop>
                                            </linearGradient>
                                        </defs>
                                    </g>
                                </svg>
                            </a>

                            {{-- Telegram --}}
                            <a href="https://t.me/share/url?url={{ urlencode(Request::fullUrl()) }}"
                                onclick="openSharePopup(this.href, 'Telegram'); return false;"
                                class="share-btn"
                                data-tooltip="Bagikan ke Telegram">
                                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <circle cx="16" cy="16" r="14" fill="url(#paint0_linear_87_7225)"></circle>
                                        <path d="M22.9866 10.2088C23.1112 9.40332 22.3454 8.76755 21.6292 9.082L7.36482 15.3448C6.85123 15.5703 6.8888 16.3483 7.42147 16.5179L10.3631 17.4547C10.9246 17.6335 11.5325 17.541 12.0228 17.2023L18.655 12.6203C18.855 12.4821 19.073 12.7665 18.9021 12.9426L14.1281 17.8646C13.665 18.3421 13.7569 19.1512 14.314 19.5005L19.659 22.8523C20.2585 23.2282 21.0297 22.8506 21.1418 22.1261L22.9866 10.2088Z" fill="white"></path>
                                        <defs>
                                            <linearGradient id="paint0_linear_87_7225" x1="16" y1="2" x2="16" y2="30" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#37BBFE"></stop>
                                                <stop offset="1" stop-color="#007DBB"></stop>
                                            </linearGradient>
                                        </defs>
                                    </g>
                                </svg>
                            </a>

                            {{-- WhatsApp --}}
                            <a href="https://api.whatsapp.com/send?text={{ urlencode(Request::fullUrl()) }}"
                                onclick="openSharePopup(this.href, 'WhatsApp'); return false;"
                                class="share-btn"
                                data-tooltip="Bagikan ke WhatsApp">
                                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16 31C23.732 31 30 24.732 30 17C30 9.26801 23.732 3 16 3C8.26801 3 2 9.26801 2 17C2 19.5109 2.661 21.8674 3.81847 23.905L2 31L9.31486 29.3038C11.3014 30.3854 13.5789 31 16 31ZM16 28.8462C22.5425 28.8462 27.8462 23.5425 27.8462 17C27.8462 10.4576 22.5425 5.15385 16 5.15385C9.45755 5.15385 4.15385 10.4576 4.15385 17C4.15385 19.5261 4.9445 21.8675 6.29184 23.7902L5.23077 27.7692L9.27993 26.7569C11.1894 28.0746 13.5046 28.8462 16 28.8462Z" fill="#BFC8D0"></path>
                                        <path d="M28 16C28 22.6274 22.6274 28 16 28C13.4722 28 11.1269 27.2184 9.19266 25.8837L5.09091 26.9091L6.16576 22.8784C4.80092 20.9307 4 18.5589 4 16C4 9.37258 9.37258 4 16 4C22.6274 4 28 9.37258 28 16Z" fill="url(#paint0_linear_87_7264)"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16 30C23.732 30 30 23.732 30 16C30 8.26801 23.732 2 16 2C8.26801 2 2 8.26801 2 16C2 18.5109 2.661 20.8674 3.81847 22.905L2 30L9.31486 28.3038C11.3014 29.3854 13.5789 30 16 30ZM16 27.8462C22.5425 27.8462 27.8462 22.5425 27.8462 16C27.8462 9.45755 22.5425 4.15385 16 4.15385C9.45755 4.15385 4.15385 9.45755 4.15385 16C4.15385 18.5261 4.9445 20.8675 6.29184 22.7902L5.23077 26.7692L9.27993 25.7569C11.1894 27.0746 13.5046 27.8462 16 27.8462Z" fill="white"></path>
                                        <path d="M12.5 9.49989C12.1672 8.83131 11.6565 8.8905 11.1407 8.8905C10.2188 8.8905 8.78125 9.99478 8.78125 12.05C8.78125 13.7343 9.52345 15.578 12.0244 18.3361C14.438 20.9979 17.6094 22.3748 20.2422 22.3279C22.875 22.2811 23.4167 20.0154 23.4167 19.2503C23.4167 18.9112 23.2062 18.742 23.0613 18.696C22.1641 18.2654 20.5093 17.4631 20.1328 17.3124C19.7563 17.1617 19.5597 17.3656 19.4375 17.4765C19.0961 17.8018 18.4193 18.7608 18.1875 18.9765C17.9558 19.1922 17.6103 19.083 17.4665 19.0015C16.9374 18.7892 15.5029 18.1511 14.3595 17.0426C12.9453 15.6718 12.8623 15.2001 12.5959 14.7803C12.3828 14.4444 12.5392 14.2384 12.6172 14.1483C12.9219 13.7968 13.3426 13.254 13.5313 12.9843C13.7199 12.7145 13.5702 12.305 13.4803 12.05C13.0938 10.953 12.7663 10.0347 12.5 9.49989Z" fill="white"></path>
                                        <defs>
                                            <linearGradient id="paint0_linear_87_7264" x1="26.5" y1="7" x2="4" y2="28" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#5BD066"></stop>
                                                <stop offset="1" stop-color="#27B43E"></stop>
                                            </linearGradient>
                                        </defs>
                                    </g>
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

                        <button class="btnDL w-100 d-flex justify-content-center align-items-center">

                            {{-- ICON --}}
                            <svg height="200px" width="200px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" xml:space="preserve" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <style type="text/css">
                                        .st0 {
                                            fill: #ffffff;
                                        }

                                        .st1 {
                                            opacity: 0.2;
                                        }

                                        .st2 {
                                            fill: #231F20;
                                        }

                                        .st3 {
                                            fill: #2144cc;
                                        }

                                        .st4 {
                                            fill: #8C81D4;
                                        }
                                    </style>
                                    <g id="Layer_1">
                                        <g>
                                            <circle class="st0" cx="32" cy="32" r="32"></circle>
                                        </g>
                                        <g class="st1">
                                            <g>
                                                <path class="st2" d="M40,11.2C40,11.2,40,11.1,40,11.2C40,11,40,11,40,11.2z"></path>
                                            </g>
                                            <g>
                                                <path class="st2" d="M24,11.1C24,11.1,24,11.2,24,11.1C24,11,24,11,24,11.1z"></path>
                                            </g>
                                        </g>
                                        <g>
                                            <g class="st1">
                                                <path class="st2" d="M48,32c0-6.5-3.8-12-9.4-14.5C36.9,16.7,36,18,36,19.1v8.5c0,0.8,0.7,1.4,1.5,1.4h3.1c1.5,0,1.9,1.1,0.9,2.4 l-7.7,9.8c-1,1.2-2.6,1.2-3.5,0l-7.7-9.8c-1-1.2-0.6-2.4,0.9-2.4h3.1c0.8,0,1.5-0.6,1.5-1.4v-8.5c0-1.1-1.5-2.1-2.8-1.6 c0,0,0,0,0,0c-4.3,2-7.6,5.9-8.7,10.6C11.7,28.9,8,33,8,38c0,5.5,4.5,10,10,10h30c4.4,0,8-3.6,8-8S52.4,32,48,32z"></path>
                                            </g>
                                            <g>
                                                <path class="st3" d="M48,30c0-6.5-3.8-12-9.4-14.5C36.9,14.7,36,16,36,17.1v8.5c0,0.8,0.7,1.4,1.5,1.4h3.1c1.5,0,1.9,1.1,0.9,2.4 l-7.7,9.8c-1,1.2-2.6,1.2-3.5,0l-7.7-9.8c-1-1.2-0.6-2.4,0.9-2.4h3.1c0.8,0,1.5-0.6,1.5-1.4v-8.5c0-1.1-1.5-2.1-2.8-1.6 c0,0,0,0,0,0c-4.3,2-7.6,5.9-8.7,10.6C11.7,26.9,8,31,8,36c0,5.5,4.5,10,10,10h30c4.4,0,8-3.6,8-8S52.4,30,48,30z"></path>
                                            </g>
                                            <g>
                                                <path class="st4" d="M40,9.2C40,9.2,40,9.1,40,9.2C40,9,40,9,40,9.2z"></path>
                                            </g>
                                            <g>
                                                <path class="st4" d="M24,9.1C24,9.1,24,9.2,24,9.1C24,9,24,9,24,9.1z"></path>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="Layer_2"> </g>
                                </g>
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
                    <div class="btnDLdisabled w-100 d-flex justify-content-center align-items-center">
                        <svg height="200px" width="200px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" xml:space="preserve" fill="#000000">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <style type="text/css">
                                    .st0 {
                                        fill: #C75C5C;
                                    }

                                    .st1 {
                                        opacity: 0.2;
                                    }

                                    .st2 {
                                        fill: #231F20;
                                    }

                                    .st3 {
                                        fill: #F5CF87;
                                    }

                                    .st4 {
                                        fill: #4F5D73;
                                    }
                                </style>
                                <g id="Layer_1">
                                    <g>
                                        <circle class="st0" cx="32" cy="32" r="32"></circle>
                                    </g>
                                    <g class="st1">
                                        <path class="st2" d="M17,50c-4.4,0-6.2-3.1-4-6.9L28,17c2.2-3.8,5.8-3.8,8,0l15,26.1c2.2,3.8,0.4,6.9-4,6.9H17z"></path>
                                    </g>
                                    <g>
                                        <path class="st3" d="M17,48c-4.4,0-6.2-3.1-4-6.9L28,15c2.2-3.8,5.8-3.8,8,0l15,26.1c2.2,3.8,0.4,6.9-4,6.9H17z"></path>
                                    </g>
                                    <g>
                                        <path class="st4" d="M34,32c0,1.1-0.9,2-2,2l0,0c-1.1,0-2-0.9-2-2v-8c0-1.1,0.9-2,2-2l0,0c1.1,0,2,0.9,2,2V32z"></path>
                                    </g>
                                    <g>
                                        <path class="st4" d="M34,40c0,1.1-0.9,2-2,2l0,0c-1.1,0-2-0.9-2-2l0,0c0-1.1,0.9-2,2-2l0,0C33.1,38,34,38.9,34,40L34,40z"></path>
                                    </g>
                                </g>
                                <g id="Layer_2"> </g>
                            </g>
                        </svg>
                        <span>Dokumen belum tersedia</span>
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