@extends('layout.app-clear')

<!-- push css halaman detail berita -->
@push('styles')
<link href="{{ asset('css/list-page/dokumen.css') }}?v={{ filemtime(public_path('css/list-page/dokumen.css')) }}" rel="stylesheet">
@endpush

@section('content')
<!-- Section List Biru -->
<section class="file-title-section">
    <div class="container"></div>
</section>

<div class="container mt-2" style="padding: 50px;">
    <livewire:dokumen-list :tipe-dok="$tipeDok ?? null" />
</div>

@endsection
