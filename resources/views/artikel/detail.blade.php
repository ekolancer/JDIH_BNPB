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
                <li class="breadcrumb-item"><a href="{{ url('/artikel') }}">Artikel</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $artikel->judul }}</li>
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
                    <h2>Detail Artikel</h2>
                    <div class="d-flex">
                        <span class="me-3"><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($artikel->created_at)->translatedFormat('d F Y') }}</span>
                        <span class="me-3"><i class="fas fa-eye"></i> {{ $artikel->view_count }}x dilihat</span>
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
                            <th>Judul</th>
                            <td>{{ $artikel->judul }}</td>
                        </tr>
                        <tr>
                            <th>Tempat Terbit</th>
                            <td>{{ $artikel->tempatPenetapan }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Terbit</th>
                            <td>{{ $artikel->tahunTerbit }}</td>
                        </tr>
                        <tr>
                            <th>Penerbit</th>
                            <td>{{ $artikel->penerbit }}</td>
                        </tr>
                        <tr>
                            <th>Bahasa</th>
                            <td>{{ $artikel->bahasa }}</td>
                        </tr>
                        <tr>
                            <th>Sumber</th>
                            <td>{{ $artikel->sumber }}</td>
                        </tr>
                        <tr>
                            <th>Bidang Hukum</th>
                            <td>{{ $artikel->bidangHukum }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $artikel->lokasi }}</td>
                        </tr>
                        <tr>
                            <th>Bahasa</th>
                            <td>{{ $artikel->bahasa }}</td>
                        </tr>
                        <tr>
                            <th>Tajuk Entri Utama (T.E.U)</th>
                            <td>{{ $artikel->teuBadan }}</td>
                        </tr>
                        <tr>
                            <th>Subjek</th>
                            <td>{{ $artikel->subjek }}</td>
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
            
            <!-- File Lampiran -->
            <div class="file-card">
                <h5>File artikel</h5>
                <!-- Garis Pemisah di dalam file-card -->
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ asset('storage/' . $artikel->lampiran) }}" target="_blank">
                        {{ basename($artikel->lampiran) }}
                    </a>
                    <a href="{{ asset('storage/' . $artikel->lampiran) }}" target="_blank">
                        @if($fileSizeInKb < 1000)
                            <span>{{ number_format($fileSizeInKb, 2) }} KB</span>
                            @else
                            <span>{{ number_format($fileSizeInMb, 2) }} MB</span>
                            @endif
                            <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection