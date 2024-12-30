<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>JDIH - Badan Nasional Penanggulangan Bencana</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/2639b34ea1.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css" rel="stylesheet') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/detail.css') }}" rel="stylesheet">

    <head>
        <style>
            .navbar-custom {
                background-color: #0052cc;
                padding: 10px 20px;
            }

            .navbar-custom .navbar-brand,
            .navbar-custom .nav-link {
                color: white;
            }

            .search-bar {
                display: flex;
                align-items: center;
            }

            .search-bar input {
                border-top-left-radius: 5px;
                border-bottom-left-radius: 5px;
                border-right: none;
            }

            .search-bar button {
                border-top-right-radius: 5px;
                border-bottom-right-radius: 5px;
                background-color: #ffcc00;
                border: none;
            }

            .search-bar button i {
                color: white;
            }

            .btn-group-custom {
                margin-top: 10px;
            }

            /* Prevent navbar from overlapping the hero section */
            .hero-section {
                padding-top: 120px;
                /* Adjust to the height of the navbar */
                background-color: #f8f9fa;
            }

            /* Styling for Breadcrumb */
            .breadcrumb {
                background-color: transparent;
                margin-top: 20px;
                /* Give space from the navbar/hero */
            }

            .breadcrumb-item+.breadcrumb-item::before {
                content: "›";
                /* Use custom separator for the breadcrumb */
            }

            .breadcrumb-item a {
                color: white;
                /* Link color */
                text-decoration: none;
            }

            .breadcrumb-item a:hover {
                text-decoration: underline;
                /* Hover effect */
            }
        </style>
    </head>

<body>

    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
            <a href="/" class="navbar-brand d-flex hero-items-center">
                <img src="{{ asset('img/jdihn.png') }}" alt="Logo" class="me-3"> <!-- menambahkan margin-end untuk jarak -->
                <img src="{{ asset('img/BNPB.png') }}" alt="Logo" class="me-3"> <!-- menambahkan margin-end untuk jarak -->
                <div class="d-flex flex-column">
                    <h5 class="mb-0 text-justify">Jaringan Dokumentasi dan Informasi Hukum</h5> <!-- menghilangkan margin bawah -->
                    <h5 class="mb-0 text-justify">Badan Nasional Penanggulangan Bencana</h5> <!-- menghilangkan margin bawah -->
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="/" class="nav-item nav-link active">Home</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Jenis Dokumen</a>
                        <div class="dropdown-menu m-0">
                            <a href="{{ route('peraturan.index') }}" class="dropdown-item">Peraturan</a>
                            <a href="{{ route('monograf.index') }}" class="dropdown-item">Monografi</a>
                            <a href="{{ route('artikel.index') }}" class="dropdown-item">Artikel</a>
                            <a href="{{ route('putusan.index') }}" class="dropdown-item">Putusan</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Perihal</a>
                        <div class="dropdown-menu m-0">
                            <a href="{{ route('about') }}" class="dropdown-item">Tentang Kami</a>
                            <a href="{{ route('visiMisi') }}" class="dropdown-item">Visi Misi</a>
                            <a href="{{ route('struktur') }}" class="dropdown-item">Struktur Organisasi Biro Hukum</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Informasi Hukum</a>
                        <div class="dropdown-menu m-0">
                            <a href="{{ route('berita.index') }}" class="dropdown-item">Berita</a>
                            <a href="{{ route('infografis.index') }}" class="dropdown-item">Infografis</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Hero Image Section -->
        <div class="hero-image text-center text-white">
            <div class="overlay position-absolute w-100 h-100"></div>
            <div class="container py-5">
                <h1 class="title-bread display-4 fw-bold">PRODUK HUKUM</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mt-3">
                        <!-- Breadcrumb Home -->
                        <li class="breadcrumb-item">
                            <a href="/" class="text-white text-decoration-none">
                                <i class="fas fa-home me-2"></i>Home
                            </a>
                        </li>

                        <!-- Breadcrumb untuk "Peraturan" jika route adalah 'peraturan.index' -->
                        @if (Route::is('peraturan.index'))
                        <li class="breadcrumb-item active text-warning" aria-current="page">Peraturan</li>

                        <!-- Breadcrumb untuk "Monografi" jika route adalah 'monografi.index' -->
                        @elseif (Route::is('monograf.index'))
                        <li class="breadcrumb-item active text-warning" aria-current="page">Monografi</li>

                        <!-- Breadcrumb untuk "Artikel" jika route adalah 'artikel.index' -->
                        @elseif (Route::is('artikel.index'))
                        <li class="breadcrumb-item active text-warning" aria-current="page">Artikel</li>

                        <!-- Breadcrumb untuk "Putusan" jika route adalah 'putusan.index' -->
                        @elseif (Route::is('putusan.index'))
                        <li class="breadcrumb-item active text-warning" aria-current="page">Putusan</li>

                        <!-- Kondisi default jika tidak ada yang cocok -->
                        @else
                        <li class="breadcrumb-item active text-warning" aria-current="page">All</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>