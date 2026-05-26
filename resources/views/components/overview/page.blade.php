@extends('layout.app-clear')

@push('styles')
<style>
    :root {
        --blue: #1a56db;
        --blue-light: #eff6ff;
        --gold: #f59e0b;
        --gold-light: #fffbeb;
        --dark: #111827;
        --muted: #6b7280;
        --border: #e5e7eb;
        --bg: #f9fafb;
    }

    /* ── Layout ── */
    .profil-wrap {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 2rem;
        align-items: start;
    }

    @media (max-width: 991px) {
        .profil-wrap { grid-template-columns: 1fr; }
        .profil-sidebar { position: static !important; }
    }

    /* ── Sidebar ── */
    .profil-sidebar {
        position: sticky;
        top: 5.5rem;
    }

    .sidebar-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }

    .sidebar-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--muted);
        padding: 1.25rem 1.25rem 0.5rem;
    }

    .sidebar-nav { padding: 0.5rem; }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 1rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--muted);
        text-decoration: none;
        transition: background 0.2s, color 0.2s, transform 0.2s;
    }

    .sidebar-nav a .nav-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
        transition: background 0.2s, color 0.2s;
    }

    .sidebar-nav a:hover {
        background: var(--bg);
        color: var(--dark);
        transform: translateX(3px);
    }

    .sidebar-nav a.active {
        background: var(--blue-light);
        color: var(--blue);
        font-weight: 600;
    }

    .sidebar-nav a.active .nav-icon {
        background: var(--blue);
        color: #fff;
    }

    .help-card {
        margin: 0.75rem;
        padding: 1.25rem;
        background: linear-gradient(135deg, var(--blue) 0%, #1e40af 100%);
        border-radius: 12px;
        color: #fff;
    }

    .help-card h6 { font-size: 0.9rem; font-weight: 700; margin-bottom: 0.35rem; }
    .help-card p  { font-size: 0.78rem; opacity: 0.8; margin: 0; line-height: 1.5; }

    /* ── Content Card ── */
    .content-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        min-height: 600px;
    }

    .content-top-bar {
        padding: 2rem 2.5rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .content-top-bar h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 0.35rem;
        letter-spacing: -0.3px;
    }

    .content-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: var(--muted);
    }

    .content-meta .dot { width: 4px; height: 4px; border-radius: 50%; background: var(--border); }

    .page-badge {
        padding: 0.35rem 0.85rem;
        background: var(--blue-light);
        color: var(--blue);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .content-body-wrap {
        padding: 2rem 2.5rem;
    }

    /* ── Prose ── */
    .prose {
        color: #374151;
        font-size: 1rem;
        line-height: 1.85;
    }

    .prose p { margin-bottom: 1.25rem; }
    .prose h1, .prose h2, .prose h3 { color: var(--dark); font-weight: 700; margin: 1.5rem 0 0.75rem; }
    .prose ul, .prose ol { padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .prose li { margin-bottom: 0.4rem; }
    .prose img { border-radius: 12px; max-width: 100%; }
    .prose a { color: var(--blue); text-decoration: underline; text-underline-offset: 3px; }

    /* ── Image Block ── */
    .content-image-block {
        margin: 0 2.5rem 2.5rem;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .content-image-block img {
        width: 100%;
        max-height: 480px;
        object-fit: cover;
        display: block;
    }

    .content-image-block figcaption {
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
        color: var(--muted);
        background: var(--bg);
        text-align: center;
        font-style: italic;
    }

    /* ── FAQ ── */
    .faq-list { display: flex; flex-direction: column; gap: 0.75rem; }

    .faq-item {
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .faq-item:hover { border-color: #93c5fd; box-shadow: 0 4px 12px rgba(26,86,219,0.06); }

    .faq-item.open { border-color: var(--blue); }

    .faq-btn {
        width: 100%;
        background: none;
        border: none;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        text-align: left;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--dark);
        transition: background 0.2s;
    }

    .faq-btn:hover { background: var(--bg); }
    .faq-item.open .faq-btn { background: var(--blue-light); color: var(--blue); }

    .faq-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.75rem;
        transition: transform 0.3s, background 0.2s, color 0.2s;
    }

    .faq-item.open .faq-icon {
        transform: rotate(45deg);
        background: var(--blue);
        color: #fff;
    }

    .faq-answer {
        display: none;
        padding: 0 1.25rem 1.25rem;
        font-size: 0.9rem;
        color: #4b5563;
        line-height: 1.8;
        border-top: 1px solid var(--border);
    }

    .faq-item.open .faq-answer { display: block; }

    /* ── Animations ── */
    .fade-up {
        opacity: 0;
        transform: translateY(18px);
        animation: fadeUp 0.5s ease forwards;
    }

    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 575px) {
        .content-top-bar, .content-body-wrap { padding: 1.25rem; }
        .content-image-block { margin: 0 1.25rem 1.25rem; }
        .content-top-bar h2 { font-size: 1.2rem; }
    }
</style>
@endpush

@section('content')
<div class="container py-5 fade-up">
    <div class="profil-wrap">

        {{-- ── SIDEBAR ── --}}
        <aside class="profil-sidebar">
            <div class="sidebar-card">
                <p class="sidebar-label">Menu Profil</p>
                <nav class="sidebar-nav">
                    @foreach ($menus as $menu)
                    <a href="/profil/{{ $menu['slug'] }}" class="{{ request()->is('profil/'.$menu['slug']) ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-layers"></i></span>
                        {{ $menu['title'] }}
                    </a>
                    @endforeach
                </nav>

                <div class="help-card">
                    <h6><i class="bi bi-headset me-1"></i> Butuh Bantuan?</h6>
                    <p>Hubungi kami melalui pusat layanan informasi terpadu BNPB.</p>
                </div>
            </div>
        </aside>

        {{-- ── CONTENT ── --}}
        <main>
            <div class="content-card">

                @if ($slug === 'faq')
                {{-- ── FAQ ── --}}

                <div class="content-body-wrap">
                    <div class="faq-list">
                        @foreach ($faqs as $i => $faq)
                        <div class="faq-item" id="faq-{{ $faq->id }}">
                            <button class="faq-btn" type="button" data-faq-id="{{ $faq->id }}">
                                <span>{{ $faq->question }}</span>
                                <span class="faq-icon"><i class="bi bi-plus"></i></span>
                            </button>
                            <div class="faq-answer prose">
                                {!! $faq->answer !!}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                @else
                {{-- ── PAGE ── --}}

                <div class="content-body-wrap">
                    <div class="prose">
                        {!! $page->content !!}
                    </div>
                </div>

                @if ($page->image)
                <figure class="content-image-block">
                    <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title }}">
                    @if($page->image_caption)
                    <figcaption>{{ $page->image_caption }}</figcaption>
                    @endif
                </figure>
                @endif

                @endif
            </div>
        </main>

    </div>
</div>

<script>
    function toggleFaq(id) {
        const item = document.getElementById('faq-' + id);
        if (!item) return;

        const isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));
        if (!isOpen) item.classList.add('open');
    }

    document.querySelectorAll('[data-faq-id]').forEach(button => {
        button.addEventListener('click', () => toggleFaq(button.dataset.faqId));
    });
</script>
@endsection
