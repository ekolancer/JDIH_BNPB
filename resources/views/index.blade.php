@extends('layout.app')

@section('content')
<!-- Section List Biru -->
<section class="file-title-section">
    <div class="container"></div>
</section>

<!-- Stats Section -->
<section id="stats" class="stats section light-background py-5">
    <div class="container">
        <div class="mx-auto text-center mb-4" style="max-width: 800px;">
            <p class="text-muted" data-aos="zoom-in" data-aos-duration="800">Produk Hukum</p>
            <h2 class="counters-title fw-bold" data-aos="zoom-in" data-aos-duration="800">Berdasarkan Kategori.</h2>
        </div>
        <div id="projectFacts" class="sectionClass">
            <div class="fullWidth eight columns">
                <div class="projectFactsWrap">
                    <!-- Peraturan -->
                    <div class="item wow fadeInUpBig animated animated" style="visibility: visible;">
                        <i class="fa fa-balance-scale mb-3"></i>
                        <p class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $data['jumlahDokumen']['Peraturan'] }}" data-purecounter-duration="1"></p>
                        <span></span>
                        <p>Peraturan</p>
                    </div>
                    <!-- Monografi -->
                    <div class="item wow fadeInUpBig animated animated" style="visibility: visible;">
                        <i class="fa fa-book-open mb-3"></i>
                        <p class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $data['jumlahDokumen']['Monografi'] }}" data-purecounter-duration="1"></p>
                        <span></span>
                        <p>Monografi</p>
                    </div>
                    <!-- Artikel -->
                    <div class="item wow fadeInUpBig animated animated" data-number="359" style="visibility: visible;">
                        <i class="fa fa-newspaper mb-3"></i>
                        <p class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $data['jumlahDokumen']['Artikel'] }}" data-purecounter-duration="1"></p>
                        <span></span>
                        <p>Artikel</p>
                    </div>
                    <!-- Putusan -->
                    <div class="item wow fadeInUpBig animated animated" data-number="246" style="visibility: visible;">
                        <i class="fa fa-gavel mb-3"></i>
                        <p class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $data['jumlahDokumen']['Putusan'] }}" data-purecounter-duration="1"></p>
                        <span></span>
                        <p>Putusan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Stats Section -->

<!-- Peraturan Terbaru -->
<div class="container my-5" data-aos="fade-up" data-aos-offset="300" data-aos-duration="2000">
    <!-- Header -->
    <!-- <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary">Highlight Peraturan</h4>
        <a href="{{ route('list-doc', ['tipeDok' => 'peraturan']) }}" class="btn btn-outline-primary btn-sm">Lihat Semua Peraturan</a>
    </div> -->

    <div class="highlight-section py-5">
        <div class="container text-center mb-5">
            <h2 class="fw-bold text-dark mb-2">Highlight Peraturan</h2>
            <div class="header-line mx-auto"></div>
        </div>

        <div class="container">
            <div class="d-flex justify-content-center mb-5">
                <ul class="nav nav-pills custom-pills p-1 bg-light rounded-pill shadow-sm" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#brs">
                            <i class="fas fa-bolt me-2"></i>Terkini
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tabel">
                            <i class="fas fa-fire me-2"></i>Populer
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content mt-2" id="pills-tabContent">
                <div class="tab-pane fade show active" id="brs" role="tabpanel">
                    <div class="row g-4 pt-3"> @foreach ($data['peraturanTerbaru'] as $peraturan)
                        @include('partials.peraturan-card', ['item' => $peraturan])
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="tabel" role="tabpanel">
                    <div class="row g-4 pt-3">
                        @foreach ($data['peraturanPopuler'] as $peraturan)
                        @include('partials.peraturan-card', ['item' => $peraturan])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Infografis Berita -->
<section id="stats" class="stats section light-background py-5">
    <div class="container-fluid pt-5 pb-1">
        <div class="container">
            <div class="mx-auto text-center mb-4" style="max-width: 800px;">
                <p class="text-muted" data-aos="zoom-in" data-aos-offset="300" data-aos-duration="500">Informasi Hukum</p>
                <h2 class="counters-title fw-bold" data-aos="zoom-in" data-aos-duration="800">Infografis.</h2>
            </div>
            <!-- Swiper Container -->
            <div class="swiper-container position-relative" data-aos="fade-right" data-aos-offset="100" data-aos-easing="ease-in-sine" data-aos-duration="500">
                <!-- Navigation Arrows Above -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-wrapper">
                    @foreach($data['infografis'] as $item)
                    <div class="swiper-slide">
                        <div class="card border-0">
                            <div class="position-relative">
                                <!-- Gambar dengan hyperlink -->
                                @if(!empty($item->gambar) && file_exists(public_path('storage/' . $item->gambar)))
                                <a href="{{ route('infografis.show', $item->slug) }}"> <!-- Ubah dengan URL tujuan -->
                                    <img src="{{ asset('storage/' . $item->gambar) }}" class="img-fluid w-100 rounded" alt="Image">
                                </a>
                                @else
                                <img src="{{ asset('img/noimage.png') }}" class="img-fluid w-100 rounded" alt="No Image">
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Infografis Hukum End -->

    <!-- Berita Hukum Start-->
    <div class="container-fluid packages pt-5">
        <div class="container">
            <div class="mx-auto text-center mb-4" style="max-width: 800px;">
                <p class="text-muted" data-aos="zoom-in" data-aos-offset="300" data-aos-duration="500">Informasi Hukum</p>
                <h2 class="counters-title fw-bold" data-aos="zoom-in" data-aos-duration="800">Berita.</h2>
            </div>
            <div class="packages-carousel owl-carousel shadow-lg" data-aos="fade-left" data-aos-offset="100" data-aos-easing="ease-in-sine" data-aos-duration="500">
                @foreach($data['berita'] as $item)
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
                            <small><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d F Y') }}</small>
                        </div>
                        <div class="p-4 pb-4 pt-4">
                            <small class="text-uppercase">{{ Str::limit(strip_tags($item->konten), 100) }}</small>
                        </div>
                        <div class="row bg-primary rounded-bottom mx-0">
                            <div class="col-6 text-start px-0">
                                <a href="{{ route('berita.show', $item->slug) }}" class="btn-hover btn text-white py-2 px-4">Read More</a>
                            </div>
                            <div class="col-6 text-end px-0">
                                <a class="btn-hover btn text-white py-2 px-4"><i class="fas fa-eye me-2"></i> {{ $item->view_count }} dilihat</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- Berita Hukum  End -->

