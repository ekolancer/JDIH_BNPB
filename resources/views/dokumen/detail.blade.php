@extends('layout.app-clear')

@section('content')
<div class="container mt-5">
    <div class="row align-items-start">
        <!-- Detail Container -->
        <div class="col-md-8">
            <div class="container-detail shadow-lg p-4 bg-white rounded">
                <div class="title-section mb-3">
                    <h2 class="fw-bold text-primary">
                        @if(in_array($dokumen->tipeDok, ['Peraturan', 'Putusan']))
                            {{ $dokumen->singkatan }} Nomor {{ $dokumen->nomor }} Tahun {{ $dokumen->tahun }}
                        @else
                            {{ $dokumen->judul }}
                        @endif
                    </h2>
                    <div class="d-flex text-muted">
                        <span class="me-3"><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($dokumen->created_at)->translatedFormat('d F Y') }}</span>
                        <span class="me-3"><i class="fas fa-eye"></i> {{ $dokumen->view_count }}x dilihat</span>
                        <span class="me-3"><i class="fas fa-download"></i> {{ $dokumen->download_count }}x diunduh</span>
                    </div>
                </div>
                <hr>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th class="table-primary">Metadata</th>
                            <th class="table-primary">Keterangan</th>
                        </tr>
                        @foreach($metadata[$dokumen->tipeDok] as $key => $value)
                        <tr>
                            <th>{{ $key }}</th>
                            <td>
                                @if($key == 'Subjek' && !empty($value))
                                @foreach(explode(' ', $value) as $word)
                                <span class="badge bg-primary">{{ $word }}</span>
                                @endforeach
                                @else
                                {{ $value }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between">
                    <!-- Tombol Kembali -->
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>

                    <!-- Tombol Baca -->
                    <button class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#lampiranModal">Baca Lampiran</button>
                </div>
            </div>
        </div>

        <!-- File Container -->
        <div class="col-md-3">
            <!-- Jenis Dokumen -->
            <div class="card-status">
                <div class="header">JENIS DOKUMEN</div>
                <hr>
                <div class="content text-uppercase">{{ $dokumen->jenisDok }}</div>
            </div>

            @if($dokumen->tipeDok !== 'Monografi' && $dokumen->tipeDok !== 'Artikel')
            <!-- Singkatan -->
            <div class="card-status">
                <div class="header">SINGKATAN</div>
                <hr>
                <div class="content text-uppercase">{{ $dokumen->singkatan }}</div>
            </div>
            <!-- Status -->
            <div class="card-status">
                <div class="header">STATUS</div>
                <hr>
                <div class="{{ $dokumen->status === 'TIDAK BERLAKU' ? 'content-negatif' : 'content' }}">
                    {{ $dokumen->status }}
                </div>
            </div>

            <div class="file-card">
                <div class="header">LAMPIRAN</div>
                <hr>
                <div class="d-flex">
                    <!-- Lampiran Full Text -->
                    <div class="file-info flex-grow-1 me-3">
                        <a href="#" target="_blank" class="btn btn-primary text-white text-uppercase d-flex flex-column align-items-center">
                            Full Text
                            <span class="mt-1 text-black">
                                @if($fileSizeInKb < 1000)
                                    {{ number_format($fileSizeInKb, 2) }} KB
                                    @else
                                    {{ number_format($fileSizeInMb, 2) }} MB
                                    @endif
                                    </span>
                        </a>
                    </div>

                    <!-- Lampiran Abstrak -->
                    <div class="file-info flex-grow-1">
                        <a href="#" target="_blank" class="btn btn-primary text-white text-uppercase d-flex flex-column align-items-center">
                            Abstrak
                            <span class="mt-1 text-black">
                                @if($fileSizeInKb < 1000)
                                    {{ number_format($fileSizeInKb, 2) }} KB
                                    @else
                                    {{ number_format($fileSizeInMb, 2) }} MB
                                    @endif
                                    </span>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if($dokumen->tipeDok === 'Monografi')
            <div class="file-card">
                <div class="header">COVER BUKU</div>
                <hr>
                <div class="d-flex justify-content-center align-items-center">
                    @php
                    $fileExists = $dokumen->lampiran && file_exists(public_path('storage/' . $dokumen->lampiran));
                    $imagePath = $fileExists ? Storage::url($dokumen->lampiran) : asset('img/noimage.png');
                    @endphp
                    <img src="{{ $imagePath }}" alt="Cover Buku" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection