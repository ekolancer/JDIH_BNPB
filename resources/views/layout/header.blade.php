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

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
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

    <!-- Topbar Start -->
    <div class="container-fluid bg-primary px-5 d-none d-lg-block">
        <div class="row gx-0">
            <!-- Kolom Kiri: Ikon Sosial Media -->
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex justify-content-center align-items-center" style="height: 45px;">
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-twitter fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-facebook-f fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-instagram fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href=""><i class="fab fa-youtube fw-normal"></i></a>
                </div>
            </div>

            <!-- Kolom Kanan: Link Login -->
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex justify-content-center align-items-center" style="height: 45px;">
                    <a href="#"><small class="me-3 text-light"><i class="fa fa-sign-in-alt me-2"></i>Login</small></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


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

        <!-- Carousel Start -->
        <div class="carousel-header">
            <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#carouselId" data-bs-slide-to="1"></li>
                    <li data-bs-target="#carouselId" data-bs-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <!-- <div class="carousel-item active"> -->
                    <img src="img/headerhero.jpg" class="img-fluid" alt="Image">
                    <div class="carousel-caption">
                        <div class="p-3" style="max-width: 900px;"></div>
                        <div class="search-container">
                            <div class="search-box p-5">
                                <h1 class="text-center mb-4" style="color: #fff;">Peraturan apa yang anda ingin cari ?</h1>
                                <div class="input-group mb-4">
                                    <input type="text" class="form-control rounded-pill" placeholder="Masukan Kata Kunci"
                                        style="line-height: 2;" aria-label="Masukan Kata Kunci">
                                    <button class="btn btn-primary rounded-pill" type="button" id="filterButton">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button class="btn btn-info rounded-pill" type="button">Cari</button>
                                </div>

                                <!-- Filter Section -->
                                <div class="filter-section" id="filterSection" style="display: none;">
                                    <div class="d-flex justify-content-between">
                                        <div class="form-group w-30">
                                            <label for="tipeDokumen" class="form-label">Tipe Dokumen</label>
                                            <select class="form-select" id="tipeDokumen">
                                                <option selected>Pilih Tipe Dokumen</option>
                                                <option value="1">Tipe 1</option>
                                                <option value="2">Tipe 2</option>
                                                <option value="3">Tipe 3</option>
                                            </select>
                                        </div>
                                        <div class="form-group w-30">
                                            <label for="nomor" class="form-label">Nomor</label>
                                            <input type="text" class="form-control" id="nomor" placeholder="Masukan Nomor">
                                        </div>
                                        <div class="form-group w-30">
                                            <label for="tahun" class="form-label">Tahun</label>
                                            <input type="text" class="form-control" id="tahun" placeholder="Masukan Tahun">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->
    </div>

    <!-- Navbar & Hero End -->