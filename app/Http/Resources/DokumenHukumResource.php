<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DokumenHukumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseUrl = config('app.url', 'http://jdih.bnpb.go.id');

        return [
            'idData' => $this->document_uid,
            'tahun_pengundangan' => $this->tahun,
            'tanggal_pengundangan' => $this->tglPengundangan,
            'jenis' => $this->jenisDokumen?->nama,
            'noPeraturan' => $this->nomor,
            'judul' => $this->judul,
            'noPanggil' => $this->nomorPanggil,
            'singkatanJenis' => $this->jenisDokumen?->singkatan,
            'tempatTerbit' => $this->tempat,
            'penerbit' => $this->penerbit,
            'deskripsiFisik' => $this->deskripsiFisik,
            'sumber' => $this->sumber,
            'subjek' => $this->subjekRef->isNotEmpty()
                ? $this->subjekRef->pluck('nama')->implode('; ')
                : null,
            'isbn' => $this->isbn,
            'status' => $this->status,
            'bahasa' => $this->bahasa,
            'bidangHukum' => $this->bidangHukumRef?->nama,
            'teuBadan' => $this->teuBadan,
            'nomorIndukBuku' => $this->nomorIndukBuku,
            'fileDownload' => basename($this->lampiran),
            'urlDownload' => $this->lampiran ? $baseUrl . "/download-lampiran/" . $this->slug : null,
            'abstrak' => $this->abstrak,
            'urlabstrak' => $this->abstrak ? $baseUrl . "/download-abstrak/" . $this->slug : null,
            'urlDetailPeraturan' => $baseUrl . "/dokumen/" . $this->tipeDok . "/" . $this->slug,

            // FLAG PUU
            'operasi' => "4",
            'display' => "1",
        ];
    }
}
