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
    <link href="{{ asset('css/bootstrap.min.css') }}?v={{ filemtime(public_path('css/bootstrap.min.css')) }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}" rel="stylesheet">

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

        <!-- Hero Image Start -->
        <div class="hero-section hero-main">
            <div class="hero-overlay"></div>
            <div class="container hero-content pt-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-8 text-center">
                        <h1 class="hero-title">Cari Dokumen Hukum</h1>

                        <form method="GET" action="{{ route('list-doc') }}" class="search-form mt-4">
                            <div class="input-group search-group-main shadow-lg">
                                <input type="text" class="form-control" placeholder="Masukkan judul atau kata kunci..." name="judul" value="{{ request('judul') }}">
                                <button class="btn btn-primary btn-search-hero" type="submit">
                                    <i class="fas fa-search me-2"></i> <span>Cari</span>
                                </button>
                            </div>

                            <button class="btn btn-link text-white mt-2" type="button" id="advanced-search-toggle">
                                <i class="fas fa-filter me-1"></i> Pencarian Lanjutan
                            </button>

                            <div class="advanced-filters p-4 mt-3" style="display: none;">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <select class="form-select" name="bentuk">
                                            <option value="">Bentuk Dokumen</option>
                                            @foreach($data['dokumenAttributes']['ref_jenis_dokumen_id'] as $id => $nama)
                                            <option value="{{ $id }}">{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4"><input type="number" class="form-control" name="no" placeholder="Nomor"></div>
                                    <div class="col-md-4"><input type="number" class="form-control" name="tahun" placeholder="Tahun"></div>
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

    <!-- Navbar & Hero End -->