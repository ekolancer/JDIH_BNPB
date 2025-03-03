@extends('layout.app-clear')

@section('content')

<section id="stats" class="stats section light-background py-5">
    <div class="container">
        <div class="mx-auto text-center pt-3" style="max-width: 800px;">
            <p class="text-muted" data-aos="zoom-in" data-aos-duration="800">Informasi Hukum</p>
            <h2 class="counters-title fw-bold" data-aos="zoom-in" data-aos-duration="800">Infografis Seputar JDIH BNPB.</h2>
        </div>
    </div>
</section>

<!-- list content -->
<div class="container mt-2 py-12">
    <!-- Kolom Daftar Infografis -->
    <div class="row">
        @forelse ($infografis as $item)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <a href="{{ route('infografis.show', $item->slug) }}" class="d-block position-relative">
                @if($item->gambar)
                <img
                    src="{{ asset('storage/' . $item->gambar) }}"
                    alt="Gambar Lampiran"
                    class="img-fluid rounded shadow-sm" />
                @else
                <img
                    src="{{ asset('img/noimage.png') }}"
                    alt="Ikon Default"
                    class="img-fluid rounded shadow-sm" />
                @endif
                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white text-center py-2 rounded-bottom">
                    <h5 class="mb-0">{{ $item->judul }}</h5>
                    <small>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</small>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">
                Tidak ada infografis ditemukan.
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $infografis->links('pagination::simple-modern') }}
    </div>
</div>

@endsection