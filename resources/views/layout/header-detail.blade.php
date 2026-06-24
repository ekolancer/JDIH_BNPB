<!DOCTYPE html>
<html lang="id">

<head>
    {{-- BASIC SEO --}}
    <title>{{ $seo['title'] }}</title>

    <meta name="description" content="{{ $seo['description'] }}">
    <link rel="canonical" href="{{ $seo['url'] }}">

    <!-- ===================== -->
    <!-- OPEN GRAPH (WA, FB, Telegram) -->
    <!-- ===================== -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:url" content="{{ $seo['url'] }}">
    <meta property="og:image" content="{{ $seo['image'] }}">
    <meta property="og:site_name" content="JDIH BNPB">

    <!-- wajib biar stabil -->
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- ===================== -->
    <!-- TWITTER (X) -->
    <!-- ===================== -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    <meta name="twitter:description" content="{{ $seo['description'] }}">
    <meta name="twitter:image" content="{{ $seo['image'] }}">

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

    <!-- pull css detail halaman masing-masing -->
    @stack('styles')

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
                            <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                <a href="{{ route('profil.show', 'tentang-kami') }}" class="dropdown-item">Tentang Kami</a>
                                <a href="{{ route('profil.show', 'visi-misi') }}" class="dropdown-item">Visi Misi</a>
                                <a href="{{ route('profil.show', 'struktur-organisasi') }}" class="dropdown-item">Struktur Organisasi</a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('profil.show', 'faq') }}" class="dropdown-item">FAQ</a>
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
