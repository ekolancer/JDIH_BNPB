@extends('layout.app-clear')

@section('content')

<section id="stats" class="stats section light-background py-5">
    <div class="container">
        <div class="mx-auto text-center pt-3" style="max-width: 800px;">
            <p class="text-muted" data-aos="zoom-in" data-aos-duration="800">Informasi Hukum</p>
            <h2 class="counters-title fw-bold" data-aos="zoom-in" data-aos-duration="800">Berita Seputar JDIH BNPB.</h2>
        </div>
    </div>
</section>

<div class="container" style="padding: 10px;">
    <div class="row align-items-start">
        <!-- Berita Terpopuler -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Terpopuler</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($beritaTerpopuler as $item)
                        <li class="list-group-item popular-item">
                            <!-- Gambar di sebelah kiri -->
                            <img src="{{ asset('storage/'.$item->gambar) }}" alt="Gambar Berita" class="img-fluid">
                            <div class="content">
                                <!-- Judul dengan maksimal 50 karakter dan hyperlink ke halaman detail -->
                                <h6><a href="{{ route('berita.show', $item->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($item->judul, 50) }}</a></h6>
                                <!-- Tanggal dibuat -->
                                <p class="text-muted"><i class="far fa-calendar-alt me-1" style="font-weight: bold;"></i>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kolom Daftar Berita -->
        <div class="col-md-7">
            <div class="row">
                @forelse ($berita as $item)
                <div class="col-12 mb-3">
                    <!-- Card Dokumen -->
                    <div class="card shadow-lg border-0 zoom-card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex">
                                <!-- Kolom Ikon File -->
                                <div class="me-4">
                                    @if($item->gambar && file_exists(public_path('storage/' . $item->gambar)))
                                    <!-- Jika file lampiran ada di server -->
                                    <img
                                        src="{{ asset('storage/' . $item->gambar) }}"
                                        alt="Gambar Lampiran"
                                        class="rounded-shadow"
                                        height="200px"
                                        width="160px" />
                                    @else
                                    <!-- Jika file tidak ada, tampilkan gambar default -->
                                    <img
                                        src="{{ asset('img/noimage.png') }}"
                                        alt="Ikon Default"
                                        class="rounded-shadow"
                                        height="200px"
                                        width="160px" />
                                    @endif
                                </div>

                                <!-- Kolom Tipe Dokumen dan Judul -->
                                <div class="d-flex flex-column justify-content-center">
                                    <h5 class="card-title text-primary fw-bold mb-2">
                                        {{ $item->judul }}
                                    </h5>
                                    <p class="card-text text-muted mb-0" style="text-align: justify;">
                                        {{ Str::limit(strip_tags($item->konten), 200) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="card-footer bg-light border-top">
                            <div class="d-flex justify-content-between hero-items-center">
                                <!-- Informasi Tanggal -->
                                <div>
                                    <small class="text-muted me-3">
                                        <i class="far fa-calendar-alt me-1" style="font-weight: bold;"></i>
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="far fa-eye me-1" style="font-weight: bold;"></i>
                                        {{ $item->view_count }} dilihat
                                    </small>
                                </div>

                                <!-- Tombol aksi -->
                                <div class="d-flex">
                                    <a href="{{ route('berita.show', $item->slug) }}" class="btn btn-primary btn-sm me-2">
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
                        Tidak ada berita ditemukan.
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $berita->links('pagination::simple-modern') }}
            </div>
        </div>
    </div>
</div>

@endsection