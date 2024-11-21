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
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #0052cc;
            padding: 10px 20px;
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link {
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
    </style>
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
            <a href="#" class="navbar-brand d-flex p-0">
                <img src="img/jdihn.png" alt="Logo">
                <img src="img/bnpb.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.html" class="nav-item nav-link active">Home</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">About</a>
                        <div class="dropdown-menu m-0">
                            <a href="destination.html" class="dropdown-item">Sekilah Sejarah</a>
                            <a href="tour.html" class="dropdown-item">Dasar Hukum</a>
                            <a href="booking.html" class="dropdown-item">Visi Misi</a>
                            <a href="gallery.html" class="dropdown-item">Struktur Organisasi</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Jenis Dokumen</a>
                        <div class="dropdown-menu m-0">
                            <a href="{{ route('peraturan.index')}}" class="dropdown-item">Peraturan</a>
                            <a href="tour.html" class="dropdown-item">Monografi</a>
                            <a href="booking.html" class="dropdown-item">Artikel</a>
                            <a href="gallery.html" class="dropdown-item">Putusan</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Informasi Hukum</a>
                        <div class="dropdown-menu m-0">
                            <a href="destination.html" class="dropdown-item">Berita</a>
                            <a href="tour.html" class="dropdown-item">Infografis</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar & Hero End -->

    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dokumen Hukum / Peraturan</a>
            <form class="d-flex search-bar">
                <input class="form-control me-2" type="search" placeholder="Ketik kata kunci pencarian" aria-label="Search">
                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-6 d-flex justify-content-start">
                <button class="btn btn-outline-secondary me-2"><i class="fas fa-filter"></i> Pencarian Lanjutan</button>
                <button class="btn btn-outline-secondary"><i class="fas fa-microphone"></i> Pencarian menggunakan Suara</button>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center">
                <span class="me-2">Terdapat 4.552 data</span>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary"><i class="fas fa-th"></i></button>
                    <button type="button" class="btn btn-outline-secondary"><i class="fas fa-th-list"></i></button>
                </div>
            </div>
        </div>
    </div>