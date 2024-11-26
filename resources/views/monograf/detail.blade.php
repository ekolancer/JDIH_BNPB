@extends('layout.app-clear')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa;
        padding-top: 20px;
        padding-bottom: 40px;
        /* Memberikan jarak dengan footer */
    }

    .container-detail {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 30px;
        /* Jarak kontainer dengan elemen lainnya */
    }

    /* ******Style Table****** */
    .table-striped th:not(.table-primary) {
        font-weight: normal;
        /* Non-bold selain header judul */
        color: gray;
    }

    .table-striped th,
    .table-striped td {
        padding: 8px;
        /* Memberikan padding yang nyaman untuk kolom */
        font-size: 16px;
        /* Ukuran font yang lebih kecil */
    }

    .table-striped th:nth-child(1),
    .table-striped td:nth-child(1) {
        width: 40%;
        /* Lebar kolom pertama 30% */
    }

    .table-striped th:nth-child(2),
    .table-striped td:nth-child(2) {
        width: 60%;
        /* Lebar kolom kedua 70% */
    }

    /* ******Card Keterangan Kanan****** */
    .file-card {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        padding: 15px;
        margin-bottom: 20px;
        text-align: center;
    }

    .file-card h5 {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .file-card a {
        text-decoration: none;
        color: #007bff;
        font-size: 14px;
    }

    .file-card a:hover {
        text-decoration: underline;
    }

    .file-card span {
        font-size: 12px;
        color: #6c757d;
    }

    .file-card .icon-download {
        color: #007bff;
        margin-left: 5px;
    }

    .card-status {
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 8px;
        text-align: center;
        padding: 10px;
        margin: 20px auto;
        margin-top: 0;
    }

    .card-status .header {
        font-size: 14px;
        color: #333;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .card-status .content {
        background-color: #d0e4ff;
        color: #0044cc;
        padding: 10px;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
    }

    /* ******Button****** */
    .btn-back {
        margin-top: 20px;
    }

    /* ******Breadcrumb****** */
    .file-title-section {
        background-color: #818181;
        padding: 2px 0;
        text-align: center;
        margin-bottom: 30px;
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
        font-size: 18px;
    }

    .breadcrumb-item a {
        color: white;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: white;
        font-weight: bold;
    }
</style>

<!-- Section Judul File dan Breadcrumb -->
<section class="file-title-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/monograf') }}">Monograf</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $monograf->judul }}</li>
            </ol>
        </nav>
    </div>
</section>

<div class="container">
    <div class="row">
        <!-- Detail Container -->
        <div class="col-md-8">
            <div class="container-detail shadow-lg">
                <div class="title-section">
                    <h2>Detail Monograf</h2>
                    <div class="d-flex">
                        <span class="me-3"><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($monograf->created_at)->translatedFormat('d F Y') }}</span>
                        <span class="me-3"><i class="fas fa-eye"></i> 12321 dilihat</span>
                        <span class="me-3"><i class="fas fa-download"></i> 2912 diunduh</span>
                    </div>
                </div>
                <hr>
                <table class="table table-striped">
                    <tbody>
                        <!-- Judul: Meta -->
                        <tr>
                            <th class="table-primary">Meta</th>
                            <th class="table-primary">Keterangan</th>
                        </tr>
                        <tr>
                            <th>Judul Monografi</th>
                            <td>{{ $monograf->jenisDok }}</td>
                        </tr>
                        <tr>
                            <th>Judul</th>
                            <td>{{ $monograf->judul }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Terbit</th>
                            <td>{{ $monograf->tahunTerbit }}</td>
                        </tr>
                        <tr>
                            <th>Cetakan / Edisi</th>
                            <td>{{ $monograf->cetakanEdisi }}</td>
                        </tr>
                        <tr>
                            <th>Tempat Terbit</th>
                            <td>{{ $monograf->tempatPenetapan }}</td>
                        </tr>
                        <tr>
                            <th>Penerbit</th>
                            <td>{{ $monograf->penerbit }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi Fisik</th>
                            <td>{{ $monograf->deskripsiFisik }}</td>
                        </tr>
                        <tr>
                            <th>Bidang Hukum</th>
                            <td>{{ $monograf->bidangHukum }}</td>
                        </tr>
                        <tr>
                            <th>ISBN / ISSN</th>
                            <td>{{ $monograf->isbn }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Panggil</th>
                            <td>{{ $monograf->nomorPanggil }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Induk Buku</th>
                            <td>{{ $monograf->nomorIndukBuku }}</td>
                        </tr>
                        <tr>
                            <th>Bahasa</th>
                            <td>{{ $monograf->bahasa }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi Buku</th>
                            <td>{{ $monograf->lokasi }}</td>
                        </tr>
                        <tr>
                            <th>Tajuk Entri Utama (T.E.U)</th>
                            <td>{{ $monograf->teuBadan }}</td>
                        </tr>
                        <tr>
                            <th>Subjek</th>
                            <td>{{ $monograf->subjek }}</td>
                        </tr>
                        <tr>
                    </tbody>
                </table>

                <!-- Tombol Kembali -->
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-back">Kembali</a>
            </div>
        </div>

        <!-- File Container -->
        <div class="col-md-3">
            <!-- Jenis Dokumen -->
            <div class="card-status">
                <div class="header">JENIS DOKUMEN</div>
                <div class="content">{{ $monograf->jenisDok }}</div>
            </div>

            <!-- File Cover Buku -->
            <div class="file-card">
                <h5>Cover Buku</h5>
                <!-- Garis Pemisah di dalam file-card -->
                <hr>
                <div class="d-flex justify-content-center align-items-center">
                    <!-- Menampilkan Gambar -->
                    <a href="{{ asset('storage/' . $monograf->lampiran) }}" target="_blank">
                        <img src="{{ asset('storage/' . $monograf->lampiran) }}"
                            alt="Cover Buku"
                            style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection