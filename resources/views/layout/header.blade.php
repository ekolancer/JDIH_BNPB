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
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        .advanced-filters {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease;
            /* Transisi untuk efek opacity */
            border: 2px solid rgba(128, 128, 128, 0.4);
            /* Border abu-abu transparan */
            border-radius: 5px;
            /* Sudut border yang membulat */
            padding: 15px;
            /* Padding di dalam border */
            margin-top: 10px;
            /* Jarak atas dari elemen sebelumnya */
            background-color: white;
            /* Warna latar belakang putih */
        }

        .advanced-filters.show {
            display: block;
            opacity: 1;
            /* Menampilkan dengan opacity */
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
            <a href="/" class="navbar-brand d-flex align-items-center">
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
                            <a href="#" class="dropdown-item">Monografi</a>
                            <a href="#" class="dropdown-item">Artikel</a>
                            <a href="#" class="dropdown-item">Putusan</a>
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
        <div class="hero-image-container" style="background-image: url('img/headerhero.jpg'); background-size: cover; background-position: center; height: 70vh; position: relative;">
            <div class="container h-100 d-flex justify-content-center align-items-center">
                <div class="row w-100">
                    <div class="col-12 text-center">
                        <!-- Search Form -->
                        <div class="input-group mb-3 w-75 mx-auto">
                            <input type="text" class="form-control" placeholder="Masukan kata kunci pencarian" aria-label="Search" aria-describedby="button-addon2">
                            <button class="btn btn-outline-warning" type="button" id="button-addon2"><i class="fas fa-search"></i></button>
                        </div>
                        <!-- Optional Advanced Search Button -->
                        <button class="btn btn-outline-secondary" type="button" id="advanced-search-toggle"><i class="fas fa-filter"></i> Advanced Search</button>
                        <!-- Advanced Filters (Hidden by Default) -->
                        <div class="advanced-filters mt-3" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="documentType" class="form-label">Tipe Dokumen</label>
                                    <select class="form-select" id="documentType">
                                        <option selected>Pilih Tipe Dokumen</option>
                                        <option value="1">Type 1</option>
                                        <option value="2">Type 2</option>
                                        <option value="3">Type 3</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="documentNumber" class="form-label">Nomor</label>
                                    <input type="text" class="form-control" id="documentNumber" placeholder="Nomor">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="documentYear" class="form-label">Tahun</label>
                                    <input type="text" class="form-control" id="documentYear" placeholder="Tahun">
                                </div>
                            </div>
                            <button class="btn btn-primary" type="button">Apply Filters</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero Image End -->
    </div>
    <!-- Navbar & Hero End -->
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
                }, 100); // Delay singkat untuk memicu transisi
            }
        });
    </script>

    <!-- Navbar & Hero End -->