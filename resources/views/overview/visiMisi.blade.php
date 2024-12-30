@extends('layout.app-overview')

@section('content')

<style>
    .about-section,
    .legal-section {
        max-width: 60%;
        margin: 30px auto;
        padding: 40px 30px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .about-section h2,
    .legal-section h2 {
        color: #007bff;
        font-weight: 700;
        margin-bottom: 20px;
        text-align: center;
    }

    .about-section p,
    .legal-section ol li {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #4a4a4a;
        text-align: justify;
    }

    .legal-section ol {
        padding-left: 20px;
    }

    .legal-section ol li {
        margin-bottom: 15px;
    }

    .section-title {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-title h1 {
        font-size: 2.5rem;
        font-weight: bold;
        color: #343a40;
    }

    @media (max-width: 768px) {

        .about-section,
        .legal-section {
            max-width: 90%;
        }

        .about-section h2,
        .legal-section h2 {
            font-size: 1.8rem;
        }

        .about-section p,
        .legal-section ol li {
            font-size: 1rem;
        }
    }
</style>
</head>

<body>

    <!-- About Section -->
    <section class="about-section">
        <h2>Visi</h2>
        <p>
            Menjadi penyedia informasi peraturan perundang-undangan di bidang
            penanggulangan bencana yang lengkap dan akurat.
        </p>
    </section>

    <!-- Legal Basis Section -->
    <section class="legal-section">
        <h2>Misi</h2>
        <ol>
            <li>
                Mengoptimalkan pengolahan dan penyajian dalam rangka penyebarluasan peraturan perundang-undangan
                di bidang penanggulangan bencana yang terpadu dan terpusat untuk dikelola dan dimanfaatkan bersama.
            </li>
            <li>
                Meningkatkan pelayanan dan memberikan kemudahan akses kepada pimpinan BNPB pada khususnya dan publik pada umumnya untuk
                mendapatkan informasi peraturan perundang-undangan penanggulangan bencana dalam rangka mendukung terwujudnya e-goverment.
            </li>
            <li>
                Memperluas dan mengefektifkan jaringan kerja dengan Pusat JDIH Nasional dan antar Anggota JDIH Nasional,
                dalam rangka meningkatkan kinerja JDIH penanggulangan bencana.
            </li>
        </ol>
    </section>

    @endsection