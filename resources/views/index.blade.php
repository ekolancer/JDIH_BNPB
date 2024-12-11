@extends('layout.app')

@section('content')

<style>
    .card {
        height: 100%;
    }

    .card-body {
        flex: 1 1 auto;
    }

    .main-container {
        background-color: #f2f2f2;
        padding: 20px;
        border-radius: 8px;
        width: 100%;
    }

    /* Efek zoom dan shadow untuk card */
    .hover-zoom {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-zoom:hover {
        transform: scale(1.05);
        /* Zoom efek */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        /* Shadow efek */
    }

    .card-link {
        text-decoration: none;
        /* Menghilangkan garis bawah pada link */
        color: inherit;
        /* Menjaga warna teks agar tetap sama */
    }
</style>

<!-- Stats Section -->
<section id="stats" class="stats section light-background py-5">
    <div class="container">
        <div class="mx-auto text-center mb-3" style="max-width: 900px;">
            <h5 class="section-title px-3">Jumlah Dokumen</h5>
            <!-- <h1 class="mb-0">Jumlah Dokumen</h1> -->
        </div>
        <div class="row gy-4">
            <!-- Item Peraturan -->
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                <i class="fa fa-balance-scale text-primary"></i>
                <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="{{ $data['peraturan'] }}" data-purecounter-duration="1"
                        class="purecounter"></span>
                    <p>Peraturan</p>
                </div>
            </div>
            <!-- Item Monografi -->
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                <i class="fa fa-book-open text-primary"></i>
                <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="{{ $data['monografi'] }}" data-purecounter-duration="1"
                        class="purecounter"></span>
                    <p>Monografi</p>
                </div>
            </div>
            <!-- Item Artikel -->
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                <i class="fa fa-newspaper text-primary"></i>
                <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="{{ $data['artikel'] }}" data-purecounter-duration="1"
                        class="purecounter"></span>
                    <p>Artikel</p>
                </div>
            </div>
            <!-- Item Putusan -->
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                <i class="fa fa-gavel text-primary"></i>
                <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="{{ $data['putusan'] }}" data-purecounter-duration="1"
                        class="purecounter"></span>
                    <p>Putusan</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Stats Section -->

<!-- Peraturan Terbaru -->
<div class="container mt-4 main-container">
    <h2>Peraturan Terbaru</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($peraturan as $item)
        <div class="col">
            <!-- Card yang menjadi hyperlink -->
            <a href="{{ route('peraturan.show', $item->slug) }}" class="card-link">
                <div class="card h-100 shadow-lg hover-zoom">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->jenisDok }}</h5>
                        <p class="card-text">{{ $item->judul }}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <small><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</small>
                        {{ $item->view_count }}x dilihat
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Peraturan Terupdate -->
    <hr class="my-4">
    <h2>Peraturan Terpopuler</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div></div>
        <select class="form-select w-auto">
            <option selected>Sebulan terakhir</option>
            <option value="1">Seminggu terakhir</option>
            <option value="2">Hari ini</option>
        </select>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">UU 7 TAHUN 2021</h5>
                    <p class="card-text">Harmonisasi Peraturan Perpajakan</p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <small><i class="fas fa-calendar-alt"></i> 29 Oktober 2021</small>
                    <small><i class="fas fa-eye"></i> 111.4 rb x dilihat</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">PMK 81 TAHUN 2024</h5>
                    <p class="card-text">Ketentuan Perpajakan dalam Rangka Pelaksanaan Sistem Inti Administrasi Perpajakan</p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <small><i class="fas fa-calendar-alt"></i> 14 Oktober 2024</small>
                    <small><i class="fas fa-eye"></i> 38 x dilihat</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">PMK 39 TAHUN 2024</h5>
                    <p class="card-text">Standar Biaya Masukan Tahun Anggaran 2025</p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <small><i class="fas fa-calendar-alt"></i> 31 Mei 2024</small>
                    <small><i class="fas fa-eye"></i> 2.1 rb x dilihat</small>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center my-4">
        <a href="{{ route('peraturan.index') }}" class="btn btn-primary">Lihat Selengkapnya</a>
    </div>
</div>
<!-- Peraturan Terupdate dan Terpopuler End -->

