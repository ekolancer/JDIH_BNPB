@extends('layout.app-clear')

@section('content')

<!-- Kontainer Detail Dokumen -->
<div class="container mt-5">
    <div class="row align-items-start">
        <!-- Detail Container -->
        <div class="col-md-8">
            <div class="container-detail shadow-lg p-4 bg-white rounded">
                <div class="title-section mb-3">
                    <h2 class="fw-bold text-primary">
                        <i class="fas fa-list me-2"></i>
                        @if(in_array($dokumen->tipeDok, ['Peraturan', 'Putusan']))
                        {{ $dokumen->singkatan }} Nomor {{ $dokumen->nomor }} Tahun {{ $dokumen->tahun }}
                        @else
                        {{ $dokumen->judul }}
                        @endif
                    </h2>
                    <hr>
                    <!-- sekilas info -->
                    <div class="d-flex text-muted justify-content mt-2">
                        <!-- Created At -->
                        <span class="bg-info text-dark px-3 py-2 d-inline-block">
                            <i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($dokumen->created_at)->translatedFormat('d F Y') }}
                        </span>

                        <!-- View Count -->
                        <span class="bg-success text-white px-3 py-2 d-inline-block">
                            <i class="fas fa-eye me-1"></i> {{ $dokumen->view_count }} Dilihat
                        </span>

                        <!-- Download Count -->
                        <span class="bg-warning text-dark px-3 py-2 d-inline-block">
                            <i class="fas fa-download me-1"></i> {{ $dokumen->download_count }} Diunduh
                        </span>

                        <!-- Tombol Baca -->
                        @if($dokumen->tipeDok !== 'Artikel')
                        <span class="ms-auto bg-primary text-white px-3 py-2 d-inline-block">
                            <a href="#" class="text-white text-decoration-none" data-bs-toggle="modal" data-bs-target="#lampiranModal">
                                <i class="fas fa-book-open-reader me-1"></i> {{ $buttonText }}
                            </a>
                        </span>
                        @endif
                    </div>

                </div>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th class="table-primary">Metadata</th>
                            <th class="table-primary">Keterangan</th>
                        </tr>
                        @foreach($metadata as $key => $value)
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
                <div class="d-flex justify-content">
                    <!-- Tombol Kembali -->
                    <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Kembali</a>

                    <!-- Menampilkan Komponen Livewire -->
                    @livewire('media-file', ['dokumenId' => $dokumen->id])

                </div>
            </div>
        </div>

        <!-- File Container -->
        <div class="col-md-3">
            <!-- Jenis Dokumen -->
            <div class="card-status">
                <div class="header text-uppercase">Tipe Dokumen</div>
                <hr style="margin-top: 0.5rem; margin-bottom: 0.5rem; height: 2px;">
                <div class="content text-uppercase">{{ $dokumen->tipeDok }}</div>
            </div>

            @if($dokumen->tipeDok !== 'Monografi' && $dokumen->tipeDok !== 'Artikel')
            <!-- Singkatan -->
            <div class="card-status">
                <div class="header text-uppercase">Singkatan</div>
                <hr style="margin-top: 0.5rem; margin-bottom: 0.5rem; height: 2px;">
                <div class="content text-uppercase">{{ $dokumen->singkatan }}</div>
            </div>
            <!-- Status -->
            <div class="card-status">
                <div class="header text-uppercase">Status</div>
                <hr style="margin-top: 0.5rem; margin-bottom: 0.5rem; height: 2px;">
                <div class="{{ $dokumen->status === 'TIDAK BERLAKU' ? 'content-negatif' : 'content' }}">
                    {{ $dokumen->status }}
                </div>
            </div>

            <div class="file-card">
                <div class="header text-uppercase">Lampiran</div>
                <hr style="margin-top: 0.5rem; margin-bottom: 0.5rem; height: 2px;">
                <div class="d-flex">
                    <!-- Lampiran Full Text -->
                    <div class="file-info flex-grow-1 me-3">
                        <a href="{{ route('dokumen.unduhLampiran', $dokumen->id) }}" target="_self" class="btn btn-primary text-white text-uppercase d-flex flex-column align-items-center" style="text-decoration: none;">
                            Full Text
                            <span class="mt-1 text-black">
                                @if($lampiranSizeInKb < 1000)
                                    {{ number_format($lampiranSizeInKb) }} KB
                                    @else
                                    {{ number_format($lampiranSizeInMb, 2) }} MB
                                    @endif
                                    </span>
                        </a>
                    </div>

                    <!-- Lampiran Abstrak -->
                    <!-- <div class="file-info flex-grow-1">
                        <a href="{{ route('dokumen.unduhAbstrak', $dokumen->id) }}" target="_self" class="btn btn-primary text-white text-uppercase d-flex flex-column align-items-center">
                            Abstrak
                            <span class="mt-1 text-black">
                                @if($abstrakSizeInKb < 1000)
                                    {{ number_format($abstrakSizeInKb) }} KB
                                    @else
                                    {{ number_format($abstrakSizeInMb, 2) }} MB
                                    @endif
                                    </span>
                        </a>
                    </div> -->
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