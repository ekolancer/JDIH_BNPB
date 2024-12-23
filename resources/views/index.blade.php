@extends('layout.app')

@section('content')

<!-- Stats Section -->
<section id="stats" class="stats section light-background py-5">
    <div class="container">
        <div class="mx-auto text-center mb-4" style="max-width: 800px;">
            <p class="text-muted">Produk Hukum</p>
            <h2 class="counters-title fw-bold">Berdasarkan Kategori.</h2>
        </div>
        <div class="row gy-4 text-center">
            <!-- Peraturan -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-item bg-gradient-primary shadow-sm">
                    <i class="fa fa-balance-scale mb-3"></i>
                    <h3 class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $data['peraturan'] }}" data-purecounter-duration="1"></h3>
                    <p class="feature-title">Peraturan Perundang-Undangan</p>
                </div>
            </div>
            <!-- Monografi Hukum -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-item bg-gradient-secondary shadow-sm">
                    <i class="fa fa-book-open mb-3"></i>
                    <h3 class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $data['monografi'] }}" data-purecounter-duration="1"></h3>
                    <p class="feature-title">Monografi Hukum</p>
                </div>
            </div>
            <!-- Artikel Hukum -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-item bg-gradient-tertiary shadow-sm">
                    <i class="fa fa-newspaper mb-3"></i>
                    <h3 class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $data['artikel'] }}" data-purecounter-duration="1"></h3>
                    <p class="feature-title">Artikel Hukum</p>
                </div>
            </div>
            <!-- Putusan Hukum -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-item bg-gradient-quaternary shadow-sm">
                    <i class="fa fa-gavel mb-3"></i>
                    <h3 class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $data['putusan'] }}" data-purecounter-duration="1"></h3>
                    <p class="feature-title">Putusan Hukum</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Stats Section -->

<!-- Peraturan Terbaru -->
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Highlight Peraturan</h4>
        <a href="{{ route('peraturan.index') }}" class="text-primary text-decoration-none">Lihat Semua Peraturan</a>
    </div>
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#brs"><i class="fas fa-sync-alt me-2"></i>Terkini</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabel"><i class="far fa-star me-1"></i>Populer</button>
        </li>
    </ul>
    <div class="tab-content">
        <!-- Tab Terkini -->
        <div class="tab-pane fade show active" id="brs">
            <div class="row g-3">
                @foreach ($peraturanTerbaru as $peraturan)
                <div class="col-md-6 col-lg-3">
                    <!-- Card Dokumen -->
                    <div class="card shadow-lg border-0 zoom-card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Kolom Ikon File -->
                                <div class="align-items-center pb-3 mt-0 me-2">
                                    <img src="/img/icondoc.png" alt="" height="50px" width="50px">
                                </div>
                                <!-- Kolom Tipe Dokumen dan Judul -->
                                <div class="d-flex flex-column w-100">
                                    <div class="card-title text-primary fw-bold mb-0">
                                        <small>{{ $peraturan->singkatan }} Nomor {{ $peraturan->nomor }} Tahun {{ $peraturan->tahunTerbit }}</small>
                                    </div>
                                    <p class="card-text mb-3 text-muted" style="font-size: small;">
                                        <small>{{ $peraturan->judul ?? 'Tidak ada deskripsi.' }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="card-footer bg-light border-top">
                            <div class="d-flex justify-content-between hero-items-center">
                                <!-- Informasi tambahan -->
                                <div>
                                    <small class="text-muted me-3">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($peraturan->created_at)->format('d F Y') }}
                                    </small>
                                </div>
                                <!-- Tombol aksi -->
                                <div class="d-flex">
                                    <a href="{{ route('peraturan.show', $peraturan->slug) }}" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Tab Populer -->
        <div class="tab-pane fade" id="tabel">
            <div class="row g-3">
                @foreach ($peraturanPopuler as $peraturan)
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-lg border-0 zoom-card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Kolom Ikon File -->
                                <div class="align-items-center pb-3 mt-0 me-2">
                                    <img src="/img/icondoc.png" alt="" height="50px" width="50px">
                                </div>
                                <!-- Kolom Tipe Dokumen dan Judul -->
                                <div class="d-flex flex-column w-100">
                                    <div class="card-title text-primary fw-bold mb-0">
                                        <small>{{ $peraturan->singkatan }} Nomor {{ $peraturan->nomor }} Tahun {{ $peraturan->tahunTerbit }}</small>
                                    </div>
                                    <p class="card-text mb-3 text-muted" style="font-size: small;">
                                        <small>{{ $peraturan->judul ?? 'Tidak ada deskripsi.' }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="card-footer bg-light border-top">
                            <div class="d-flex justify-content-between hero-items-center">
                                <!-- Informasi tambahan -->
                                <div>
                                    <small class="text-muted me-3">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($peraturan->created_at)->format('d F Y') }}
                                    </small>
                                </div>
                                <!-- Tombol aksi -->
                                <div class="d-flex">
                                    <a href="{{ route('peraturan.show', $peraturan->slug) }}" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Infografis Berita -->
<div class="container-fluid ExploreTour pt-5 pb-1">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Infografis Hukum</h5>
        </div>
        <div class="owl-carousel owl-theme">
            @foreach($infografis as $item)
            <div class="card infografis-card border-0">
                <div class="infografis-img-container position-relative">
                    <!-- Menampilkan gambar infografis -->
                    @if(!empty($item->gambar) && file_exists(public_path('storage/' . $item->gambar)))
                    <img src="{{ asset('storage/' . $item->gambar) }}" class="img-fluid w-100 rounded infografis-img" alt="Image">
                    @else
                    <img src="{{ asset('img/noimage.png') }}" class="img-fluid w-100 rounded infografis-img" alt="No Image">
                    @endif
                </div>
            </div>
            @endforeach
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
        <div class="packages-carousel owl-carousel shadow-lg">
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
<div class="container-fluid booking py-5">
    <div class="container">
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
                                    <h3>{{ $todayVisitors }}</h3>
                                    <p class="counter-title">Pengunjung Hari ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="counter-item">
                                <i class="fas fa-calendar-minus"></i>
                                <div>
                                    <h3>{{ $yesterdayVisitors }}</h3>
                                    <p class="counter-title">Pengunjung Kemarin</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="counter-item">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <h3>{{ $thisMonthVisitors }}</h3>
                                    <p class="counter-title">Pengunjung Bulan ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="counter-item">
                                <i class="fas fa-chart-line"></i>
                                <div>
                                    <h3>{{ $thisYearVisitors }}</h3>
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
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="h-100"
                    style="border: 50px solid; border-color: transparent #13357B transparent #13357B;">
                    <img src="{{ asset('img/survey.webP') }}" class="img-fluid w-100 h-100" alt="">
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
                            <div class="rating-details">
                                <h3>4.9</h3>
                                <p>774 Penilaian</p>
                                <div>
                                    <span>&#9733;</span>
                                    <span>&#9733;</span>
                                    <span>&#9733;</span>
                                    <span>&#9733;</span>
                                    <span>&#9734;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <canvas id="ratingDistributionChart"></canvas>
                    </div>
                </div>
                <a class="btn btn-primary rounded-pill py-3 px-5 mt-2 ms-5" href="#">Ikuti Survey</a>
            </div>
        </div>
    </div>
</div>

<!-- Survey End -->

@endsection