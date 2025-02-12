@extends('layout.app-clear')

@section('content')

<div class="container mt-2" style="padding: 50px;">

    <div class="row align-items-start">
        <!-- Kolom Filter Dokumen -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm" style="background-color: #f8f9fa; border-radius: 10px;">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">Filter Pencarian</h5>

                    <!-- Kata Kunci -->
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="judul" name="judul" class="form-control border-start-0" placeholder="Cari dokumen...">
                    </div>

                    <!-- Looping untuk menampilkan filter berdasarkan kolom yang dikelompokkan -->
                    @foreach($data as $column => $values)
                    <div class="border-bottom pb-2 mb-3">
                        <label class="fw-bold text-secondary">
                            {{ $filterNames[$column] ?? ucfirst(str_replace('_', ' ', $column)) }}
                        </label>

                        @php
                        $collapseId = 'collapse_' . $column;
                        $count = 0;
                        @endphp

                        <!-- Menampilkan 3 data pertama -->
                        @foreach($values as $key => $count)
                        @if($loop->index < 3)
                            <div class="form-check">
                            <input class="form-check-input" type="radio" name="{{ $column }}" value="{{ $key }}" id="{{ $column }}_{{ $key }}">
                            <label class="form-check-label d-flex justify-content-between w-100" for="{{ $column }}_{{ $key }}">
                                <span>{{ $key }}</span>
                                <span class="badge bg-primary">{{ $count }}</span>
                            </label>
                    </div>
                    @endif
                    @endforeach

                    <!-- Data ke-4 dan seterusnya masuk ke dalam collapse -->
                    @if(count($values) > 3)
                    <div class="collapse" id="{{ $collapseId }}">
                        @foreach($values as $key => $count)
                        @if($loop->index >= 3)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="{{ $column }}" value="{{ $key }}" id="{{ $column }}_{{ $key }}">
                            <label class="form-check-label d-flex justify-content-between w-100" for="{{ $column }}_{{ $key }}">
                                <span>{{ $key }}</span>
                                <span class="badge bg-primary">{{ $count }}</span>
                            </label>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <!-- Show More Button -->
                    <button class="btn btn-sm btn-link text-primary p-0 mt-1" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                        <i class="bi bi-chevron-down"></i> Tampilkan lebih banyak
                    </button>
                    @endif
                </div>
                @endforeach

                <!-- Kolom Input -->
                <div class="border-bottom pb-1 mb-3">
                    <div class="input-group">
                        <input type="text" id="nomorPanggil" name="nomorPanggil" class="form-control" placeholder="Nomor Panggil">
                        <button class="btn btn-outline-primary" type="button">Apply</button>
                    </div>
                </div>

                <div class="border-bottom pb-1 mb-3">
                    <div class="input-group">
                        <input type="text" id="isbn" name="isbn" class="form-control" placeholder="ISBN">
                        <button class="btn btn-outline-primary" type="button">Apply</button>
                    </div>
                </div>

                <div class="border-bottom pb-1 mb-3">
                    <div class="input-group">
                        <input type="text" id="nomorIndukBuku" name="nomorIndukBuku" class="form-control" placeholder="Nomor Induk Buku">
                        <button class="btn btn-outline-primary" type="button">Apply</button>
                    </div>
                </div>

                <!-- Tombol Filter -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success">Cari</button>
                    <a href="#" class="btn btn-warning">Reset</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Daftar Dokumen -->
    <div class="col-md-8">
        <div class="row">
            @forelse ($results as $result)
            <div class="col-12 mb-3">
                <!-- Card Dokumen -->
                <div class="card shadow-lg border-0 zoom-card">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Kolom Ikon File -->
                            <div class="align-items-center pb-3 mt-0 me-3">
                                <img src="/img/icon/allDoc.png" alt="" height="50px" width="45px">
                            </div>
                            <!-- Kolom Tipe Dokumen dan Judul -->
                            <div class="d-flex flex-column w-100">
                                <h5 class="card-title text-primary fw-bold mb-0">
                                    {{ $result->jenisDok }} Nomor {{ $result->nomor }} Tahun {{ $result->tahun }}
                                </h5>
                                <p class="card-text mb-3 text-muted">
                                    {{ $result->judul ?? 'Tidak ada deskripsi.' }}
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
                                    <i class="far fa-calendar-alt me-1" style="font-weight: bold;"></i>
                                    {{ \Carbon\Carbon::parse($result->created_at)->format('d F Y') }}
                                </small>
                                <small class="text-muted me-3">
                                    <i class="far fa-eye me-1" style="font-weight: bold;"></i>
                                    {{ $result->view_count }}x Tayang
                                </small>
                                <small class="text-muted">
                                    <i class="far fa-download me-1" style="font-weight: bold;"></i>
                                    {{ $result->download_count }}x Unduhan
                                </small>
                            </div>
                            <!-- Tombol aksi -->
                            <div class="d-flex">
                                <a href="{{ route('dokumen.detail', ['tipeDok' => $result->tipeDok, 'slug' => $result->slug]) }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    Tidak ada data peraturan ditemukan.
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $results->links('pagination::simple-modern') }}
        </div>
    </div>
</div>
</div>

@endsection