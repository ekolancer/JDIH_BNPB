@extends('layout.app-clear')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
                <div class="header">JENIS MONOGRAFI</div>
                <div class="content">{{ $monograf->jenisDok }}</div>
            </div>

            <!-- File Cover Buku -->
            <div class="file-card">
                <h5>Cover Buku</h5>
                <!-- Garis Pemisah di dalam file-card -->
                <hr>
                <div class="d-flex justify-content-center align-items-center">
                    @if($monograf->lampiran && Storage::exists('public/' . $monograf->lampiran))
                    <!-- Jika file lampiran ada di server -->
                    <a href="{{ asset('storage/' . $monograf->lampiran) }}" target="_blank">
                        <img src="{{ asset('storage/' . $monograf->lampiran) }}"
                            alt="Cover Buku"
                            style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    </a>
                    @else
                    <!-- Jika file tidak ada, tampilkan gambar default -->
                    <img src="{{ asset('img/noimage.png') }}"
                        alt="Gambar Tidak Tersedia"
                        style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection