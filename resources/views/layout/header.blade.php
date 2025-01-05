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

    <!-- Font Awesome for arrows -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/2639b34ea1.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Swiper Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    <!-- Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="loader"></div>
    </div>
    <!-- Spinner End -->

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
                    <a href="/" class="nav-item nav-link active">Beranda</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Dokumen Hukum</a>
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
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Publikasi</a>
                        <div class="dropdown-menu m-0">
                            <a href="{{ route('berita.index') }}" class="dropdown-item">Berita</a>
                            <a href="{{ route('infografis.index') }}" class="dropdown-item">Infografis</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Hero Image Start -->
        <div class="hero-image-container" style="background-image: url('img/headerhero.webp'); background-size: cover; background-position: center; height: 70vh; position: relative;">
            <div class="container h-100 d-flex justify-content-center hero-items-center">
                <div class="row w-100">
                    <div class="col-12 text-center">
                        <p style="font-weight: bold;">
                        <h1 style="font: size 60px; color:white;">Cari Dokumen hukum disini.</h1>
                        </p>
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('dashboard.search') }}">
                            <div class="input-group mb-3 w-75 mx-auto">
                                <input type="text"
                                    class="form-control"
                                    placeholder="Masukan kata kunci pencarian"
                                    aria-label="Search"
                                    aria-describedby="button-addon2"
                                    name="q"
                                    value="{{ request('q') }}">
                                <button class="btn btn-outline-warning" type="submit" id="button-addon2">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>

                            <!-- Optional Advanced Search Button -->
                            <button class="btn btn-outline-secondary" type="button" id="advanced-search-toggle">
                                <i class="fas fa-filter"></i> Advanced Search
                            </button>

                            <!-- Advanced Filters (Hidden by Default) -->
                            <div class="advanced-filters mt-3" style="display: none;">
                                <div class="row">
                                    <!-- Tipe Dokumen -->
                                    <div class="col-md-4 mb-3">
                                        <label for="documentType" class="form-label">Jenis Dokumen</label>
                                        <select class="form-select" id="documentType" name="tipe_dokumen">
                                            <option value="">Pilih Jenis Dokumen</option>
                                            @foreach($tipeDokumen as $tipe)
                                            <option value="{{ $tipe }}" {{ request('tipe_dokumen') == $tipe ? 'selected' : '' }}>
                                                {{ $tipe }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Bentuk Dokumen -->
                                    <div class="col-md-4 mb-3">
                                        <label for="documentForm" class="form-label">Bentuk Dokumen</label>
                                        <select class="form-select" id="documentForm" name="bentuk_dokumen">
                                            <option value="">Bentuk Dokumen</option>
                                            @foreach($bentukDokumen as $bentuk)
                                            <option value="{{ $bentuk }}" {{ request('bentuk_dokumen') == $bentuk ? 'selected' : '' }}>
                                                {{ $bentuk }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Tahun -->
                                    <div class="col-md-4 mb-3">
                                        <label for="documentYear" class="form-label">Tahun</label>
                                        <input type="text"
                                            class="form-control"
                                            id="documentYear"
                                            name="tahun"
                                            placeholder="Tahun"
                                            value="{{ request('tahun') }}">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button class="btn btn-primary" type="submit">Apply Filters</button>
                            </div>
                        </form>
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
                }, 200); // Delay singkat untuk memicu transisi
            }
        });
    </script>

    <!-- Navbar & Hero End -->