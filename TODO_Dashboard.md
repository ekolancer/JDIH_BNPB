# 📊 TODO -- Dashboard Refactor (Webmin JDIH)

Tanggal: 2026-03-04

------------------------------------------------------------------------

## 🎯 OBJECTIVE

Mentransformasi dashboard Webmin menjadi **Operational & Legal Control
Center**, bukan sekadar statistik pengunjung (karena Google Analytics
sudah terpisah).

------------------------------------------------------------------------

# ✅ SECTION 1 -- Executive Summary Widgets

-   [ ] Total Dokumen
-   [ ] Total Dokumen Aktif
-   [ ] Total Dokumen Tidak Berlaku / Dicabut
-   [ ] Metadata Health Score (% kelengkapan dokumen)
-   [ ] Alert Counter (jumlah dokumen bermasalah)

------------------------------------------------------------------------

# ⚖ SECTION 2 -- Compliance & Regulatory Monitoring

-   [ ] Donut Chart Status Regulasi:
    -   Berlaku
    -   Dicabut
    -   Diubah
    -   Tidak Berlaku
    -   Draft / Dalam Proses
-   [ ] Peraturan Mendekati Masa Evaluasi
-   [ ] Peraturan Tidak Berlaku tapi Status Masih Aktif
-   [ ] Dokumen Belum Dipublish \> 7 Hari

------------------------------------------------------------------------

# 🚨 SECTION 3 -- Attention Required (Critical List)

-   [ ] Dokumen tanpa file PDF
-   [ ] Dokumen tanpa abstrak
-   [ ] Dokumen tanpa relasi dasar hukum
-   [ ] Dokumen tanpa kata kunci
-   [ ] Link file rusak / 404
-   [ ] Duplicate nomor & tahun regulasi

------------------------------------------------------------------------

# 📈 SECTION 4 -- Content Performance (Non-GA Based)

-   [ ] Top 5 Dokumen Diunduh
-   [ ] Top 5 Dokumen Dilihat
-   [ ] Rasio Dilihat vs Diunduh (Conversion Rate)
-   [ ] Jenis Dokumen Paling Banyak Dipublish
-   [ ] Tren Publikasi Dokumen per Bulan (6--12 bulan)

------------------------------------------------------------------------

# 🔎 SECTION 5 -- Insight dari Google Analytics (Integrasi)

-   [ ] Top 5 Search Query Internal
-   [ ] Keyword Tanpa Hasil
-   [ ] Halaman Dokumen dengan Exit Rate Tinggi

------------------------------------------------------------------------

# 🧠 SECTION 6 -- Data Quality & SEO Monitoring

-   [ ] Dokumen belum masuk sitemap
-   [ ] Metadata kosong (judul/nomor/tahun tidak standar)
-   [ ] URL slug tidak SEO friendly
-   [ ] File size terlalu besar (\> 10MB)

------------------------------------------------------------------------

# 🏗 TECHNICAL IMPROVEMENT

-   [ ] Refactor layout ke Tailwind modern style
-   [ ] Konsolidasi widget agar tidak redundan dengan GA
-   [ ] Gunakan caching untuk statistik berat
-   [ ] Optimasi query agregasi (hindari N+1)
-   [ ] Logging untuk broken file detection

------------------------------------------------------------------------

# 📌 PRIORITAS IMPLEMENTASI

1.  Alert & Metadata Health Score
2.  Compliance Monitoring
3.  Conversion Rate Dokumen
4.  Tren Publikasi
5.  GA Insight Enhancement

------------------------------------------------------------------------

## 🎯 TARGET AKHIR

Dashboard menjadi: ✔ Command Center Pengelolaan Peraturan\
✔ Monitoring Kepatuhan Regulasi\
✔ Kontrol Kualitas Metadata\
✔ Insight Cepat untuk Pengambilan Keputusan

------------------------------------------------------------------------

**End of TODO**
