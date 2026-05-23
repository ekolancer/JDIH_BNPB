# Database Schema Optimization — JDIH BNPB

## Perbandingan Manajemen Dokumen Hukum: ILDIS vs JDIH BNPB

Dokumen ini berisi analisis perbandingan struktur database manajemen dokumen hukum antara **ILDIS (Sistem JDIH Pusat)** dengan **JDIH BNPB**, beserta rekomendasi optimasi agar sesuai best practice.

---

## 1. Struktur Tabel Utama Dokumen

| Aspek | ILDIS (Pusat) | JDIH BNPB (Saat Ini) |
|-------|--------------|----------------------|
| Tabel utama | `document` | `dokumen_hukums` |
| Jumlah kolom dokumen | ~70+ kolom | ~30 kolom |
| Tipe dokumen | Kolom `tipe_dokumen` (int FK) | Kolom `tipeDok` (enum) |
| Jenis dokumen | FK ke `document_type` | FK ke `ref_jenis_dokumen` |
| Bidang hukum | Kolom `bidang_hukum` (text) | FK ke `ref_bidang_hukum` ✅ |
| Subjek | Tabel `data_subyek` (1:N) | Pivot `dokumen_subjek` (M:N) ✅ |
| Status peraturan | Tabel `data_status` terpisah | Kolom `status` (enum) |
| Lampiran/File | Tabel `data_lampiran` terpisah | Kolom `lampiran` (single file) |
| Relasi antar dokumen | 4 tabel terpisah (`document_terkait`, `peraturan_terkait`, `putusan_terkait`, `hasil_uji_materi`) | Tabel `dokumen_relasi` (unified) ✅ |

---

## 2. Fitur ILDIS yang Belum Ada di JDIH BNPB

| Fitur ILDIS | Tabel di ILDIS | Status di JDIH BNPB |
|-------------|---------------|---------------------|
| Multi-lampiran per dokumen | `data_lampiran` | ❌ Hanya 1 file per dokumen |
| Riwayat status peraturan | `data_status` | ❌ Hanya status aktif (enum) |
| Data pengarang/penyusun | `data_pengarang` | ❌ Belum ada |
| Abstrak terstruktur (multi-bagian) | `abstrak` (tabel terpisah) | ❌ Hanya file abstrak |
| Hit counter terpisah | `hit_counter`, `download_counter` | ✅ Sudah ada (`view_count`, `download_count`) |
| Catatan verifikasi | `catatan_verifikasi` | ❌ Belum ada |
| Integrasi JDIHN | Kolom `integrasi` di banyak tabel | ❌ Belum ada |
| Urusan pemerintahan | `urusan_pemerintahan` + kolom di `document` | ❌ Belum ada |
| Sumber perolehan | Enum `Beli`/`Hibah` di `document` | ❌ Belum ada |
| Tanggal pengundangan | Kolom `tanggal_pengundangan` | ✅ Ada (`tglPengundangan`) |
| Pemrakarsa & Inisiatif | Kolom di `document` | ❌ Belum ada |

---

## 3. Kelebihan JDIH BNPB Dibanding ILDIS

| Aspek | Penjelasan |
|-------|-----------|
| **Normalisasi referensi** | Bidang hukum dan subjek sudah dinormalisasi dengan FK, ILDIS masih pakai text/varchar |
| **Relasi dokumen terpadu** | Satu tabel `dokumen_relasi` dengan enum jenis relasi (lebih clean dari 4 tabel terpisah di ILDIS) |
| **Slug-based routing** | SEO-friendly URL, ILDIS belum punya |
| **Document UID** | Format terstruktur `PERKA/BNPB/01/2024` |
| **Activity logging** | Spatie Activity Log terintegrasi |
| **Modern stack** | Laravel + Filament vs Yii2 legacy |
| **Performance indexes** | Index komposit untuk query umum |
| **Fulltext search** | Index fulltext pada kolom `judul` |

---

## 4. Rekomendasi Optimasi

### A. Prioritas Tinggi (Wajib untuk Kepatuhan JDIHN)

#### 4.1 Tabel Multi-Lampiran

ILDIS menyimpan lampiran di tabel terpisah `data_lampiran` yang memungkinkan satu dokumen memiliki banyak file (naskah lengkap, lampiran, penjelasan, dll).

**Migration:**

```php
Schema::create('dokumen_lampiran', function (Blueprint $table) {
    $table->id();
    $table->foreignId('dokumen_hukum_id')
        ->constrained('dokumen_hukums')
        ->cascadeOnDelete();
    $table->string('judul', 255);
    $table->string('file_path', 500);
    $table->string('tipe_file', 50)->nullable(); // pdf, doc, image
    $table->enum('jenis', [
        'naskah_lengkap',
        'lampiran',
        'penjelasan',
        'abstrak',
        'lainnya'
    ])->default('lampiran');
    $table->enum('akses', ['publik', 'terbatas'])->default('publik');
    $table->text('deskripsi')->nullable();
    $table->unsignedInteger('urutan')->default(0);
    $table->unsignedBigInteger('download_count')->default(0);
    $table->timestamps();

    $table->index(['dokumen_hukum_id', 'jenis']);
});
```

