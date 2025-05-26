<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //menyesuaikan base url
        $baseUrl = config('app.url', 'http://jdih.bnpb.go.id');
        // Ambil semua data dari tabel dokumen_hukums
        $data = DB::table('dokumen_hukums')->get();
        // dd($data->first());

        $result = [];
        foreach ($data as $row) {
            $slug = $row->slug;

            $item = [
                'idData' => $row->id,
                'tahun_pengundangan' => $row->tahun,
                'tanggal_pengundangan' => $row->tglPengundangan,
                'jenis' => $row->jenisDok,
                'noPeraturan' => $row->nomor,
                'judul' => $row->judul,
                'noPanggil' => $row->nomorPanggil,
                'singkatanJenis' => $row->singkatan,
                'tempatTerbit' => $row->tempat,
                'penerbit' => $row->penerbit,
                'deskripsiFisik' => $row->deskripsiFisik,
                'sumber' => $row->sumber,
                'subjek' => $row->subjek,
                'isbn' => $row->isbn,
                'status' => $row->status,
                'bahasa' => $row->bahasa,
                'bidangHukum' => $row->bidangHukum,
                'teuBadan' => $row->teuBadan,
                'nomorIndukBuku' => $row->nomorIndukBuku,
                'fileDownload' => basename($row->lampiran),
                'urlDownload' => $baseUrl . "/download-lampiran/" . $slug,
                'abstrak' => $row->abstrak,
                'urlabstrak' => is_null($row->abstrak) ? null : $baseUrl . "/download-abstrak/" . $slug,
                'urlDetailPeraturan' => $baseUrl . "/dokumen/" . $row->tipeDok . "/" . $row->slug,
                'operasi' => "4",
                'display' => "1",
            ];
            $result[] = $item;
        }

        return response()->json($result);
    }
}
