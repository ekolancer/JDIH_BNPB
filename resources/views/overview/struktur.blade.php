@extends('layout.app-overview')

@section('content')

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }

    .card-header {
        background-color: #007bff;
        color: white;
    }

    .list-group-item {
        cursor: pointer;
    }
</style>
</head>

<body>

    <div class="container">
        <h1 class="text-center mt-5 mb-4">Struktur Organisasi</h1>

        <div class="card mb-5">
            <div class="card-header">
                <h3>Biro Hukum, Organisasi dan Kerja Sama</h3>
            </div>
            <div class="card-body">
                <div class="accordion" id="accordionExample">

                    <!-- Bagian Hukum -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingHukum">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHukum" aria-expanded="true" aria-controls="collapseHukum">
                                Bagian Hukum
                            </button>
                        </h2>
                        <div id="collapseHukum" class="accordion-collapse collapse show" aria-labelledby="headingHukum" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item" data-bs-toggle="collapse" data-bs-target="#subbagianPerundangUndangan">Subbagian Peraturan Perundang-undangan</li>
                                    <div id="subbagianPerundangUndangan" class="collapse">
                                        <ul class="list-group ms-3">
                                            <li class="list-group-item">Detail 1</li>
                                            <li class="list-group-item">Detail 2</li>
                                        </ul>
                                    </div>
                                    <li class="list-group-item" data-bs-toggle="collapse" data-bs-target="#subbagianAdvokasiHukum">Subbagian Advokasi Hukum</li>
                                    <div id="subbagianAdvokasiHukum" class="collapse">
                                        <ul class="list-group ms-3">
                                            <li class="list-group-item">Detail 1</li>
                                            <li class="list-group-item">Detail 2</li>
                                        </ul>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Organisasi dan Tata Laksana -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOrganisasiTataLaksana">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrganisasiTataLaksana" aria-expanded="true" aria-controls="collapseOrganisasiTataLaksana">
                                Bagian Organisasi dan Tata Laksana
                            </button>
                        </h2>
                        <div id="collapseOrganisasiTataLaksana" class="accordion-collapse collapse" aria-labelledby="headingOrganisasiTataLaksana" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item" data-bs-toggle="collapse" data-bs-target="#subbagianOrganisasi">Subbagian Organisasi</li>
                                    <div id="subbagianOrganisasi" class="collapse">
                                        <ul class="list-group ms-3">
                                            <li class="list-group-item">Detail 1</li>
                                            <li class="list-group-item">Detail 2</li>
                                        </ul>
                                    </div>
                                    <li class="list-group-item" data-bs-toggle="collapse" data-bs-target="#subbagianTataLaksana">Subbagian Tata Laksana</li>
                                    <div id="subbagianTataLaksana" class="collapse">
                                        <ul class="list-group ms-3">
                                            <li class="list-group-item">Detail 1</li>
                                            <li class="list-group-item">Detail 2</li>
                                        </ul>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Kerja Sama -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingKerjaSama">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKerjaSama" aria-expanded="true" aria-controls="collapseKerjaSama">
                                Bagian Kerja Sama
                            </button>
                        </h2>
                        <div id="collapseKerjaSama" class="accordion-collapse collapse" aria-labelledby="headingKerjaSama" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item" data-bs-toggle="collapse" data-bs-target="#subbagianKerjaSamaDalamNegeri">Subbagian Kerja Sama Dalam Negeri</li>
                                    <div id="subbagianKerjaSamaDalamNegeri" class="collapse">
                                        <ul class="list-group ms-3">
                                            <li class="list-group-item">Detail 1</li>
                                            <li class="list-group-item">Detail 2</li>
                                        </ul>
                                    </div>
                                    <li class="list-group-item" data-bs-toggle="collapse" data-bs-target="#subbagianKerjaSamaInternasional">Subbagian Kerja Sama Internasional</li>
                                    <div id="subbagianKerjaSamaInternasional" class="collapse">
                                        <ul class="list-group ms-3">
                                            <li class="list-group-item">Detail 1</li>
                                            <li class="list-group-item">Detail 2</li>
                                        </ul>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Kelompok Jabatan Fungsional -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingJabatanFungsional">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJabatanFungsional" aria-expanded="true" aria-controls="collapseJabatanFungsional">
                                Kelompok Jabatan Fungsional
                            </button>
                        </h2>
                        <div id="collapseJabatanFungsional" class="accordion-collapse collapse" aria-labelledby="headingJabatanFungsional" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item">Detail 1</li>
                                    <li class="list-group-item">Detail 2</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @endsection