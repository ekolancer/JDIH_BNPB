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
                    <a href="/" class="nav-item nav-link active">Beranda</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Dokumen Hukum</a>
                        <div class="dropdown-menu m-0">
                            <a href="{{ route('dokumen.filter', ['tipeDok' => 'peraturan']) }}" class="dropdown-item">Peraturan</a>
                            <a href="{{ route('dokumen.filter', ['tipeDok' => 'monografi']) }}" class="dropdown-item">Monografi</a>
                            <a href="{{ route('dokumen.filter', ['tipeDok' => 'artikel']) }}" class="dropdown-item">Artikel</a>
                            <a href="{{ route('dokumen.filter', ['tipeDok' => 'putusan']) }}" class="dropdown-item">Putusan</a>
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

        <!-- Hero Image Section -->
        <div class="hero-image position-relative text-center text-white d-flex align-items justify-content-center">
            <div class="overlay position-absolute w-100 h-100"></div>
            <div class="container py-5">
                <h1 class="title-bread display-4 fw-bold">Halaman Tidak Ditemukan</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mt-3">
                        <!-- Breadcrumb Home -->
                        <li class="breadcrumb-item">
                            <a href="/" class="text-white text-decoration-none">
                                <i class="fas fa-home me-2"></i>Beranda
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-warning" aria-current="page">404</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Hero Image Section End -->

    <!-- Content 404 Start -->
    <div class="text-center py-5">
        <img src="{{ asset('img/404.png') }}" alt="404 Error" class="img-fluid" style="max-width: 30%; height: 20%;">
        <h2 class="mt-4">Oops! Halaman Tidak Ditemukan</h2>
        <p class="mb-4">Maaf, halaman yang Anda cari tidak ditemukan. Coba kembali ke halaman utama.</p>
        <a href="/" class="btn btn-primary btn-lg">Kembali ke Halaman Utama</a>
    </div>
    <!-- Content 404 End -->

    <!-- Footer Start -->
    <footer class="footer-section">
        <div class="container">
            <div class="footer-content pt-3 pb-3">
                <div class="row">
                    <!-- Kolom 1 -->
                    <div class="col-xl-4 col-lg-4 mb-50">
                        <div class="footer-widget">
                            <div class="footer-logo">
                                <a href="/"><img src="{{ asset('img/update_logo.png') }}" class="img-fluid" alt="logo"></a>
                            </div>
                            <div class="footer-text me-5">
                                <p>Jaringan Dokumentasi dan Informasi Hukum Nasional yang selanjutnya disebut JDIHN adalah wadah pendayagunaan bersama atas dokumen hukum secara tertib terpadu, dan berkesinambungan, serta merupakan sarana pemberian pelayanan informasi hukum secara lengkap, akurat, mudah, dan cepat.</p>
                            </div>
                            <div class="footer-social-icon">
                                <span>Follow us</span>
                                <a href="https://www.facebook.com/infobnpb"><i class="fab fa-facebook-f facebook-bg"></i></a>
                                <a href="https://twitter.com/BNPB_Indonesia"><i class="fab fa-twitter twitter-bg"></i></a>
                                <a href="https://www.instagram.com/bnpb_indonesia/"><i class="fab fa-instagram instagram-bg"></i></a>
                                <a href="https://www.youtube.com/user/BNPBIndonesia/"><i class="fab fa-youtube youtube-bg"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Kolom 2 -->
                    <div class="col-xl-2 col-lg-2 col-md-6 mb-30">
                        <div class="footer-widget pt-5">
                            <div class="footer-widget-heading">
                                <h3>JDIH BNPB</h3>
                            </div>
                            <ul class="list-unstyled">
                                <li><a href="#">Profil</a></li>
                                <li><a href="#">Struktur Organisasi</a></li>
                                <li><a href="#">Berita</a></li>
                                <li><a href="#">Statistik</a></li>
                                <li><a href="#">Visi dan Misi</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Kolom 3 -->
                    <div class="col-xl-2 col-lg-2 col-md-6 mb-30">
                        <div class="footer-widget pt-5">
                            <div class="footer-widget-heading">
                                <h3>TAUTAN</h3>
                            </div>
                            <ul class="list-unstyled">
                                <li><a href="https://bnpb.go.id">Website BNPB</a></li>
                                <li><a href="https://jdihn.go.id/">Portal JDIH Nasional</a></li>
                                <li><a href="http://bphn.jdihn.go.id/">Portal BPHN</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Kolom 4 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-50">
                        <div class="footer-widget pt-5">
                            <div class="footer-widget-heading">
                                <h3>HUBUNGI KAMI</h3>
                            </div>
                            <div class="footer-text mb-25">
                                <ul style="color: dimgrey;">
                                    <li><i class="fas fa-home me-2"></i>Graha BNPB - Jl. Pramuka Kav.38 Jakarta Timur 13120</li>
                                    <li><i class="fas fa-phone-alt me-2"></i> 021-29827793</li>
                                    <li><i class="fas fa-fax me-2"></i> 021-21281200</li>
                                    <li><i class="fas fa-envelope me-2"></i> jdih@bnpb.go.id</li>
                                </ul>
                            </div>
                            <div class="footer-widget-heading pt-4">
                                <h3>Subscribe</h3>
                            </div>
                            <div class="subscribe-form">
                                <form action="#">
                                    <input type="text" placeholder="Email Address">
                                    <button><i class="fab fa-telegram-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <div class="copyright-text">
                            <p>Copyright &copy; 2024, All Right Reserved <a href="https://bnpb.go.id/">Badan Nasional Penanggulangan Bencana</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Copyright End -->
    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i
            class="fa fa-arrow-up"></i></a>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const row = document.querySelector('.row.g-4');
            console.log(row); // Periksa apakah elemen ditemukan
        });
    </script>
</body>

</html>