**Model Relation:**

```php
// DokumenHukum.php
public function lampiranFiles()
{
    return $this->hasMany(DokumenLampiran::class, 'dokumen_hukum_id')
        ->orderBy('urutan');
}
```

---

#### 4.2 Tabel Riwayat Status Peraturan

ILDIS memiliki tabel `data_status` untuk melacak perubahan status (berlaku → diubah → dicabut). Ini penting untuk audit trail.

**Migration:**

```php
Schema::create('dokumen_status_history', function (Blueprint $table) {
    $table->id();
    $table->foreignId('dokumen_hukum_id')
        ->constrained('dokumen_hukums')
        ->cascadeOnDelete();
    $table->enum('status_peraturan', [
        'berlaku',
        'tidak_berlaku',
        'dicabut',
        'diubah',
        'dicabut_sebagian'
    ]);
    $table->unsignedBigInteger('dokumen_pencabut_id')->nullable();
    $table->text('catatan')->nullable();
    $table->date('tanggal_perubahan')->nullable();
    $table->unsignedInteger('urutan')->default(0);
    $table->timestamps();

    $table->foreign('dokumen_pencabut_id')
        ->references('id')
        ->on('dokumen_hukums')
        ->nullOnDelete();
    $table->index('dokumen_hukum_id');
});
```

**Model Relation:**

```php
// DokumenHukum.php
public function statusHistory()
{
    return $this->hasMany(DokumenStatusHistory::class, 'dokumen_hukum_id')
        ->orderByDesc('tanggal_perubahan');
}
```

---

#### 4.3 Kolom Integrasi JDIHN

ILDIS memiliki kolom `integrasi` di hampir semua tabel terkait dokumen untuk sinkronisasi data dengan JDIHN pusat.

**Migration:**

```php
Schema::table('dokumen_hukums', function (Blueprint $table) {
    $table->boolean('is_synced_jdihn')->default(false)->after('download_count');
    $table->timestamp('last_synced_at')->nullable()->after('is_synced_jdihn');
    $table->string('jdihn_id', 100)->nullable()->unique()->after('last_synced_at');
});
```

---

### B. Prioritas Sedang (Meningkatkan Kelengkapan Data)

#### 4.4 Tabel Data Pengarang/Penyusun

ILDIS memiliki tabel `data_pengarang` dengan referensi ke `pengarang`, `tipe_pengarang`, dan `jenis_pengarang`.

**Migration:**

```php
Schema::create('ref_pengarang', function (Blueprint $table) {
    $table->id();
    $table->string('nama', 255);
    $table->enum('tipe', ['perorangan', 'badan'])->default('perorangan');
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->index('nama');
});

Schema::create('dokumen_pengarang', function (Blueprint $table) {
    $table->id();
    $table->foreignId('dokumen_hukum_id')
        ->constrained('dokumen_hukums')
        ->cascadeOnDelete();
    $table->foreignId('pengarang_id')
        ->constrained('ref_pengarang')
        ->cascadeOnDelete();
    $table->enum('peran', ['penyusun', 'editor', 'kontributor'])
        ->default('penyusun');
    $table->timestamps();

    $table->unique(['dokumen_hukum_id', 'pengarang_id', 'peran']);
});
```

**Model Relation:**

```php
// DokumenHukum.php
public function pengarang()
{
    return $this->belongsToMany(RefPengarang::class, 'dokumen_pengarang', 'dokumen_hukum_id', 'pengarang_id')
        ->withPivot('peran')
        ->withTimestamps();
}
```

---

#### 4.5 Kolom Tambahan pada `dokumen_hukums`

Kolom-kolom yang ada di ILDIS tapi belum ada di JDIH BNPB.

**Migration:**

```php
Schema::table('dokumen_hukums', function (Blueprint $table) {
    $table->string('pemrakarsa', 255)->nullable()->after('penandatangan');
    $table->string('inisiatif', 255)->nullable()->after('pemrakarsa');
    $table->unsignedBigInteger('ref_urusan_pemerintahan_id')->nullable()->after('inisiatif');
    $table->enum('sumber_perolehan', ['internal', 'beli', 'hibah'])->nullable()->after('ref_urusan_pemerintahan_id');
    $table->boolean('is_published')->default(false)->after('sumber_perolehan');

    $table->foreign('ref_urusan_pemerintahan_id')
        ->references('id')
        ->on('ref_urusan_pemerintahan')
        ->nullOnDelete();
});
```

