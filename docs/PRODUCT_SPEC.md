[↩ Kembali ke Beranda](../README.md)

# Spesifikasi Produk — JDIH BNPB

> **Dokumen ini ditujukan untuk:** Product Owner, Manajer Proyek, Analis Bisnis, dan Developer baru yang perlu memahami *apa* yang dilakukan sistem ini dan *mengapa*, tanpa harus membaca kode.

---

## Daftar Isi

- [Ringkasan Eksekutif](#ringkasan-eksekutif)
- [Pengguna & Pemangku Kepentingan](#pengguna--pemangku-kepentingan)
- [Matriks RBAC (Role-Based Access Control)](#matriks-rbac-role-based-access-control)
- [Alur Bisnis Utama](#alur-bisnis-utama)
  - [Alur 1: Publikasi Dokumen Hukum](#alur-1-publikasi-dokumen-hukum)
  - [Alur 2: Akses Publik & Unduhan Dokumen](#alur-2-akses-publik--unduhan-dokumen)
  - [Alur 3: Pencatatan Pengunjung](#alur-3-pencatatan-pengunjung)
  - [Alur 4: Sinkronisasi Data via API](#alur-4-sinkronisasi-data-via-api)
- [Katalog Fitur Lengkap](#katalog-fitur-lengkap)
- [Aturan Bisnis Kritis](#aturan-bisnis-kritis)
- [Glosarium](#glosarium)

---

## Ringkasan Eksekutif

**JDIH BNPB** adalah portal web resmi untuk pengelolaan dan publikasi produk hukum Badan Nasional Penanggulangan Bencana. Sistem ini menjawab dua kebutuhan utama:

1. **Kebutuhan Publik** — Masyarakat, peneliti, dan instansi lain membutuhkan akses mudah, cepat, dan terstruktur terhadap peraturan perundang-undangan yang diterbitkan BNPB. Sebelumnya, dokumen tersebar dan sulit ditemukan.

2. **Kebutuhan Internal** — Tim Kedeputian Hukum BNPB membutuhkan alat manajemen konten yang efisien, dengan kontrol akses berbasis peran, audit trail, dan kemampuan sinkronisasi data ke sistem Pusat Peraturan Perundang-undangan (PUU) nasional.

**Nilai utama yang dihasilkan:**
- Satu sumber kebenaran (*single source of truth*) untuk seluruh produk hukum BNPB
- Transparansi dan akuntabilitas melalui akses publik tanpa hambatan
- Efisiensi operasional melalui otomatisasi slug, UID dokumen, dan metadata
- Interoperabilitas dengan sistem hukum nasional melalui API terbuka

---

## Pengguna & Pemangku Kepentingan

| Kelompok | Tipe | Kebutuhan Utama |
|---|---|---|
| **Masyarakat Umum** | Pengguna Akhir (Publik) | Mencari, membaca, dan mengunduh dokumen hukum BNPB |
| **Peneliti & Akademisi** | Pengguna Akhir (Publik) | Akses metadata terstruktur via API untuk riset |
| **Instansi Pemerintah Lain** | Pengguna Akhir (Publik) | Sinkronisasi data ke sistem PUU nasional |
| **Biro Hukum** | Administrator (Internal) | Input, edit, dan publikasi dokumen hukum |
| **Editor Konten** | Administrator (Internal) | Kelola berita, infografis, dan halaman profil |
| **IT Administrator** | Administrator (Internal) | Kelola pengguna, role, dan monitoring sistem |
| **Pimpinan BNPB** | Pemangku Kepentingan | Laporan statistik dan monitoring kinerja portal |

---

## Matriks RBAC (Role-Based Access Control)

Sistem otorisasi dibangun di atas **Spatie Laravel Permission** yang dikelola melalui **Filament Shield**. Setiap permission di-generate otomatis per resource dengan format `{aksi}_{resource}`.

### Definisi Role

| Role | Deskripsi | Dibuat Oleh |
|---|---|---|
| `super_admin` | Akses penuh tanpa batasan ke seluruh sistem | `php artisan shield:super-admin` |
| `admin` | Akses penuh ke konten, terbatas di user management | Manual via panel |
| `editor` | Hanya dapat membuat dan mengedit konten, tidak dapat menghapus | Manual via panel |
| `viewer` | Hanya dapat melihat data di panel, tidak dapat mengubah | Manual via panel |

### Matriks Akses per Fitur

Keterangan: ✅ = Akses Penuh | 📝 = Buat & Edit | 👁 = Lihat Saja | ❌ = Tidak Ada Akses

| Fitur / Resource | `super_admin` | `admin` | `editor` | `viewer` |
|---|:---:|:---:|:---:|:---:|
| **Dokumen Hukum** — Lihat | ✅ | ✅ | ✅ | 👁 |
| **Dokumen Hukum** — Buat & Edit | ✅ | ✅ | 📝 | ❌ |
| **Dokumen Hukum** — Hapus | ✅ | ✅ | ❌ | ❌ |
| **Berita** — Lihat | ✅ | ✅ | ✅ | 👁 |
| **Berita** — Buat & Edit | ✅ | ✅ | 📝 | ❌ |
| **Berita** — Hapus | ✅ | ✅ | ❌ | ❌ |
| **Infografis** — Buat & Edit | ✅ | ✅ | 📝 | ❌ |
| **Profil Page** — Buat & Edit | ✅ | ✅ | 📝 | ❌ |
| **FAQ** — Buat & Edit | ✅ | ✅ | 📝 | ❌ |
| **Metadata Referensi** (Jenis, Bidang, Subjek) | ✅ | ✅ | ❌ | ❌ |
| **User Management** — Lihat | ✅ | ✅ | ❌ | ❌ |
| **User Management** — Buat & Edit | ✅ | ❌ | ❌ | ❌ |
| **Role Management** | ✅ | ❌ | ❌ | ❌ |
| **Visitor Logs** — Lihat | ✅ | ✅ | ❌ | 👁 |
| **Visitor Logs** — Hapus | ✅ | ✅ | ❌ | ❌ |
| **Google Analytics Dashboard** | ✅ | ✅ | ❌ | ❌ |
| **Application Health Check** | ✅ | ❌ | ❌ | ❌ |
| **Edit Profil Sendiri** | ✅ | ✅ | ✅ | ✅ |

> **Catatan Implementasi:** `super_admin` di-bypass dari semua pengecekan permission melalui `canAccessPanel()` di `App\Models\User`. Role lain diperiksa via Filament Shield secara granular per resource.

---

## Alur Bisnis Utama

### Alur 1: Publikasi Dokumen Hukum

Alur ini menggambarkan proses dari input dokumen oleh staf hingga dokumen tersedia untuk publik.

```mermaid
flowchart TD
    A([👤 Staf Kedeputian Hukum\nLogin ke /webmin]) --> B[Buka menu\nDokumen Hukum]
    B --> C[Klik 'Add Document']
    C --> D{Pilih Tipe Dokumen}

    D -->|Peraturan| E1[Isi: Jenis, Judul, TEU Badan,\nNomor, Tahun, Tgl Penetapan,\nTgl Pengundangan, Status, Sumber]
    D -->|Monografi| E2[Isi: Judul, Penerbit, ISBN,\nNomor Panggil, Cetakan/Edisi,\nDeskripsi Fisik]
    D -->|Artikel| E3[Isi: Judul, Penulis, Sumber,\nTahun, Penerbit]
    D -->|Putusan| E4[Isi: Judul, Nomor, Tahun,\nTgl Dibacakan, Tempat Peradilan]

    E1 & E2 & E3 & E4 --> F[Upload Lampiran\nPDF / Gambar maks 25MB]
    F --> G{Tipe = Peraturan?}
    G -->|Ya| H[Upload Abstrak PDF\nOpsional]
    G -->|Tidak| I
    H --> I[Submit Form]

    I --> J{Validasi\nLaravel Form Request}
    J -->|Gagal| K[❌ Tampilkan error\nkembali ke form]
    J -->|Lulus| L[Model Observer: boot\nGenerating slug & UID]

    L --> M["Slug: perka-bnpb-01-2024\nUID: PERKA/BNPB/01/2024"]
    M --> N[Simpan ke database\ndokumen_hukums]
    N --> O[✅ Dokumen tersimpan\ndan langsung publik]

    O --> P([🌐 Pengunjung dapat\nmengakses di portal])

    style K fill:#fee2e2,stroke:#ef4444
    style O fill:#dcfce7,stroke:#22c55e
    style P fill:#dbeafe,stroke:#3b82f6
```

---

### Alur 2: Akses Publik & Unduhan Dokumen

Alur ini menggambarkan perjalanan pengunjung publik dari pencarian hingga mengunduh dokumen.

```mermaid
flowchart TD
    A([🌐 Pengunjung\nMembuka Portal]) --> B[Middleware: VisitorCounter\nCatat kunjungan harian]
    B --> C{Pilih Aksi}

    C -->|Cari Dokumen| D[Filter: Jenis, Tahun,\nBidang Hukum, Subjek]
    C -->|Buka Halaman Utama| E[Lihat: Peraturan Terbaru,\nBerita, Infografis, Rating]

    D --> F[Livewire DokumenList\nQuery real-time]
    F --> G[Klik Judul Dokumen]
    G --> H[GET /dokumen/tipeDok/slug\nDokumenController@show]
    H --> I[Increment view_count\nvia DB::table direct]
    I --> J[Tampilkan halaman detail\ndengan metadata lengkap]

    J --> K{Aksi Pengguna}

    K -->|Baca Online| L[GET /dokumen/view/slug\nfileView - PDF in browser]
    K -->|Unduh Lampiran| M[GET /download-lampiran/slug\nunduhLampiran]
    K -->|Unduh Abstrak| N[GET /download-abstrak/slug\nunduhAbstrak]

    L --> O{Gate::authorize\nviewFile}
    M --> P{Gate::authorize\ndownload}
    N --> P

    O -->|Ditolak| Q[❌ 403 Forbidden]
    P -->|Ditolak| Q

    O -->|Diizinkan| R{Status Dokumen\n= BERLAKU?}
    P -->|Diizinkan| R

    R -->|TIDAK BERLAKU| S[❌ 403 Unduhan\ndinonaktifkan]
    R -->|BERLAKU| T{File fisik\nada di storage?}

    T -->|Tidak| U[❌ 404 File\ntidak ditemukan]
    T -->|Ya| V[Increment download_count\nKirim file ke browser]

    V --> W([✅ File diterima\noleh pengunjung])

    style Q fill:#fee2e2,stroke:#ef4444
    style S fill:#fee2e2,stroke:#ef4444
    style U fill:#fee2e2,stroke:#ef4444
    style W fill:#dcfce7,stroke:#22c55e
```

---

### Alur 3: Pencatatan Pengunjung

Alur ini menggambarkan bagaimana sistem mencatat dan memfilter pengunjung secara otomatis di background.

```mermaid
flowchart TD
    A([HTTP Request\nMasuk]) --> B[Middleware: VisitorCounter]
    B --> C{Route adalah\n/webmin/* atau /api/*?}

    C -->|Ya| D[⏭ Skip — Jangan catat\nadmin & API request]
    C -->|Tidak| E[Ambil IP, User-Agent,\nPath, Referrer]

    E --> F{CrawlerDetect:\nApakah Bot/Crawler?}
    F -->|Ya| G[⏭ Skip — Abaikan\nbot & spider]
    F -->|Tidak| H{IP Lokal?\n127.x / 192.168.x / 10.224.x}

    H -->|Ya| I[Set Country = Indonesia\nCity = Local Network]
    H -->|Tidak| J[Lookup Geolokasi\nipapi.co]

    J --> K{Berhasil &\nbukan error?}
    K -->|Tidak| L[Fallback ke\nipwho.is]
    K -->|Ya| M[Ambil country & city]
    L --> M

    M --> N{Country =\nIndonesia?}
    N -->|Tidak| O[⏭ Skip — Abaikan\npengunjung luar negeri]
    N -->|Ya| P

    I --> P{Cek User-Agent\nMencurigakan?}
    P -->|sqlmap, curl, nmap, dll| Q[Tandai suspicious = true]
    P -->|Normal| R[suspicious = false]

    Q & R --> S{Sudah ada record\nhari ini untuk IP ini?}
    S -->|Ya| T[⏭ Skip — Cegah\nduplikasi harian]
    S -->|Tidak| U[INSERT ke tabel visitors\nip, path, country, city, suspicious]

    U --> V([✅ Kunjungan tercatat\ndi Visitor Logs])

    style D fill:#f3f4f6,stroke:#9ca3af
    style G fill:#f3f4f6,stroke:#9ca3af
    style O fill:#f3f4f6,stroke:#9ca3af
    style T fill:#f3f4f6,stroke:#9ca3af
    style V fill:#dcfce7,stroke:#22c55e
```

---

### Alur 4: Sinkronisasi Data via API

Alur ini menggambarkan bagaimana sistem eksternal (seperti portal PUU nasional) mengambil data dari JDIH BNPB.

```mermaid
sequenceDiagram
    participant EXT as 🔗 Sistem Eksternal<br/>(Portal PUU / Integrasi)
    participant MW as Middleware<br/>Throttle 60/min
    participant API as ApiController<br/>GET /metadata/api
    participant VAL as MetadataApiRequest<br/>Validation
    participant DB as MySQL Database
    participant RES as DokumenHukumResource<br/>JSON Transformer

    EXT->>MW: GET /metadata/api?tipeDok=Peraturan&tahun=2024
    MW->>MW: Cek rate limit (60 req/menit per IP)

    alt Rate limit terlampaui
        MW-->>EXT: 429 Too Many Requests
    end

    MW->>API: Request diteruskan
    API->>VAL: Validasi parameter query

    alt Parameter tidak valid
        VAL-->>EXT: 422 Unprocessable Entity<br/>{"error": "Validation failed", "details": {...}}
    end

    VAL->>DB: Query DokumenHukum<br/>with jenisDokumen, bidangHukumRef, subjekRef
    DB-->>VAL: Collection hasil query

    VAL->>RES: Transform setiap record<br/>ke format API standar

    Note over RES: Format output:<br/>idData, tahun_pengundangan,<br/>jenis, noPeraturan, judul,<br/>urlDownload, urlDetailPeraturan, dll

    RES-->>EXT: 200 OK<br/>JSON Array (tanpa wrapper "data")<br/>UTF-8, Pretty Print
```

---

## Katalog Fitur Lengkap

### Portal Publik

| Fitur | Route | Deskripsi Teknis |
|---|---|---|
| Halaman Utama | `GET /` | Statistik dokumen, peraturan terbaru, berita, infografis, rating widget |
| Daftar Dokumen | `GET /dokumen/{tipeDok?}` | Filter multi-dimensi via Livewire, lazy loading |
| Detail Dokumen | `GET /dokumen/{tipeDok}/{slug}` | Metadata lengkap, increment `view_count` |
| Pratinjau PDF | `GET /dokumen/view/{slug}` | Stream file langsung ke browser, Gate check |
| Unduh Lampiran | `GET /download-lampiran/{slug}` | Gate check + status check + increment `download_count` |
| Unduh Abstrak | `GET /download-abstrak/{slug}` | Hanya untuk tipe Peraturan |
| Daftar Berita | `GET /berita` | Berita aktif (`status = 1`) |
| Detail Berita | `GET /berita/{slug}` | Increment `view_count` |
| Daftar Infografis | `GET /infografis` | Infografis aktif, maks 6 item di homepage |
| Halaman Profil | `GET /profil/{slug}` | Konten dinamis dari `profil_pages`, slug `faq` khusus |
| Rating Website | `POST /` | Simpan rating 1-5 bintang |
| API Rating | `GET /api/rating-summary` | JSON distribusi & rata-rata rating |

### Panel Administrasi (`/webmin`)

| Fitur | Grup Navigasi | Deskripsi |
|---|---|---|
| Manajemen Dokumen Hukum | Main Menu | CRUD + upload file + auto-slug + auto-UID |
| Manajemen Berita | Main Menu | CRUD + Rich Editor + upload gambar + toggle status |
| Manajemen Infografis | Main Menu | CRUD + upload gambar + toggle status |
| Manajemen FAQ | Main Menu | CRUD + sort order |
| Profil Page | Features | CMS halaman statis (Visi Misi, Struktur Org, dll) |
| Visitor Logs | Features | Log pengunjung real-time, filter negara & suspicious |
| Application Health | Features | Status DB, cache, storage, queue |
| Jenis Dokumen | Metadata Referensi | Master data jenis + singkatan |
| Bidang Hukum | Metadata Referensi | Master data bidang hukum |
| Subjek Hukum | Metadata Referensi | Master data subjek + kategori |
| User Management | User Management | CRUD user + assign role |
| Role Management | User Management | CRUD role + assign permission granular |
| Google Analytics | Systems | Dashboard GA4: tren, traffic, perangkat, negara |

---

## Aturan Bisnis Kritis

Berikut adalah aturan-aturan yang **tidak boleh dilanggar** dalam pengembangan fitur baru:

**BR-01: Unduhan hanya untuk dokumen BERLAKU**
> Dokumen dengan status `TIDAK BERLAKU` tidak dapat diunduh. Accessor `download_allowed` di model `DokumenHukum` mengembalikan `false` jika status bukan `BERLAKU`. Controller memeriksa ini sebelum mengirim file.

**BR-02: Slug dan UID di-generate otomatis, tidak boleh diinput manual**
> Slug dan `document_uid` di-generate oleh `Model::boot()` saat `creating` dan `updating`. Format UID: `{SINGKATAN}/{LEMBAGA}/{NOMOR}/{TAHUN}` (contoh: `PERKA/BNPB/01/2024`). Jika terjadi duplikasi, suffix angka ditambahkan otomatis.

**BR-03: File lama dihapus otomatis saat diganti**
> Saat field `lampiran` atau `abstrak` diubah, file lama di storage dihapus oleh `Model::boot()` pada event `updating`. Saat record dihapus, semua file terkait juga dihapus (`deleted` event).

**BR-04: Visitor hanya dicatat sekali per hari per IP**
> Middleware `VisitorCounter` memeriksa keberadaan record dengan IP yang sama pada hari yang sama sebelum insert. Ini mencegah inflasi statistik.

**BR-05: Pengunjung luar negeri tidak dicatat**
> Sistem hanya mencatat pengunjung dari Indonesia (atau IP lokal). Pengunjung dari negara lain di-skip setelah geolokasi terdeteksi.

**BR-06: Path file harus aman (path traversal prevention)**
> Method `isSafePublicStoragePath()` di `DokumenController` memvalidasi bahwa path tidak dimulai dengan `/` dan tidak mengandung `../`. Ini mencegah serangan path traversal.

**BR-07: API metadata tidak memerlukan autentikasi, tapi dibatasi throttle**
> Endpoint `/metadata/api` bersifat publik untuk mendukung interoperabilitas, namun dibatasi 60 request per menit per IP untuk mencegah penyalahgunaan.

**BR-08: Akses panel admin dibatasi IP whitelist**
> Middleware `RestrictIpMiddleware` diterapkan pada semua route `/webmin/*`. Hanya IP yang terdaftar di `config/whitelist-ip.php` yang dapat mengakses panel. Localhost selalu diizinkan.

---

## Glosarium

| Istilah | Kepanjangan / Definisi |
|---|---|
| **JDIH** | Jaringan Dokumentasi dan Informasi Hukum — sistem nasional pengelolaan dokumen hukum Indonesia |
| **BNPB** | Badan Nasional Penanggulangan Bencana — lembaga pemerintah non-kementerian yang menangani penanggulangan bencana |
| **PUU** | Peraturan Perundang-Undangan — istilah umum untuk produk hukum berupa peraturan |
| **RBAC** | Role-Based Access Control — sistem otorisasi berbasis peran pengguna |
| **TEU Badan** | Tajuk Entri Utama Badan — nama lembaga/badan yang menerbitkan dokumen (standar katalogisasi perpustakaan) |
| **Tipedok** | Tipe Dokumen — klasifikasi utama dokumen: `Peraturan`, `Monografi`, `Artikel`, `Putusan` |
| **Slug** | Versi URL-friendly dari judul dokumen (contoh: `perka-bnpb-01-2024`) |
| **UID Dokumen** | Unique Identifier Dokumen — kode unik format `SINGKATAN/LEMBAGA/NOMOR/TAHUN` |
| **Lampiran** | File utama dokumen (PDF atau gambar) yang dapat diunduh publik |
| **Abstrak** | Ringkasan dokumen dalam format PDF, hanya tersedia untuk tipe Peraturan |
| **PERKA** | Peraturan Kepala — salah satu jenis peraturan yang diterbitkan Kepala BNPB |
| **GA4** | Google Analytics 4 — platform analitik web generasi terbaru dari Google |
| **Filament** | Framework admin panel untuk Laravel berbasis Livewire |
| **Shield** | Filament Shield — plugin manajemen permission granular untuk Filament |
| **Spatie Permission** | Package Laravel untuk manajemen role dan permission berbasis database |
| **Livewire** | Framework PHP untuk komponen UI reaktif tanpa JavaScript |
| **Throttle** | Pembatasan jumlah request dalam periode waktu tertentu |
| **CIDR** | Classless Inter-Domain Routing — notasi untuk mendefinisikan rentang IP (contoh: `10.220.52.0/22`) |
| **Crawler** | Bot otomatis (seperti Googlebot) yang menjelajahi web; tidak dicatat sebagai pengunjung |
