@extends('layout.app-clear')

@section('content')

<div class="container mt-3">
    <div class="row">
        <!-- Detail Container -->
        <div class="col-md-8">
            <div class="container-detail shadow-lg">
                <div class="title-section">
                    <h2>Detail Peraturan</h2>
                    <div class="d-flex">
                        <span class="me-3"><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($peraturan->created_at)->translatedFormat('d F Y') }}</span>
                        <span class="me-3"><i class="fas fa-eye"></i> {{ $peraturan->view_count }}x dilihat</span>
                        <span class="me-3"><i class="fas fa-download"></i> {{ $peraturan->download_count }}x diunduh</span>
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
                            <th>Tipe Dokumen</th>
                            <td>{{ $peraturan->tipeDok }}</td>
                        </tr>
                        <tr>
                            <th>Judul</th>
                            <td>{{ $peraturan->judul }}</td>
                        </tr>
                        <tr>
                            <th>Tajuk Entri Utama (TEU)</th>
                            <td>{{ $peraturan->teuBadan }}</td>
                        </tr>
                        <tr>
                            <th>Tempat Penetapan</th>
                            <td>{{ $peraturan->tempatPenetapan }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Terbit</th>
                            <td>{{ $peraturan->tahunTerbit }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Penetapan</th>
                            <td>{{ \Carbon\Carbon::parse($peraturan->tglPenetapan)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengundangan</th>
                            <td>{{ \Carbon\Carbon::parse($peraturan->tglPengundangan)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Sumber</th>
                            <td>{{ $peraturan->sumber }}</td>
                        </tr>
                        <tr>
                            <th>Subjek</th>
                            <td>{{ $peraturan->subjek }}</td>
                        </tr>
                        <tr>
                            <th>Bidang Hukum</th>
                            <td>{{ $peraturan->bidangHukum }}</td>
                        </tr>
                        <tr>
                            <th>Bahasa</th>
                            <td>{{ $peraturan->bahasa }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $peraturan->lokasi }}</td>
                        </tr>
                        <tr>
                            <th>Urusan Pemerintahan</th>
                            <td>{{ $peraturan->urusanPemerintahan }}</td>
                        </tr>
                        <tr>
                            <th>Pemrakarsa</th>
                            <td>{{ $peraturan->pemrakarsa }}</td>
                        </tr>
                        <tr>
                            <th>Penandatangan</th>
                            <td>{{ $peraturan->penandatangan }}</td>
                        </tr>
                        <tr>
                            <th>Peraturan Terkait</th>
                            <td>{{ $peraturan->peraturanTerkait }}</td>
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
                <div class="header">JENIS DOKUMEN</div>
                <div class="content">{{ $peraturan->jenisDok }}</div>
            </div>
            <!-- Singaktan Jenis -->
            <div class="card-status">
                <div class="header">SINGKATAN</div>
                <div class="content">{{ $peraturan->singkatan }}</div>
            </div>
            <!-- Status -->
            <div class="card-status">
                <div class="header">STATUS</div>
                <div class="content">{{ $peraturan->status }}</div>
            </div>

            <!-- File Lampiran -->
            <div class="file-card">
                <h5>FILE LAMPIRAN</h5>
                <!-- Garis Pemisah di dalam file-card -->
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('file.download', $peraturan->lampiran) }}" target="_blank">
                        {{ basename($peraturan->lampiran) }}
                    </a>
                    <a href="{{ asset('storage/' . $peraturan->lampiran) }}" target="_blank">
                        @if($fileSizeInKb < 1000)
                            <span>{{ number_format($fileSizeInKb, 2) }} KB</span>
                            @else
                            <span>{{ number_format($fileSizeInMb, 2) }} MB</span>
                            @endif
                            <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>

            <div class="file-card">
                <h5>FILE ABSTRAK</h5>
                <!-- Garis Pemisah di dalam file-card -->
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    Tidak Tersedia
                    <a href="{{ asset('storage/' . $peraturan->lampiran) }}" target="_blank">
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