---

#### 4.6 Tabel Catatan Verifikasi (Workflow)

ILDIS memiliki tabel `catatan_verifikasi` untuk quality control sebelum publish.

**Migration:**

```php
Schema::create('dokumen_verifikasi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('dokumen_hukum_id')
        ->constrained('dokumen_hukums')
        ->cascadeOnDelete();
    $table->enum('status', [
        'draft',
        'review',
        'approved',
        'rejected',
        'published'
    ]);
    $table->text('catatan')->nullable();
    $table->foreignId('verified_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();
    $table->timestamps();

    $table->index(['dokumen_hukum_id', 'status']);
});
```

**Model Relation:**

```php
// DokumenHukum.php
public function verifikasi()
{
    return $this->hasMany(DokumenVerifikasi::class, 'dokumen_hukum_id')
        ->orderByDesc('created_at');
}

public function latestVerifikasi()
{
    return $this->hasOne(DokumenVerifikasi::class, 'dokumen_hukum_id')
        ->latestOfMany();
}
```

---

### C. Prioritas Rendah (Nice-to-Have)

#### 4.7 Tabel Urusan Pemerintahan

**Migration:**

```php
Schema::create('ref_urusan_pemerintahan', function (Blueprint $table) {
    $table->id();
    $table->string('nama', 255)->unique();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

---

#### 4.8 Abstrak Terstruktur

Daripada mengikuti pola ILDIS yang kurang fleksibel (hardcoded `isi_abstrak_1` sampai `isi_abstrak_3`), gunakan pendekatan kolom text:

**Migration:**

```php
Schema::table('dokumen_hukums', function (Blueprint $table) {
    $table->text('abstrak_teks')->nullable()->after('abstrak'); // Isi abstrak (text content)
    $table->text('catatan_dokumen')->nullable()->after('abstrak_teks'); // Catatan umum
});
```

---

## 5. Arsitektur Database yang Direkomendasikan

```
dokumen_hukums (tabel utama - sudah ada)
├── ref_jenis_dokumen (FK - sudah ada ✅)
├── ref_bidang_hukum (FK - sudah ada ✅)
├── ref_subjek via dokumen_subjek (M:N - sudah ada ✅)
├── dokumen_relasi (sudah ada ✅)
│
├── dokumen_lampiran (BARU - multi-file) ⭐ Prioritas Tinggi
├── dokumen_status_history (BARU - riwayat status) ⭐ Prioritas Tinggi
├── kolom integrasi JDIHN (BARU) ⭐ Prioritas Tinggi
│
├── ref_pengarang via dokumen_pengarang (BARU - M:N) 🔶 Prioritas Sedang
├── dokumen_verifikasi (BARU - workflow) 🔶 Prioritas Sedang
├── kolom tambahan (pemrakarsa, inisiatif, dll) 🔶 Prioritas Sedang
│
├── ref_urusan_pemerintahan (BARU - referensi) 🔷 Prioritas Rendah
└── kolom abstrak_teks & catatan (BARU) 🔷 Prioritas Rendah
```

---

## 6. Urutan Implementasi yang Disarankan

### Fase 1 — Kepatuhan JDIHN
1. Buat tabel `dokumen_lampiran` + migrasi data dari kolom `lampiran`
2. Buat tabel `dokumen_status_history`
3. Tambah kolom integrasi JDIHN (`is_synced_jdihn`, `last_synced_at`, `jdihn_id`)

### Fase 2 — Kelengkapan Data
4. Buat tabel `ref_pengarang` + `dokumen_pengarang`
5. Tambah kolom `pemrakarsa`, `inisiatif`, `sumber_perolehan`, `is_published`
6. Buat tabel `dokumen_verifikasi`

### Fase 3 — Penyempurnaan
7. Buat tabel `ref_urusan_pemerintahan`
8. Tambah kolom `abstrak_teks` dan `catatan_dokumen`
9. Cleanup kolom legacy yang tidak terpakai

---

## 7. Catatan Penting

- **Backward Compatibility:** Semua perubahan harus backward-compatible. Kolom lama (`lampiran`, `abstrak`, `bidangHukum`, `subjek`) jangan langsung dihapus, tapi di-deprecate secara bertahap.
- **Data Migration:** Setiap penambahan tabel baru harus disertai script migrasi data dari kolom/tabel lama.
- **API Compatibility:** Pastikan API response tetap konsisten selama transisi.
- **Indexing:** Semua foreign key dan kolom yang sering di-query harus memiliki index.
- **Soft Delete:** Pertimbangkan penggunaan soft delete pada tabel utama untuk audit trail.

---

*Dokumen ini dibuat berdasarkan analisis perbandingan database ILDIS (dbdiagram.io export) dengan struktur database JDIH BNPB saat ini.*

*Terakhir diperbarui: 22 Mei 2026*
