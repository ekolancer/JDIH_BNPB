<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SeoHelper
{
    public static function meta($data)
    {
        $type = class_basename($data);

        return match ($type) {

            // =========================
            // 📄 DOKUMEN (existing logic)
            // =========================
            'DokumenHukum' => self::dokumen($data),

            // =========================
            // 📰 BERITA (baru)
            // =========================
            'Berita' => self::berita($data),

            // =========================
            // DEFAULT (fallback aman)
            // =========================
            default => self::default($data),
        };
    }

    // =========================
    // 📄 DOKUMEN (TIDAK DIUBAH)
    // =========================
    private static function dokumen($dokumen)
    {
        return [
            'title' => "{$dokumen->jenisDokumen?->singkatan} Nomor {$dokumen->nomor} Tahun {$dokumen->tahun} - JDIH BNPB",
            'description' => Str::limit(strip_tags($dokumen->judul), 160),
            'image' => asset('images/og_image.png'),
            'url' => url()->current(),
        ];
    }

    // =========================
    // 📰 BERITA (OPTIMIZED)
    // =========================
    private static function berita($berita)
    {
        return [
            'title' => "{$berita->judul} - JDIH BNPB",
            'description' => Str::limit(strip_tags($berita->isi), 160),
            'image' => $berita->gambar
                ? asset('storage/' . $berita->gambar)
                : asset('images/og_image.png'),
            'url' => url()->current(),
            'type' => 'article', // 🔥 tambahkan
        ];
    }

    // =========================
    // 🔰 DEFAULT (SAFETY NET)
    // =========================
    private static function default($data)
    {
        return [
            'title' => config('app.name'),
            'description' => 'Website resmi JDIH BNPB',
            'image' => asset('images/og_image.png'),
            'url' => url()->current(),
        ];
    }
}
