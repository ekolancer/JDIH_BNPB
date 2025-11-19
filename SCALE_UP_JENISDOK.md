## ⭐ 1. Struktur Tabel Baru: ref_jenis_dokumen

## Jalankan:

CREATE TABLE ref_jenis_dokumen (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(150) NOT NULL UNIQUE,
  singkatan VARCHAR(50) NOT NULL UNIQUE,
  deskripsi TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_unicode_ci;

## ⭐ 2. Tambahkan kolom FK ke tabel lama (dokumen_hukums)
ALTER TABLE dokumen_hukums
  ADD COLUMN ref_jenis_dokumen_id BIGINT UNSIGNED NULL AFTER jenisDok;


## Belum tambahkan foreign key dulu — supaya migrasi lebih fleksibel.

## ⭐ 3. Isi Master “jenisDokumen” dari data unik tabel lama
INSERT INTO ref_jenis_dokumen (nama, singkatan)
SELECT DISTINCT jenisDok, singkatan
FROM dokumen_hukums
WHERE jenisDok IS NOT NULL
  AND TRIM(jenisDok) <> '';


## Informasi ini akan menghasilkan master seperti:

Peraturan Kepala Badan  | PERKA
Surat Edaran            | SE
Surat Keputusan         | SK
Pedoman                 | PD
Petunjuk Teknis         | Juknis
Petunjuk Pelaksanaan    | Juklak
dst...

## ⭐ 4. Update tabel lama agar terhubung ke master
UPDATE dokumen_hukums d
JOIN ref_jenis_dokumen r
  ON d.jenisDok = r.nama
SET d.ref_jenis_dokumen_id = r.id;

## ⭐ 5. Tambahkan Foreign Key (setelah data aman)
ALTER TABLE dokumen_hukums
  ADD CONSTRAINT fk_dokhukum_jenis
  FOREIGN KEY (ref_jenis_dokumen_id)
  REFERENCES ref_jenis_dokumen(id);

## ⭐ 6. (Opsional) Mulai Deprecate Kolom Lama

## Saya tidak menyarankan langsung hapus kolom lama karena:
## 1. UI masih menggunakan kolom lama
## 2. kode backend mungkin masih pakai kolom ini
## 3. butuh masa transisi 1–2 minggu
## 4. Tapi Anda bisa menyiapkan rename untuk dokumentasi:

ALTER TABLE dokumen_hukums
  RENAME COLUMN jenisDok TO jenisDok_legacy,
  RENAME COLUMN singkatan TO singkatar_legacy;


## Jika frontend/back-end sudah diperbarui → baru dihapus:

ALTER TABLE dokumen_hukums
  DROP COLUMN jenisDok_legacy,
  DROP COLUMN singkatar_legacy;

## ⭐ 7. Contoh Data Master yang Direkomendasikan (fix standard)

## Jika ingin data master lebih rapi mengikuti standar JDIH nasional, rekomendasi:

## nama	                singkatan
Undang-Undang	        UU
Peraturan Pemerintah	PP
Peraturan Presiden	    Perpres
Peraturan Menteri	    Permen
Peraturan Kepala Badan	Perka
Keputusan Kepala Badan	Keputusan
Surat Edaran	        SE
Petunjuk Teknis	        Juknis
Petunjuk Pelaksanaan	Juklak
Pedoman	                Pedoman

## Update UUID dan Slug

App\Models\DokumenHukum::chunk(100, function ($docs) {
    foreach ($docs as $d) {
        $d->generateDocumentUid();
        $d->slug = $d->generateSlug();
        $d->save();
    }
});
