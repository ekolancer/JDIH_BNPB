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
    .legal-section h3 {
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
        .legal-section h3 {
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
        <h2>Tentang Kami</h2>
        <p>
            Jaringan Dokumentasi dan Informasi Hukum (JDIH) di lingkungan Badan Nasional Penanggulangan Bencana (BNPB) adalah
            wadah pendayagunaan bersama atas dokumen hukum di bidang penanggulangan bencana secara tertib, terpadu, dan berkesinambungan.
            JDIH merupakan sarana pemberian pelayanan informasi hukum secara lengkap, akurat, mudah, dan cepat.
        </p>
        <p>
            Sistem ini dibangun untuk melaksanakan sebagian tugas pokok dan fungsi Biro Hukum, Organisasi, dan Kerja Sama BNPB,
            yaitu melaksanakan koordinasi penyusunan peraturan perundang-undangan, advokasi hukum, organisasi dan tata laksana,
            serta kerja sama di bidang penanggulangan bencana.
        </p>
    </section>

    <!-- Legal Basis Section -->
    <section class="legal-section">
        <h3>Dasar Hukum</h3>
        <ol>
            <li>
                <strong>Peraturan Presiden Nomor 33 Tahun 2012:</strong> Tentang Jaringan Dokumentasi dan Informasi Hukum Nasional.
            </li>
            <li>
                <strong>Peraturan Menteri Hukum dan HAM Nomor 8 Tahun 2019:</strong> Tentang Standar Pengelolaan Dokumen Hukum.
            </li>
            <li>
                <strong>Keputusan Kepala BNPB:</strong> Tentang Penetapan JDIH sebagai bagian dari layanan hukum di lingkungan BNPB.
            </li>
        </ol>
    </section>

    @endsection