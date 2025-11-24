@extends('layout.app-clear')

@section('content')

<style>
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
        /* kuning highlight */
        padding-left: 11px;
        font-weight: 600;
        color: #0d6efd;
        background: #fff7e1;
    }
</style>

<div class="container py-5">

    <div class="row g-4">

        {{-- SIDEBAR --}}
        <div class="col-md-3">
            <div class="p-4 bg-white rounded-4 shadow-sm sidebar-menu">

                <a href="/profil/tentang-kami"
                    class="{{ request()->is('profil/tentang-kami') ? 'active' : '' }}">
                    Tentang Kami
                </a>

                <a href="/profil/visi-misi"
                    class="{{ request()->is('profil/visi-misi') ? 'active' : '' }}">
                    Visi dan Misi
                </a>

                <a href="/profil/struktur-organisasi"
                    class="{{ request()->is('profil/struktur-organisasi') ? 'active' : '' }}">
                    Struktur Organisasi
                </a>

                <!-- <a href="/profil/prasyarat"
                    class="{{ request()->is('profil/prasyarat') ? 'active' : '' }}">
                    Prasyarat
                </a> -->

                <!-- <a href="/profil/faq"
                    class="{{ request()->is('profil/faq') ? 'active' : '' }}">
                    FAQ
                </a> -->

            </div>
        </div>


        {{-- CONTENT --}}
        <div class="col-md-9">

            <div class="p-5 bg-white rounded-4 shadow-sm" style="min-height: 600px;">

                {{-- CONTENT --}}
                <div class="mt-3" style="font-size: 1.05rem; line-height: 1.75;">
                    {!! $page->content !!}
                </div>

                {{-- Struktur Organisasi Image --}}
                @if ($page->image)
                <div class="mt-4">
                    <img src="{{ asset('storage/' . $page->image) }}"
                        class="img-fluid rounded-3 shadow-sm"
                        alt="Struktur Organisasi">
                </div>
                @endif

            </div>

        </div>

    </div>

</div>

@endsection