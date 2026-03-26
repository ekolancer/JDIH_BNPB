@extends('layout.app-detail')

<!-- push css halaman detail infografis -->
@push('styles')
<link href="{{ asset('css/detail-page/info.css') }}?v={{ filemtime(public_path('css/detail-page/info.css')) }}" rel="stylesheet">
@endpush

@section('content')
<div class="infographic-detail-wrapper">
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                <div class="mb-4">
                    <a href="{{ url()->previous() }}" class="btn-back-clean">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>

                <header class="info-header text-center mb-0">
                    <h1 class="info-title">
                        {{ $infografis->judul_label }}
                    </h1>

                    <div class="info-meta">
                        <span class="meta-item">
                            <i class="far fa-calendar-alt"></i>
                            {{ $infografis->tanggal_formatted }}
                        </span>
                        <span class="meta-separator d-none d-sm-inline">•</span>
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            {{ $infografis->view_count_formatted }} Dilihat
                        </span>
                    </div>
                </header>

                @php
                $shareUrl = urlencode(url()->current());
                $shareTitle = urlencode($infografis->judul_label);
                @endphp

                <div class="info-share-top d-flex justify-content-center align-items-center gap-3 mb-4">
                    <span class="share-label mb-0">Bagikan:</span>

                    <div class="d-flex gap-2">

                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                            target="_blank"
                            class="share-icon fb"
                            title="Bagikan ke Facebook"
                            data-tooltip="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>

                        <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}"
                            target="_blank"
                            class="share-icon x"
                            title="Bagikan ke X"
                            data-tooltip="X">
                            <i class="fab fa-x-twitter"></i>
                        </a>

                        <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}"
                            target="_blank"
                            class="share-icon wa"
                            title="Bagikan ke WhatsApp"
                            data-tooltip="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>

                        <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}"
                            target="_blank"
                            class="share-icon tg"
                            title="Bagikan ke Telegram"
                            data-tooltip="Telegram">
                            <i class="fab fa-telegram-plane"></i>
                        </a>

                        <!-- copy link button -->
                        <button type="button"
                            class="share-icon share-copy"
                            id="copyBtn"
                            data-tooltip="Salin link"
                            onclick="copyLinkPremium(this)">
                            <i class="fas fa-link"></i>
                        </button>

                    </div>
                </div>

                <figure class="info-image-container mb-5">
                    @if ($infografis->image_url)
                    <div class="image-wrapper shadow-xl">
                        <img
                            src="{{ $infografis->image_url }}"
                            class="img-fluid info-img"
                            alt="{{ $infografis->image_alt }}"
                            loading="lazy">
                    </div>
                    @else
                    <div class="empty-state shadow-sm">
                        <i class="fas fa-image fa-4x mb-3 text-muted opacity-50"></i>
                        <h5 class="fw-bold text-secondary">Belum Ada Infografis</h5>
                        <p class="text-muted mb-0">Gambar untuk infografis ini belum tersedia.</p>
                    </div>
                    @endif
                </figure>

            </div>
        </div>
    </div>
</div>

<script>
    function copyLinkPremium(button) {
        const url = window.location.href;
        const icon = button.querySelector('i');

        navigator.clipboard.writeText(url).then(() => {

            // Ubah icon
            icon.classList.remove('fa-link');
            icon.classList.add('fa-check');

            // Ubah tooltip
            button.setAttribute('data-tooltip', 'Disalin!');

            // Tambah class success
            button.classList.add('success');

            // Toast kecil (optional subtle)
            showToastPremium('Link berhasil disalin');

            // Reset setelah 2 detik
            setTimeout(() => {
                icon.classList.remove('fa-check');
                icon.classList.add('fa-link');
                button.setAttribute('data-tooltip', 'Salin link');
                button.classList.remove('success');
            }, 2000);

        }).catch(() => {
            showToastPremium('Gagal menyalin link');
        });
    }

    function showToastPremium(message) {
        const toast = document.createElement('div');
        toast.innerText = message;

        toast.style.position = 'fixed';
        toast.style.bottom = '30px';
        toast.style.left = '50%';
        toast.style.transform = 'translateX(-50%) translateY(10px)';
        toast.style.background = 'rgba(0,0,0,0.85)';
        toast.style.color = '#fff';
        toast.style.padding = '10px 16px';
        toast.style.borderRadius = '999px';
        toast.style.fontSize = '13px';
        toast.style.opacity = '0';
        toast.style.transition = 'all 0.3s ease';
        toast.style.zIndex = '9999';

        document.body.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(-50%) translateY(0)';
        });

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(-50%) translateY(10px)';
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }
</script>

@endsection