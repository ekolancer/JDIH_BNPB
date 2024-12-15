@extends('layout.app')

@section('content')

<style>
    .card {
        height: 100%;
    }

    .card-body {
        flex: 1 1 auto;
    }

    .card-img-top {
        height: 150px;
        object-fit: cover;
    }

    .card h6 {
        font-size: 0.9rem;
        font-weight: 500;
        line-height: 1.4;
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
<div class="container-fluid ExploreTour py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Infografis Hukum</h5>
        </div>
        <div class="owl-carousel owl-theme">
            @foreach($infografis as $item)
            <div class="card infografis-card border-0 shadow">
                <div class="infografis-img-container position-relative">
                    <!-- Menampilkan gambar infografis -->
                    @if(!empty($item->gambar) && file_exists(public_path('storage/' . $item->gambar)))
                    <img src="{{ asset('storage/' . $item->gambar) }}" class="img-fluid w-100 rounded infografis-img" alt="Image">
                    @else
                    <img src="{{ asset('img/noimage.png') }}" class="img-fluid w-100 rounded infografis-img" alt="No Image">
                    @endif
                </div>
                <div class="card-body text-center">
                    <!-- Menampilkan judul infografis -->
                    <h6 class="text-primary text-uppercase mb-1">
                        <a href="{{ route('infografis.show', $item->slug) }}" class="fs-6 fw-lighter">
                            {{ Str::limit(strip_tags($item->judul), 40) }}
                        </a>
                    </h6>
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
<div class="container-fluid booking py-5">
    <h2 class="text-white text-center text-uppercase mb-5">Statistik Pengunjung</h2>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="p-4 bg-light rounded shadow text-center">
                    <h4 class="text-primary">Tahun Ini</h4>
                    <h2 class="mb-0">{{ $thisYearVisitors }}</h2>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="p-4 bg-light rounded shadow text-center">
                    <h4 class="text-primary">Bulan Ini</h4>
                    <h2 class="mb-0">{{ $thisMonthVisitors }}</h2>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="p-4 bg-light rounded shadow text-center">
                    <h4 class="text-primary">Kemarin</h4>
                    <h2 class="mb-0">{{ $yesterdayVisitors }}</h2>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="p-4 bg-light rounded shadow text-center">
                    <h4 class="text-primary">Hari Ini</h4>
                    <h2 class="mb-0">{{ $todayVisitors }}</h2>
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
                    <img src="img/survey.webP" class="img-fluid w-100 h-100" alt="">
                </div>
            </div>
            <div class="col-lg-7"
                style="background: linear-gradient(rgba(255, 255, 255, .8), rgba(255, 255, 255, .8));">
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
<!-- Survey End -->

@endsection