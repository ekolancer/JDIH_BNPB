<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>JDIH - Badan Nasional Penanggulangan Bencana</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500&family=Jost:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar & Hero Start -->
        <div class="container-xxl position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <!-- <h1 class="m-0">DGital</h1> -->
                    <img src="img/JDIH.png" alt="Logo">
                    <img src="img/BNPB.png" alt="Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto py-0">
                        <a href="index.html" class="nav-item nav-link active">Home</a>
                        <a href="about.html" class="nav-item nav-link">Tentang Kami</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Jenis Dokumen</a>
                            <div class="dropdown-menu m-0">
                                <a href="testimonial.html" class="dropdown-item">Peraturan</a>
                                <a href="testimonial.html" class="dropdown-item">Monografi</a>
                                <a href="testimonial.html" class="dropdown-item">Artikel / Majalah Hukum</a>
                                <a href="testimonial.html" class="dropdown-item">Putusan</a>
                                <a href="404.html" class="dropdown-item">404 Page</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Berita</a>
                    </div>
                    <a href="/webmin" class="btn rounded-pill py-2 px-4 ms-3 d-none d-lg-block">Login</a>
                </div>
            </nav>

            <div class="container-xxl bg-primary hero-header">
                <div class="container px-lg-5">
                    <div class="row g-5 align-items-end">
                        <div class="col-lg-6 text-center text-lg-start">
                            <h1 class="text-white mb-4 animated slideInDown">JDIH Badan Nasional Penanggulangan Bencana
                            </h1>
                            <p class="text-white pb-3 animated slideInDown">Jaringan Dokumentasi dan Informasi Hukum
                                Nasional yang selanjutnya disebut JDIHN adalah wadah pendayagunaan bersama atas dokumen
                                hukum secara tertib terpadu, dan berkesinambungan, serta merupakan sarana pemberian
                                pelayanan informasi hukum secara lengkap, akurat, mudah, dan cepat.</p>
                            <a href=""
                                class="btn btn-secondary py-sm-3 px-sm-5 rounded-pill me-3 animated slideInLeft">Read
                                More</a>
                        </div>
                        <div class="col-lg-6 text-center text-lg-start">
                            <img class="img-fluid animated zoomIn" src="img/heronew.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->

        <!-- Searchbar -->
        <div class="container py-5">
            <div class="p-4 bg-light rounded shadow-sm">
                <div class="wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="text-center mb-5">Pencarian Dokumen</h1>
                </div>
                <form action="#" method="GET">
                    <!-- Pencarian Utama -->
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fa fa-search"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" placeholder="Pencarian database (ex: judul peraturan, nomor)">
                                <button type="submit" class="btn btn-dark px-4">Cari</button>
                            </div>
                        </div>
                    </div>
                    <!-- Filter (Di Bawah) -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-3">
                            <select class="form-select">
                                <option selected>Pilih Tipe Dokumen</option>
                                <option value="1">Peraturan Perundang-Undangan</option>
                                <option value="2">Monografi</option>
                                <option value="3">Artikel</option>
                                <option value="3">Putusan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Pencarian Nomor">
                        </div>
                        <div class="col-md-3">
                            <select id="yearSelect" class="form-select">
                                <option selected>Pilih Tahun</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Searchbar End -->

        <!-- Service Start -->
        <div class="container-xxl py-5">
            <div class="container py-5 px-lg-5">
                <div class="wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="text-center mb-5">Koleksi Kami</h1>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="service-item d-flex flex-column text-center rounded">
                            <div class="service-icon flex-shrink-0">
                                <i class="fa fa-balance-scale fa-2x"></i>
                            </div>
                            <h5 class="mb-3">Peraturan</h5>
                            <p class="m-0">Jenis dokumen hukum: Peraturan Perundang-Undangan</p>
                            <a class="btn btn-square" href=""><i class="fa fa-hand-point-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="service-item d-flex flex-column text-center rounded">
                            <div class="service-icon flex-shrink-0">
                                <i class="fa fa-book-open fa-2x"></i>
                            </div>
                            <h5 class="mb-3">Monografi Hukum</h5>
                            <p class="m-0">Jenis dokumen hukum: Buku Hukum, Naskah Akademik, Analisis dan Evaluasi
                                Rancangan Peraturan Perundang-Undangan</p>
                            <a class="btn btn-square" href=""><i class="fa fa-hand-point-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="service-item d-flex flex-column text-center rounded">
                            <div class="service-icon flex-shrink-0">
                                <i class="fa fa-newspaper fa-2x"></i>
                            </div>
                            <h5 class="mb-3">Artikel Hukum</h5>
                            <p class="m-0">Jenis dokumen hukum: Artikel Hukum, Jurnal Hukum, Majalah Hukum, Konvensi</p>
                            <a class="btn btn-square" href=""><i class="fa fa-hand-point-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="service-item d-flex flex-column text-center rounded">
                            <div class="service-icon flex-shrink-0">
                                <i class="fa fa-gavel fa-2x"></i>
                            </div>
                            <h5 class="mb-3">Putusan</h5>
                            <p class="m-0">Jenis dokumen hukum: Putusan Mahkamah Agung, Putusan Mahkamah Konstitusi</p>
                            <a class="btn btn-square" href=""><i class="fa fa-hand-point-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="service-item d-flex flex-column text-center rounded">
                            <div class="service-icon flex-shrink-0">
                                <i class="fa fa-square-check fa-2x"></i>
                            </div>
                            <h5 class="mb-3">Peraturan Berlaku</h5>
                            <p class="m-0">Kumpulan Peraturan Perundang-Undangan yang masih berlaku</p>
                            <a class="btn btn-square" href=""><i class="fa fa-hand-point-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="service-item d-flex flex-column text-center rounded">
                            <div class="service-icon flex-shrink-0">
                                <i class="fa fa-rectangle-xmark fa-2x"></i>
                            </div>
                            <h5 class="mb-3" style="color: red;">Peraturan Tidak Berlaku</h5>
                            <p class="m-0">Kumpulan Peraturan Perundang-Undangan yang sudah tidak berlaku</p>
                            <a class="btn btn-square" href=""><i class="fa fa-hand-point-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Service End -->

        <!-- Facts Start -->
        <div class="container-xxl fact py-5 wow fadeInUp" data-wow-delay="0.1s"
            style="position: relative; overflow: hidden; background: url('img/bg-data.png') no-repeat center center / cover;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); z-index: 1;"></div>
            <div class="container py-5 px-lg-5" style="position: relative; z-index: 2;">
                <div class="wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="text-center mb-5 text-white">Jumlah Dokumen Hukum</h1>
                </div>
                <div class="row g-4">
                    <!-- Pengunjung Setahun Terakhir -->
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.1s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                        <i class="fa fa-calendar-alt fa-3x text-secondary mb-3"></i>
                        <h1 class="text-white mb-2" data-toggle="counter-up">651.857</h1>
                        <p class="text-white mb-0">Peraturan</p>
                    </div>
                    <!-- Pengunjung Sebulan Terakhir -->
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.3s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                        <i class="fa fa-chart-bar fa-3x text-secondary mb-3"></i>
                        <h1 class="text-white mb-2" data-toggle="counter-up">137.975</h1>
                        <p class="text-white mb-0">Monografi</p>
                    </div>
                    <!-- Pengunjung Kemarin -->
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.5s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                        <i class="fa fa-clock fa-3x text-secondary mb-3"></i>
                        <h1 class="text-white mb-2" data-toggle="counter-up">3.028</h1>
                        <p class="text-white mb-0">Artikel</p>
                    </div>
                    <!-- Pengunjung Hari Ini -->
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.7s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                        <i class="fa fa-users fa-3x text-secondary mb-3"></i>
                        <h1 class="text-white mb-2" data-toggle="counter-up">897</h1>
                        <p class="text-white mb-0">Putusan</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Facts End -->

        <!-- Projects Start -->
        <div class="container-xxl py-5">
            <div class="container py-5 px-lg-5">
                <div class="wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="text-center mb-5">Informasi Hukum</h1>
                </div>
                <div class="row mt-n2 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="col-12 text-center">
                        <ul class="list-inline mb-5" id="portfolio-flters">
                            <li class="mx-2 active" data-filter="*">Berita</li>
                            <li class="mx-2" data-filter=".first">Infografis</li>
                        </ul>
                    </div>
                </div>
                <div class="row g-4 portfolio-container">
                    <div class="col-lg-4 col-md-6 portfolio-item first wow fadeInUp" data-wow-delay="0.1s">
                        <div class="rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="img/portfolio-1.jpg" alt="">
                                <div class="portfolio-overlay">
                                    <a class="btn btn-square btn-outline-light mx-1" href="img/portfolio-1.jpg"
                                        data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-square btn-outline-light mx-1" href=""><i
                                            class="fa fa-link"></i></a>
                                </div>
                            </div>
                            <div class="bg-light p-4">
                                <p class="text-primary fw-medium mb-2">UI / UX Design</p>
                                <h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 portfolio-item second wow fadeInUp" data-wow-delay="0.3s">
                        <div class="rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="img/portfolio-2.jpg" alt="">
                                <div class="portfolio-overlay">
                                    <a class="btn btn-square btn-outline-light mx-1" href="img/portfolio-2.jpg"
                                        data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-square btn-outline-light mx-1" href=""><i
                                            class="fa fa-link"></i></a>
                                </div>
                            </div>
                            <div class="bg-light p-4">
                                <p class="text-primary fw-medium mb-2">UI / UX Design</p>
                                <h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 portfolio-item first wow fadeInUp" data-wow-delay="0.5s">
                        <div class="rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="img/portfolio-3.jpg" alt="">
                                <div class="portfolio-overlay">
                                    <a class="btn btn-square btn-outline-light mx-1" href="img/portfolio-3.jpg"
                                        data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-square btn-outline-light mx-1" href=""><i
                                            class="fa fa-link"></i></a>
                                </div>
                            </div>
                            <div class="bg-light p-4">
                                <p class="text-primary fw-medium mb-2">UI / UX Design</p>
                                <h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 portfolio-item second wow fadeInUp" data-wow-delay="0.1s">
                        <div class="rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="img/portfolio-4.jpg" alt="">
                                <div class="portfolio-overlay">
                                    <a class="btn btn-square btn-outline-light mx-1" href="img/portfolio-4.jpg"
                                        data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-square btn-outline-light mx-1" href=""><i
                                            class="fa fa-link"></i></a>
                                </div>
                            </div>
                            <div class="bg-light p-4">
                                <p class="text-primary fw-medium mb-2">UI / UX Design</p>
                                <h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 portfolio-item first wow fadeInUp" data-wow-delay="0.3s">
                        <div class="rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="img/portfolio-5.jpg" alt="">
                                <div class="portfolio-overlay">
                                    <a class="btn btn-square btn-outline-light mx-1" href="img/portfolio-5.jpg"
                                        data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-square btn-outline-light mx-1" href=""><i
                                            class="fa fa-link"></i></a>
                                </div>
                            </div>
                            <div class="bg-light p-4">
                                <p class="text-primary fw-medium mb-2">UI / UX Design</p>
                                <h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 portfolio-item second wow fadeInUp" data-wow-delay="0.5s">
                        <div class="rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="img/portfolio-6.jpg" alt="">
                                <div class="portfolio-overlay">
                                    <a class="btn btn-square btn-outline-light mx-1" href="img/portfolio-6.jpg"
                                        data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-square btn-outline-light mx-1" href=""><i
                                            class="fa fa-link"></i></a>
                                </div>
                            </div>
                            <div class="bg-light p-4">
                                <p class="text-primary fw-medium mb-2">UI / UX Design</p>
                                <h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Projects End -->

        <section id="details" class="details section">
            <!-- Section Title -->
            <div class="wow fadeInUp" data-wow-delay="0.1s">
                <p class="section-title text-secondary justify-content-center"><span></span>Survey<span></span></p>
            </div>
            <!-- End Section Title -->

            <div class="container">
                <div class="row gy-4 align-items-center features-item">
                    <div class="col-md-5 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="100">
                        <img src="img/details-1.png" class="img-fluid" alt="">
                    </div>
                    <div class="col-md-7" data-aos="fade-up" data-aos-delay="100">
                        <h3>Indeks Kepuasan Masyarakat</h3>
                        <p class="fst-italic">
                            Mohon mengisi survey Indeks Kepuasan Masyarakat (IKM) sebagai masukan </br>untuk penyajian
                            website yang lebih baik.
                        </p>
                        <!-- Location Button -->
                        <a href="https://example.com/survey" target="_blank" class="btn btn-primary btn-animated">
                            Isi Survey
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Visito Counter -->
        <div class="container-xxl fact py-5 wow fadeInUp" data-wow-delay="0.1s"
            style="position: relative; overflow: hidden; background: url('img/statistik.png') no-repeat center center / cover;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); z-index: 1;"></div>
            <div class="container py-5 px-lg-5" style="position: relative; z-index: 2;">
                <div class="wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="text-center mb-5 text-white">Statistik Pengunjung</h1>
                </div>
                <div class="row g-4">
                    <!-- Pengunjung Setahun Terakhir -->
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.1s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                        <i class="fa fa-calendar-alt fa-3x text-secondary mb-3"></i>
                        <h1 class="text-white mb-2" data-toggle="counter-up">651.857</h1>
                        <p class="text-white mb-0">Pengunjung Setahun Terakhir</p>
                    </div>
                    <!-- Pengunjung Sebulan Terakhir -->
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.3s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                        <i class="fa fa-chart-bar fa-3x text-secondary mb-3"></i>
                        <h1 class="text-white mb-2" data-toggle="counter-up">137.975</h1>
                        <p class="text-white mb-0">Pengunjung Sebulan Terakhir</p>
                    </div>
                    <!-- Pengunjung Kemarin -->
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.5s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                        <i class="fa fa-clock fa-3x text-secondary mb-3"></i>
                        <h1 class="text-white mb-2" data-toggle="counter-up">3.028</h1>
                        <p class="text-white mb-0">Pengunjung Kemarin</p>
                    </div>
                    <!-- Pengunjung Hari Ini -->
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.7s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                        <i class="fa fa-users fa-3x text-secondary mb-3"></i>
                        <h1 class="text-white mb-2" data-toggle="counter-up">897</h1>
                        <p class="text-white mb-0">Pengunjung Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Visito Counter Ends -->

        <!-- Footer Start -->
        <div class="container-fluid bg-primary text-light footer wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5 px-lg-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-3">
                        <img class="img-fluid" src="img/JDIH.png" alt="Image" style="width: 70px; height: 70px;">
                        <img class="img-fluid" src="img/BNPB.png" alt="Image" style="width: 70px; height: 70px;">
                        <div class="row g-2">
                            <p class="section-title text-white h5 mb-4" style="margin-top: 35px;">JDIH BNPB</p>
                            <p style="margin-top: -55px; text-align: justify;">Jaringan Dokumentasi dan Informasi Hukum Nasional yang selanjutnya disebut JDIHN adalah
                                wadah pendayagunaan bersama atas dokumen hukum secara tertib terpadu, dan berkesinambungan,
                                serta merupakan sarana pemberian pelayanan informasi hukum secara lengkap, akurat, mudah, dan cepat.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4" style="margin-top: 35px;">Alamat</p>
                        <p><i class="fa fa-map-marker-alt me-3"></i>Graha BNPB - Jl. Pramuka Kav.38 Jakarta Timur 13120</p>
                        <p><i class="fa fa-phone-alt me-3"></i>021-29827793</p>
                        <p><i class="fa fa-fax me-3"></i>021-21281200</p>
                        <p><i class="fa fa-envelope me-3"></i>persuratan@bnpb.go.id</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href="https://twitter.com/BNPB_Indonesia"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href="https://www.facebook.com/infobnpb"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href="https://www.instagram.com/bnpb_indonesia/"><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-outline-light btn-social" href="https://www.youtube.com/user/BNPBIndonesia/"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4" style="margin-top: 35px;">Tautan Terkait</p>
                        <a class="btn btn-link" href="https://www.bnpb.go.id/">Website BNPB</a>
                        <a class="btn btn-link" href="https://jdihn.go.id/">Portal JDIH</a>
                        <a class="btn btn-link" href="https://www.bphn.go.id/">Portal BPHN</a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4" style="margin-top: 35px;">Berlangganan</p>
                        <p>Dapatkan informasi terbaru seputar dokumen hukum.</p>
                        <div class="position-relative w-100 mt-3">
                            <input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text"
                                placeholder="Your Email" style="height: 48px;">
                            <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i
                                    class="fa fa-paper-plane text-primary fs-4"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container px-lg-5">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">BNPB (Badan Nasional Penanggulangan Bencana)</a>, All Right Reserved.

                            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                            Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="/">Home</a>
                                <a href="#">Cookies</a>
                                <a href="#">Help</a>
                                <a href="#">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-secondary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="https://kit.fontawesome.com/2639b34ea1.js" crossorigin="anonymous"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <!-- JS untuk Tahun -->
    <script>
        // Elemen dropdown
        const yearSelect = document.getElementById('yearSelect');

        // Loop dari 2024 ke 1990
        for (let year = 2024; year >= 1990; year--) {
            const option = document.createElement('option'); // Buat elemen <option>
            option.value = year; // Nilai value untuk setiap <option>
            option.textContent = year; // Teks yang tampil pada dropdown
            yearSelect.appendChild(option); // Tambahkan <option> ke dropdown
        }
    </script>
</body>

</html>