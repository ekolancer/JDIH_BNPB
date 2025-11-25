@extends('layout.app-clear')

@section('content')

<style>
    /* Sidebar menu */
    .sidebar-menu a {
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        display: block;
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        transition: all .2s;
    }
    .sidebar-menu a:hover {
        background: #f8f9fa;
    }
    .sidebar-menu .active {
        border-left: 4px solid #f0ad00;
        padding-left: 11px;
        font-weight: 600;
        color: #0d6efd;
        background: #fff7e1;
    }

    /* Smooth accordion animation */
    .accordion .accordion-collapse {
        transition: height 0.35s ease, opacity 0.3s ease;
    }
    .accordion-body {
        animation: fadeIn 0.4s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container py-5">

    <div class="row g-4">

        {{-- SIDEBAR --}}
        <div class="col-md-3">
            <div class="p-4 bg-white rounded-4 shadow-sm sidebar-menu">

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
        </div>


        {{-- CONTENT --}}
        <div class="col-md-9">

            <div class="p-5 bg-white rounded-4 shadow-sm" style="min-height: 600px;">

                {{-- TITLE --}}
                <h2 class="fw-bold text-primary mb-4" style="font-size: 1.5rem;">
                    {{ strtoupper($page->title) }}
                </h2>


                {{-- FAQ MODE --}}
                @if ($slug === 'faq')

                    <h4 class="fw-semibold mb-3">Pertanyaan yang Sering Diajukan</h4>

                    <div class="accordion" id="faqAccordion">

                        @foreach ($faqs as $faq)
                        <div class="accordion-item mb-3 shadow-sm rounded-3 border-0">

                            <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                <button class="accordion-button collapsed fw-semibold"
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

                                <div class="accordion-body py-3" style="line-height: 1.75;">
                                    {!! $faq->answer !!}
                                </div>

                            </div>

                        </div>
                        @endforeach

                    </div>


                {{-- NORMAL PAGE CONTENT --}}
                @else

                    <div class="mt-3" style="font-size: 1.05rem; line-height: 1.75;">
                        {!! $page->content !!}
                    </div>

                    @if ($page->image)
                    <div class="mt-4">
                        <img src="{{ asset('storage/' . $page->image) }}"
                            class="img-fluid rounded-3 shadow-sm"
                            alt="{{ $page->title }}">
                    </div>
                    @endif

                @endif

            </div>

        </div>

    </div>

</div>

@endsection
