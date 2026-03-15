@extends('layout.app-clear')

@section('content')

<style>
    :root {
        --primary-blue: #0d6efd;
        --accent-gold: #f0ad00;
        --soft-bg: #f8f9fa;
        --text-dark: #2d3436;
        --text-muted: #636e72;
    }

    /* Sidebar Styling */
    .sidebar-wrapper {
        position: sticky;
        top: 2rem;
    }

    .sidebar-menu {
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .sidebar-menu a {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        padding: 12px 20px;
        margin-bottom: 5px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar-menu a i {
        margin-right: 12px;
        font-size: 1.1rem;
    }

    .sidebar-menu a:hover {
        background: var(--soft-bg);
        color: var(--primary-blue);
        transform: translateX(5px);
    }

    .sidebar-menu .active {
        background: linear-gradient(135deg, #fff7e1 0%, #fff 100%);
        color: var(--primary-blue) !important;
        font-weight: 700;
        box-shadow: inset 4px 0 0 var(--accent-gold);
    }

    /* Content Area */
    .content-card {
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.3s ease;
    }

    .content-header {
        border-bottom: 2px solid var(--soft-bg);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
    }

    .page-title {
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }

    /* FAQ Customization */
    .faq-item {
        border: 1px solid #edf2f7 !important;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        border-color: var(--primary-blue) !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .accordion-button:not(.collapsed) {
        background-color: transparent;
        color: var(--primary-blue);
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(13, 110, 253, 0.25);
    }

    /* Typography */
    .content-body {
        color: var(--text-dark);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .content-body p {
        margin-bottom: 1.5rem;
    }

    /* Skeleton Loading Effect (Optional) */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container py-5 fade-in-up">

    <div class="row g-4">

        {{-- SIDEBAR --}}
        <div class="col-lg-3 col-md-4">
            <div class="sidebar-wrapper">
                <div class="p-3 bg-white rounded-4 shadow-sm sidebar-menu">
                    <p class="text-uppercase small fw-bold text-muted px-3 mb-3" style="letter-spacing: 1px;">Menu Profil</p>

                    <a href="/profil/tentang-kami" class="{{ request()->is('profil/tentang-kami') ? 'active' : '' }}">
                        Tentang Kami
                    </a>

                    <a href="/profil/visi-misi" class="{{ request()->is('profil/visi-misi') ? 'active' : '' }}">
                        Visi dan Misi
                    </a>

                    <a href="/profil/struktur-organisasi" class="{{ request()->is('profil/struktur-organisasi') ? 'active' : '' }}">
                        Struktur Organisasi
                    </a>

                    <a href="/profil/prasyarat" class="{{ request()->is('profil/prasyarat') ? 'active' : '' }}">
                        Prasyarat
                    </a>

                    <a href="/profil/faq" class="{{ request()->is('profil/faq') ? 'active' : '' }}">
                        FAQ
                    </a>
                </div>

                {{-- INFO BOX (Optional) --}}
                <div class="mt-4 p-4 bg-primary rounded-4 text-white shadow-sm position-relative overflow-hidden">
                    <h6 class="fw-bold mb-2">Butuh Bantuan?</h6>
                    <p class="small mb-0 opacity-75">Hubungi kami melalui pusat layanan informasi terpadu.</p>
                </div>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="col-lg-9 col-md-8">
            <div class="content-card p-4 p-md-5 bg-white rounded-4 shadow-sm" style="min-height: 650px;">

                @if ($slug === 'faq')
                <div class="content-header">
                    <h3 class="fw-bold page-title">FAQ</h3>
                    <p class="text-muted">Temukan jawaban atas pertanyaan yang paling sering diajukan mengenai layanan kami.</p>
                </div>

                <div class="accordion" id="faqAccordion">
                    @foreach ($faqs as $faq)
                    <div class="accordion-item faq-item mb-3 rounded-3 overflow-hidden">
                        <h2 class="accordion-header" id="heading{{ $faq->id }}">
                            <button class="accordion-button collapsed fw-bold py-3 px-4"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $faq->id }}"
                                aria-expanded="false">
                                {{ $faq->question }}
                            </button>
                        </h2>

                        <div id="collapse{{ $faq->id }}"
                            class="accordion-collapse collapse"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body px-4 pb-4 pt-1 text-muted" style="line-height: 1.8;">
                                {!! $faq->answer !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @else
                <div class="content-header">
                    <h3 class="fw-bold page-title">{{ $page->title }}</h3>
                    <div class="d-flex align-items-center mt-2 text-muted small">
                        <span class="me-3"><i class="bi bi-clock me-1"></i> Terakhir diperbarui: {{ $page->updated_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="content-body mt-3" style="font-size: 1.08rem; line-height: 1.9;">
                    {!! $page->content !!}
                </div>

                @if ($page->image)
                <div class="mt-5 text-center">
                    <img src="{{ asset('storage/' . $page->image) }}"
                        class="img-fluid rounded-4 shadow-lg"
                        style="max-height: 500px; object-fit: cover;"
                        alt="{{ $page->title }}">
                    @if($page->image_caption)
                    <p class="small text-muted mt-2 italic">{{ $page->image_caption }}</p>
                    @endif
                </div>
                @endif
                @endif

            </div>
        </div>

    </div>
</div>

@endsection