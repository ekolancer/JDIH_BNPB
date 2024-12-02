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
                <li class="breadcrumb-item"><a href="{{ url('/putusan') }}">Putusan</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $putusan->singkatan }} Nomor {{ $putusan->nomor }} Tahun {{ $putusan->tahun }}</li>
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
                    <h2>Detail Putusan</h2>
                    <div class="d-flex">
                        <span class="me-3"><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($putusan->created_at)->translatedFormat('d F Y') }}</span>
                        <span class="me-3"><i class="fas fa-eye"></i> {{ $putusan->view_count }}x dilihat </span>
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
                            <td>{{ $putusan->judul }}</td>
                        </tr>
                        <tr>
                            <th>Nomor</th>
                            <td>{{ $putusan->nomor }}</td>
                        </tr>
                        <tr>
                            <th>Tahun</th>
                            <td>{{ $putusan->tahunTerbit }}</td>
                        </tr>
                        <tr>
                            <th>Pengadilan</th>
                            <td>{{ $putusan->penerbit }}</td>
                        </tr>
                        <tr>
                            <th>Tempat Peradilan</th>
                            <td>{{ $putusan->tempatPenetapan }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Penetapan</th>
                            <td>{{ \Carbon\Carbon::parse($putusan->tglPenetapan)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Bidang Hukum</th>
                            <td>{{ $putusan->bidangHukum }}</td>
                        </tr>
                        <tr>
                            <th>Tajuk Entri Utama (TEU)</th>
                            <td>{{ $putusan->teuBadan }}</td>
                        </tr>
                        <tr>
                            <th>Bahasa</th>
                            <td>{{ $putusan->bahasa }}</td>
                        </tr>
                        <tr>
                            <th>Subjek</th>
                            <td>{{ $putusan->subjek }}</td>
                        </tr>
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
                <div class="header">JENIS PERADILAN</div>
                <div class="content">{{ $putusan->jenisDok }}</div>
            </div>
            <!-- Singaktan Jenis -->
            <div class="card-status">
                <div class="header">SINGKATAN</div>
                <div class="content">{{ $putusan->singkatan }}</div>
            </div>
            <!-- Status -->
            <div class="card-status">
                <div class="header">STATUS</div>
                <div class="content">{{ $putusan->status }}</div>
            </div>

            <!-- File Lampiran -->
            <div class="file-card">
                <h5>FILE LAMPIRAN</h5>
                <!-- Garis Pemisah di dalam file-card -->
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ asset('storage/' . $putusan->lampiran) }}" target="_blank">
                        {{ basename($putusan->lampiran) }}
                    </a>
                    <a href="{{ asset('storage/' . $putusan->lampiran) }}" target="_blank">
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