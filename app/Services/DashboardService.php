<?php

namespace App\Services;

use App\Models\Berita;
use App\Models\DokumenHukum;
use App\Models\Infografis;
use App\Models\Visitor;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Ambil semua data yang dibutuhkan homepage (cached 5 menit).
     */
    public function getHomepageData(): array
    {
        return Cache::remember('homepage_data', 300, function () {
            return [
                'jumlahDokumen' => DokumenHukum::countByType(),
                'visitorStats' => Visitor::getVisitorStats(),
                'peraturanTerbaru' => DokumenHukum::latestPeraturan(4)->get(),
                'peraturanPopuler' => DokumenHukum::mostViewedPeraturan(8)->get(),
                'dokumenAttributes' => DokumenHukum::getUniqueValues(['tipeDok', 'ref_jenis_dokumen_id']),
                'berita' => Berita::getBeritaActive(),
                'infografis' => Infografis::getInfografisActive(),
            ];
        });
    }

    /**
     * Invalidate homepage cache (dipanggil saat data berubah).
     */
    public function clearCache(): void
    {
        Cache::forget('homepage_data');
    }
}
