<?php

namespace App\Helpers;

class SeoHelper
{
    public static function meta($dokumen)
    {
        $title = "{$dokumen->jenisDokumen?->singkatan} Nomor {$dokumen->nomor} Tahun {$dokumen->tahun} - JDIH BNPB";

        $description = str()->limit(strip_tags($dokumen->judul), 160);

        return [
            'title' => $title,
            'description' => $description,
            'image' => asset('images/og_image.png'), // ✅ static image
            'url' => url()->current(),
        ];
    }
}