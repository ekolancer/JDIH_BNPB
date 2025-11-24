<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XQ4PR27V84"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-XQ4PR27V84');
    </script>
    <meta charset="utf-8">
    <title>JDIH - Badan Nasional Penanggulangan Bencana</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="JDIH, Badan Nasional Penanggulangan Bencana, BNPB, Dokumentasi Hukum, Informasi Hukum" name="keywords">
    <meta content="Jaringan Dokumentasi dan Informasi Hukum Badan Nasional Penanggulangan Bencana menyediakan informasi dan dokumentasi hukum terkait penanggulangan bencana di Indonesia." name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome for arrows -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Removed duplicate Font Awesome script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <!-- AOS Library Stylesheet -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Swiper Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    <!-- Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Livewire Stylesheet -->
    <livewire:styles />

</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <svg class="pl" width="240" height="240" viewBox="0 0 240 240">
            <circle class="pl__ring pl__ring--a" cx="120" cy="120" r="105" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 660" stroke-dashoffset="-330" stroke-linecap="round"></circle>
            <circle class="pl__ring pl__ring--b" cx="120" cy="120" r="35" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 220" stroke-dashoffset="-110" stroke-linecap="round"></circle>
            <circle class="pl__ring pl__ring--c" cx="85" cy="120" r="70" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
            <circle class="pl__ring pl__ring--d" cx="155" cy="120" r="70" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
        </svg>
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
                    <!-- Dropdown Dokumen Hukum dengan Livewire -->
                    <livewire:dropdown-dokumen />
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Informasi Hukum</a>
                        <div class="dropdown-menu m-0">
                            <a href="{{ route('berita.index') }}" class="dropdown-item">Berita</a>
                            <a href="{{ route('infografis.index') }}" class="dropdown-item">Infografis</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Perihal</a>
                        <div class="dropdown-menu m-0">
                            <a href="/profil/tentang-kami" class="dropdown-item">Tentang Kami</a>
                            <a href="/profil/visi-misi" class="dropdown-item">Visi Misi</a>
                            <a href="/profil/struktur-organisasi" class="dropdown-item">Struktur Organisasi</a>
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
                        <!-- resources/views/index.blade.php -->
                        <form method="GET" action="{{ route('list-doc') }}" class="search-form">
                            <div class="input-group mb-3 w-75 mx-auto">
                                <input type="text"
                                    class="form-control"
                                    placeholder="Cari dokumen hukum..."
                                    aria-label="Search"
                                    aria-describedby="button-addon2"
                                    name="judul"
                                    value="{{ request('judul') }}">
                                <button class="btn btn-warning" type="submit" id="button-addon2">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>

                            <!-- Optional Advanced Search Button -->
                            <button class="btn btn-secondary" type="button" id="advanced-search-toggle">
                                <i class="fas fa-filter"></i> Pencairan Lanjutan
                            </button>

                            <!-- Advanced Filters (Hidden by Default) -->
                            <div class="advanced-filters mt-3" style="display: none;">
                                <div class="row">
                                    <!-- Bentuk Dokumen -->
                                    <div class="col-md-4 mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                            <select class="form-select" id="documentForm" name="jenisDok">
                                                <option value="">Bentuk Dokumen</option>
                                                @foreach($data['dokumenAttributes']['jenisDok'] as $bentuk)
                                                <option value="{{ $bentuk }}" {{ request('jenisDok') == $bentuk ? 'selected' : '' }}>
                                                    {{ $bentuk }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Nomor -->
                                    <div class="col-md-4 mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                            <input type="number"
                                                class="form-control"
                                                id="documentNumber"
                                                name="nomor"
                                                placeholder="Nomor"
                                                value="{{ request('nomor') }}">
                                        </div>
                                    </div>

                                    <!-- Tahun -->
                                    <div class="col-md-4 mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="number"
                                                class="form-control"
                                                id="documentYear"
                                                name="tahun"
                                                placeholder="Tahun"
                                                value="{{ request('tahun') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
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