<!-- Infografis Berita -->
<div class="container-fluid ExploreTour py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Infografis Hukum</h5>
        </div>
        <div class="tab-class text-center">
            <div id="NationalTab-1" class="tab-pane fade show p-0 active">
                <div class="row g-4">
                    @foreach($infografis as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="national-item">
                            <!-- Menampilkan gambar infografis pertama -->
                            @if(is_string($item->gambar))
                            <img src="{{ asset('storage/' . $item->gambar) }}" class="img-fluid w-100 rounded" alt="Image">
                            @else
                            <img src="{{ asset('img/noimage.png') }}" class="img-fluid w-100 rounded" alt="No Image">
                            @endif
                            <div class="national-content">
                                <div class="national-info">
                                    <!-- Menampilkan judul infografis -->
                                    <h5 class="text-white text-uppercase mb-2">{{ $item->judul }}</h5>
                                    <a href="{{ route('infografis.show', $item->slug) }}" class="btn-hover text-white">Selengkapnya <i class="fa fa-arrow-right ms-2"></i></a>
                                </div>
                            </div>
                            <div class="national-plus-icon">
                                <a href="{{ route('infografis.show', $item->slug) }}" class="my-auto"><i class="fas fa-link fa-2x text-white"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Infografis Hukum End -->

<!-- Berita Hukum Start-->
<div class="container-fluid packages py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Berita Terbaru</h5>
        </div>
        <div class="packages-carousel owl-carousel">
            @foreach($berita as $item)
            <div class="packages-item">
                <div class="packages-img">
                    <img src="{{ asset('storage/'.$item->gambar) }}" class="img-fluid w-100 rounded-top" alt="Image">
                    <div class="packages-info d-flex border border-start-0 border-end-0 position-absolute"
                        style="width: 100%; bottom: 0; left: 0; z-index: 5;">
                        <small class="flex-fill text-center border-end py-2">{{ Str::limit(strip_tags($item->judul), 50) }}</small>
                    </div>
                    <div class="packages-price py-2 px-4">{{ $item->kategori }}</div>
                </div>
                <div class="packages-content bg-light">
                    <div class="p-4 pb-0">
                        <small><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</small>
                    </div>
                    <div class="p-4 pb-4 pt-4">
                        <small class="text-uppercase">{{ Str::limit(strip_tags($item->konten), 100) }}</small>
                    </div>
                    <div class="row bg-primary rounded-bottom mx-0">
                        <div class="col-6 text-start px-0">
                            <a href="{{ route('berita.show', $item->slug) }}" class="btn-hover btn text-white py-2 px-4">Read More</a>
                        </div>
                        <div class="col-6 text-end px-0">
                            <a href="#" class="btn-hover btn text-white py-2 px-4"><i class="fas fa-eye me-2"></i> {{ $item->view_count }} dilihat</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Berita Hukum  End -->

<!-- Garis Pemisah -->
<div class="separator-line my-0">
    <hr style="border: 0; height: 1px; background: rgba(0, 0, 0, 0.1);">
</div>

<!-- About Start -->
<div class="container-fluid about py-5">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="h-100"
                    style="border: 50px solid; border-color: transparent #13357B transparent #13357B;">
                    <img src="img/survey.webP" class="img-fluid w-100 h-100" alt="">
                </div>
            </div>
            <div class="col-lg-7"
                style="background: linear-gradient(rgba(255, 255, 255, .8), rgba(255, 255, 255, .8)), url(img/about-img-1.png);">
                <h5 class="section-about-title pe-3">survey</h5>
                <h1 class="mb-4">Penilaian Kepuasan Layanan</h1>
                <p class="mb-4">Hasil Survey Kepuasan Layanan Jaringan Dokumentasi dan Informasi Hukum.
                    Masukan anda sangat bermanfaat untuk kemajuan unit kami agar terus memperbaiki dan
                    meningkatkan kualitas pelayanan bagi masyarakat.</p>
                <div class="row gy-2 gx-4 mb-4">
                    <!-- Letak Chart dan Grafik Survey -->
                    <div class="row">
                        <!-- Bagian Penilaian Rata-Rata -->
                        <!-- Bagian Doughnut Chart -->
                        <div class="col-lg-4 text-center position-relative">
                            <canvas id="averageRatingChart" style="max-width: 250px;"></canvas>
                            <!-- Bagian Teks di Tengah -->
                            <div id="ratingDetails"
                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                <h3 style="margin: 0; font-size: 2rem; color: #13357B;">4.9</h3>
                                <!-- Rata-rata nilai -->
                                <p style="margin: 0; font-size: 0.9rem; color: #555;">774 Penilaian</p>
                                <!-- Jumlah pemberi survei -->
                                <div>
                                    <!-- Ikon Bintang -->
                                    <span style="color: #FFC107; font-size: 1.5rem;">&#9733;</span>
                                    <span style="color: #FFC107; font-size: 1.5rem;">&#9733;</span>
                                    <span style="color: #FFC107; font-size: 1.5rem;">&#9733;</span>
                                    <span style="color: #FFC107; font-size: 1.5rem;">&#9733;</span>
                                    <span style="color: #E0E0E0; font-size: 1.5rem;">&#9733;</span>
                                </div>
                            </div>
                        </div>

                        <!-- Grafik Batang Horizontal -->
                        <div class="col-lg-6">
                            <canvas id="ratingDistributionChart" style="max-width: 500px;"></canvas>
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary rounded-pill py-3 px-5 mt-2" href="">Ikuti Survey</a>
            </div>
        </div>
    </div>
</div>
<!-- About End -->

@endsection