<!-- Garis Pemisah -->
<div class="container-fluid booking py-5">
    <div class="container" data-aos="zoom-in" data-aos-offset="300" data-aos-duration="800">
        <h2 class="counters-title pt-5">Statistik Pengunjung</h2>
        <div class="counters">
            <div class="row justify-content-center align-items-center">
                <!-- Statistik -->
                <div class="col-md-8">
                    <div class="row gy-4">
                        <div class="col-sm-6 col-lg-6">
                            <div class="counter-item">
                                <i class="fas fa-calendar-day"></i>
                                <div>
                                    <h3>{{ $data['visitorStats']['today'] }}</h3>
                                    <p class="counter-title">Pengunjung Hari ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="counter-item">
                                <i class="fas fa-calendar-minus"></i>
                                <div>
                                    <h3>{{ $data['visitorStats']['week'] }}</h3>
                                    <p class="counter-title">Pengunjung Minggu ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="counter-item">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <h3>{{ $data['visitorStats']['month'] }}</h3>
                                    <p class="counter-title">Pengunjung Bulan ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="counter-item">
                                <i class="fas fa-chart-line"></i>
                                <div>
                                    <h3>{{ $data['visitorStats']['year'] }}</h3>
                                    <p class="counter-title">Pengunjung Tahun ini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Chart -->
                <div class="col-md-4 text-center">
                    <img alt="Statistic Chart" src="{{ asset('img/statistik-doc.png') }}" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Survey Start -->
<div class="container-fluid about py-5">
    <div class="container pb-5" data-aos="fade-up" data-aos-offset="300" data-aos-duration="800">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="h-100"
                    style="border: 50px solid; border-color: transparent #13357B transparent #13357B;">
                    <img src="{{ asset('img/survey.webp') }}" class="img-fluid w-100 h-100" alt="">
                </div>
            </div>
            <div class="col-lg-7">
                <h5 class="section-about-title pe-3">Survey</h5>
                <h1 class="mb-4">Penilaian Kepuasan Layanan</h1>
                <p class="mb-4">Hasil Survey Kepuasan Layanan Jaringan Dokumentasi dan Informasi Hukum.
                    Masukan anda sangat bermanfaat untuk kemajuan unit kami agar terus memperbaiki dan
                    meningkatkan kualitas pelayanan bagi masyarakat.</p>
                <div class="row gy-2 gx-4 mb-4">
                    <div class="col-md-4 col-sm-6 mx-auto text-center position-relative">
                        <div class="circle-container">
                            <canvas id="averageRatingChart"></canvas>
                            <div class="rating-details text-center">
                                <h3 id="averageRating">0.0</h3>
                                <p id="totalRatings">0 Penilaian</p>
                                <div id="starRating">
                                    <!-- Bintang akan di-generate menggunakan JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <canvas id="ratingDistributionChart"></canvas>
                    </div>
                </div>
                <a class="btn btn-primary rounded-pill py-3 px-5 mt-2 ms-5" href="#" data-bs-toggle="modal" data-bs-target="#surveyModal">
                    Berikan Penilaian
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Survey End -->

<!-- Modal -->
<div class="modal fade" id="surveyModal" tabindex="-1" aria-labelledby="surveyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="surveyModalLabel">Survey Kepuasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="surveyForm" method="POST" action="{{ route('survey.store') }}" onsubmit="submitSurveyForm(event)">
                @csrf
                <div class="modal-body text-center">
                    <img src="{{ asset('img/rating.png') }}" alt="Survey Icon" class="mb-3" style="width: 100px;">
                    <p>Terima kasih telah mengikuti survey kepuasan layanan kami.</p>

                    <!-- Rating Bintang -->
                    <div class="mb-3">
                        <label for="rate" class="form-label">Berikan Penilaian Anda</label>
                        <div class="rate" id="rate">
                            <span class="star" data-value="1">&#9733;</span>
                            <span class="star" data-value="2">&#9733;</span>
                            <span class="star" data-value="3">&#9733;</span>
                            <span class="star" data-value="4">&#9733;</span>
                            <span class="star" data-value="5">&#9733;</span>
                        </div>
                        <input type="hidden" name="rating" id="rateInput" value="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection