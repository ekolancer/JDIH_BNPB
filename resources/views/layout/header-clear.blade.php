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
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/2639b34ea1.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css" rel="stylesheet') }}">


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
                <img src="{{ asset('img/bnpb.png') }}" alt="Logo" class="me-3"> <!-- menambahkan margin-end untuk jarak -->
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
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">About</a>
                        <div class="dropdown-menu m-0">
                            <a href="#" class="dropdown-item">Sekilah Sejarah</a>
                            <a href="#" class="dropdown-item">Dasar Hukum</a>
                            <a href="#" class="dropdown-item">Visi Misi</a>
                            <a href="#" class="dropdown-item">Struktur Organisasi</a>
                        </div>
                    </div>
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
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Informasi Hukum</a>
                        <div class="dropdown-menu m-0">
                            <a href="#" class="dropdown-item">Berita</a>
                            <a href="#" class="dropdown-item">Infografis</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Hero Image Start -->
        <div class="hero-image-container"></div>
        <div class="container h-100 d-flex justify-content-center hero-items-center">
            <div class="row w-100"></div>
        </div>
        <!-- Hero Image End -->

</body>