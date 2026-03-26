<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>JDIH - Badan Nasional Penanggulangan Bencana</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="JDIH, Badan Nasional Penanggulangan Bencana, BNPB, Dokumentasi Hukum, Informasi Hukum" name="keywords">
    <meta content="Jaringan Dokumentasi dan Informasi Hukum Badan Nasional Penanggulangan Bencana menyediakan informasi dan dokumentasi hukum terkait penanggulangan bencana di Indonesia." name="description">

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
    <!-- pull css dari masing-masing halaman -->
    @stack('styles')

    <!-- Livewire Stylesheet -->
    <livewire:styles />

</head>

<body>

    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
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
                    <div class="row">
                        <div class="col-12 text-start">
                            <nav aria-label="breadcrumb">
                                @include('partials.breadcrumbs')
                            </nav>
                        </div>
                    </div>
                </div>
            </div>