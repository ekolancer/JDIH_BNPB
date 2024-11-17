@extends('layout.app')
@section('content')

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
                <i class="fa fa-balance-scale fa-3x text-secondary mb-3"></i>
                <h1 class="text-white mb-2" data-toggle="counter-up">{{ $data['peraturan'] }}</h1>
                <p class="text-white mb-0">Peraturan</p>
            </div>
            <!-- Pengunjung Sebulan Terakhir -->
            <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.3s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                <i class="fa fa-book-open fa-3x text-secondary mb-3"></i>
                <h1 class="text-white mb-2" data-toggle="counter-up">{{ $data['monografi'] }}</h1>
                <p class="text-white mb-0">Monografi</p>
            </div>
            <!-- Pengunjung Kemarin -->
            <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.5s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                <i class="fa fa-newspaper fa-3x text-secondary mb-3"></i>
                <h1 class="text-white mb-2" data-toggle="counter-up">{{ $data['artikel'] }}</h1>
                <p class="text-white mb-0">Artikel</p>
            </div>
            <!-- Pengunjung Hari Ini -->
            <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.7s" style="position: relative; border: 2px solid #fff; border-radius: 10px; padding: 20px; background-color: rgba(0, 0, 0, 0.6);">
                <i class="fa fa-gavel fa-3x text-secondary mb-3"></i>
                <h1 class="text-white mb-2" data-toggle="counter-up">{{ $data['putusan'] }}</h1>
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

@endsection