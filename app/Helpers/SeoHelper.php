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
            // 1. DOKUMEN HUKUM
            // =========================
            'DokumenHukum' => self::dokumen($data),

            // =========================
            // 2. BERITA
            // =========================
            'Berita' => self::berita($data),

            // =========================
            // 3. INFOGRAFIS
            // =========================
            'Infografis' => self::infografis($data),

            // =========================
            // DEFAULT (fallback aman)
            // =========================
            default => self::default($data),
        };
    }

    // =========================
    // 1. DOKUMEN HUKUM
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
    // 2. BERITA (OPTIMIZED)
    // =========================
    private static function berita($berita)
    {
        return [
            'title' => "{$berita->judul} - JDIH BNPB",
            'description' => Str::limit(strip_tags($berita->konten), 160),
            'image' => $berita->gambar
                ? asset('storage/' . $berita->gambar)
                : asset('images/og_image.png'),
            'url' => url()->current(),
            'type' => 'article',
        ];
    }

    // =========================
    // 3. INFOGRAFIS
    // =========================
    private static function infografis($infografis)
    {
        return [
            'title' => "{$infografis->judul} - Infografis JDIH BNPB",
            'description' => "Infografis hukum: {$infografis->judul}",
            'image' => $infografis->gambar
                ? asset('storage/' . $infografis->gambar)
                : asset('images/og_image.png'),
            'url' => url()->current(),
            'type' => 'article',
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
