<?php

namespace App\Console\Commands;

use App\Models\Berita;
use App\Models\DokumenHukum;
use App\Models\Infografis;
use App\Models\RefTipeDokumen;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap.xml dari data database (dokumen, berita, infografis)';

    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $baseUrl = rtrim(config('app.url'), '/');
        $path = public_path('sitemap.xml');
        $total = 0;

        File::put($path, '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL);
        File::append($path, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL);

        $writeUrl = function (string $loc, string $lastmod, string $priority) use ($path, &$total): void {
            File::append($path, $this->urlEntry($loc, $lastmod, $priority));
            $total++;
        };

        $now = now()->toIso8601String();

        // Halaman statis
        $writeUrl($baseUrl.'/', $now, '1.00');
        $writeUrl($baseUrl.'/berita', $now, '0.80');
        $writeUrl($baseUrl.'/infografis', $now, '0.80');
        $writeUrl($baseUrl.'/dokumen', $now, '0.80');

        // Halaman profil
        $profilSlugs = ['about', 'visi-misi', 'SOTK', 'faq'];
        foreach ($profilSlugs as $slug) {
            $writeUrl($baseUrl.'/profil/'.$slug, $now, '0.60');
        }

        // Dokumen per tipe
        $tipes = RefTipeDokumen::active()->ordered()->pluck('kode');
        foreach ($tipes as $kode) {
            $writeUrl(
                $baseUrl.'/dokumen?type='.strtolower($kode),
                $now,
                '0.70'
            );
        }

        // Semua dokumen hukum
        $dokumenCount = 0;
        DokumenHukum::select('id', 'slug', 'tipe_dokumen_id', 'updated_at')
            ->with('tipeDokumen:id,kode')
            ->orderBy('id')
            ->chunkById(500, function ($dokumens) use ($baseUrl, $writeUrl, &$dokumenCount) {
                foreach ($dokumens as $doc) {
                    $writeUrl(
                        $baseUrl.'/dokumen/'.$doc->tipe_kode.'/'.$doc->slug,
                        $doc->updated_at->toIso8601String(),
                        '0.70'
                    );
                    $dokumenCount++;
                }
            });

        $this->info("  → {$dokumenCount} dokumen hukum");

        // Semua berita aktif
        $beritaCount = 0;
        Berita::where('status', 1)
            ->select('id', 'slug', 'updated_at')
            ->orderBy('id')
            ->chunkById(500, function ($beritas) use ($baseUrl, $writeUrl, &$beritaCount) {
                foreach ($beritas as $item) {
                    $writeUrl(
                        $baseUrl.'/berita/'.$item->slug,
                        $item->updated_at->toIso8601String(),
                        '0.60'
                    );
                    $beritaCount++;
                }
            });

        $this->info("  → {$beritaCount} berita");

        // Semua infografis aktif
        $infografisCount = 0;
        Infografis::where('status', 1)
            ->select('id', 'slug', 'updated_at')
            ->orderBy('id')
            ->chunkById(500, function ($infografis) use ($baseUrl, $writeUrl, &$infografisCount) {
                foreach ($infografis as $item) {
                    $writeUrl(
                        $baseUrl.'/infografis/'.$item->slug,
                        $item->updated_at->toIso8601String(),
                        '0.60'
                    );
                    $infografisCount++;
                }
            });

        $this->info("  → {$infografisCount} infografis");

        File::append($path, '</urlset>'.PHP_EOL);

        $this->info("✅ Sitemap generated: {$total} URLs → public/sitemap.xml");

        return Command::SUCCESS;
    }

    private function urlEntry(string $loc, string $lastmod, string $priority): string
    {
        return "  <url>\n"
            ."    <loc>{$loc}</loc>\n"
            ."    <lastmod>{$lastmod}</lastmod>\n"
            ."    <priority>{$priority}</priority>\n"
            ."  </url>\n";
    }
}
