<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\DokumenHukum;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DokumenHukumsImport implements ToModel, WithHeadingRow
{
    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Generate slug berdasarkan tipe dokumen
        $slug = $this->generateSlug(
            $row['tipeDok'] ?? null,
            $row['singkatan'] ?? null,
            $row['nomor'] ?? null,
            $row['tahun'] ?? null,
            $row['judul'] ?? null
        );

        return new DokumenHukum([
            // Sesuaikan dengan nama kolom di file import excel
            'tipeDok' => $row['tipe_dok'],
            'jenisDok' => $row['jenis_dok'],
            'judul' => $row['judul'],
            'slug' => $slug,
            'teuBadan' => $row['teu_badan'],
            'nomor' => $row['nomor'],
            'tahun' => $row['tahun'],
            'singkatan' => $row['singkatan'],
            'tempat' => $row['tempat'],
            'penerbit' => $row['penerbit'],
            'tglPenetapan' => $row['tgl_penetapan'],
            'tglPengundangan' => $row['tgl_pengundangan'],
            'tglDiBacakan' => $row['tgl_di_bacakan'],
            'nomorPanggil' => $row['nomor_panggil'],
            'cetakanEdisi' => $row['cetakan_edisi'],
            'deskripsiFisik' => $row['deskripsi_fisik'],
            'subjek' => $row['subjek'],
            'sumber' => $row['sumber'],
            'isbn' => $row['isbn'],
            'status' => $row['status'],
            'bahasa' => $row['bahasa'],
            'lokasi' => $row['lokasi'],
            'bidangHukum' => $row['bidang_hukum'],
            'lampiran' => $row['lampiran'],
            'abstrak' => $row['abstrak'],
            'nomorIndukBuku' => $row['nomor_induk_buku'],
            'penandatangan' => $row['penandatangan'],
            'download_count' => $row['download_count'],
        ]);
    }

    /**
     * Generate slug berdasarkan tipe dokumen.
     */
    private function generateSlug($tipeDok, $singkatan, $nomor, $tahun, $judul)
    {
        if (in_array(strtolower($tipeDok), ['peraturan', 'putusan'])) {
            // Jika tipe dokumen adalah Peraturan atau Putusan
            return Str::slug("{$singkatan}-nomor-{$nomor}-tahun-{$tahun}");
        } elseif (in_array(strtolower($tipeDok), ['monografi', 'artikel'])) {
            // Jika tipe dokumen adalah Monografi atau Artikel
            return Str::slug($judul);
        }
    }
}
