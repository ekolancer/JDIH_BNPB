<!DOCTYPE html>
<html lang="en">

<head>
    {{-- BASIC SEO --}}
    <title>
        {{ $dokumen->jenis_dokumen_singkatan }}
        Nomor {{ $dokumen->nomor }}
        Tahun {{ $dokumen->tahun }} - JDIH BNPB
    </title>

    <meta name="description"
        content="{{ Str::limit(strip_tags($dokumen->abstrak ?? $dokumen->judul), 160) }}">

    <link rel="canonical" href="{{ url()->current() }}">

    {{-- OPEN GRAPH --}}
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title"
        content="{{ $dokumen->jenis_dokumen_singkatan }} Nomor {{ $dokumen->nomor }} Tahun {{ $dokumen->tahun }}">
    <meta property="og:description"
        content="{{ Str::limit(strip_tags($dokumen->abstrak ?? $dokumen->judul), 160) }}">
    <!-- <meta property="og:image"
        content="{{ $dokumen->lampiran_exists ? asset('storage/'.$dokumen->lampiran) : asset('img/default-share.jpg') }}"> -->
    <meta property="og:site_name" content="JDIH BNPB">

    {{-- TWITTER CARD --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title"
        content="{{ $dokumen->jenis_dokumen_singkatan }} Nomor {{ $dokumen->nomor }} Tahun {{ $dokumen->tahun }}">
    <meta name="twitter:description"
        content="{{ Str::limit(strip_tags($dokumen->abstrak ?? $dokumen->judul), 160) }}">
    <meta name="twitter:image"
        content="{{ $dokumen->lampiran_exists ? asset('storage/'.$dokumen->lampiran) : asset('img/default-share.jpg') }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/2639b34ea1.js" crossorigin="anonymous"></script>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}" rel="stylesheet">
    <link href="{{ asset('css/detail.css') }}?v={{ filemtime(public_path('css/detail.css')) }}" rel="stylesheet">

    <!-- Livewire Stylesheet -->
    <livewire:styles />

</head>

<body>

    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
        <!-- Navbar -->
        <div class="container-fluid position-relative p-0">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom px-4 px-lg-5">
                <a href="/" class="navbar-brand">
                    <div class="logo-container d-flex">
                        <div class="logo-box">
                            <img src="{{ asset('img/jdihn.png') }}" alt="Logo JDIH" class="logo-img">
                        </div>
                        <div class="logo-divider"></div>
                        <div class="logo-box">
                            <img src="{{ asset('img/BNPB.png') }}" alt="Logo BNPB" class="logo-img">
                        </div>
                    </div>

                    <div class="brand-text ms-3 d-none d-sm-flex flex-column">
                        <span class="brand-title">Jaringan Dokumentasi dan Informasi Hukum</span>
                        <span class="brand-subtitle">Badan Nasional Penanggulangan Bencana</span>
                    </div>
                </a>

                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="/" class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a>

                        <livewire:dropdown-dokumen />

                        <div class="nav-item dropdown hover-dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Informasi Hukum</a>
                            <div class="dropdown-menu border-0 shadow-lg">
                                <a href="{{ route('berita.index') }}" class="dropdown-item">Berita</a>
                                <a href="{{ route('infografis.index') }}" class="dropdown-item">Infografis</a>
                            </div>
                        </div>

                        <div class="nav-item dropdown hover-dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Perihal</a>
                            <div class="dropdown-menu border-0 shadow-lg">
                                <a href="/profil/tentang-kami" class="dropdown-item">Tentang Kami</a>
                                <a href="/profil/visi-misi" class="dropdown-item">Visi Misi</a>
                                <a href="/profil/struktur-organisasi" class="dropdown-item">Struktur Organisasi</a>
                                <div class="dropdown-divider"></div>
                                <a href="/profil/faq" class="dropdown-item">FAQ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

            <!-- Hero Image Section -->
            <div class="hero-section hero-compact">
                <div class="hero-overlay"></div>
                <div class="container hero-content">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <nav aria-label="breadcrumb">
                                @include('partials.breadcrumbs')
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('advanced-search-toggle').addEventListener('click', function() {
                    var filters = document.querySelector('.advanced-filters');
                    if (filters.classList.contains('show')) {
                        filters.classList.remove('show');
                        setTimeout(() => {
                            filters.style.display = 'none'; // Set display none after transition
                        }, 500); // Waktu yang sama dengan durasi transisi
                    } else {
                        filters.style.display = 'block'; // Set display block before adding class
                        setTimeout(() => {
                            filters.classList.add('show');
                        }, 200); // Delay singkat untuk memicu transisi
                    }
                });

                // Remove empty inputs from form submission
                document.querySelector('.search-form').addEventListener('submit', function(e) {
                    const inputs = this.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        if (!input.value || input.value === '') {
                            input.name = ''; // Remove name attribute so it's not sent
                        }
                    });
                });
            </script>