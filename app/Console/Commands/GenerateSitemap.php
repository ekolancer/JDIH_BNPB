<?php

namespace App\Console\Commands;

use App\Models\Berita;
use App\Models\DokumenHukum;
use App\Models\Infografis;
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
        $urls = [];

        // Halaman statis
        $urls[] = $this->urlEntry($baseUrl . '/', now()->toIso8601String(), '1.00');
        $urls[] = $this->urlEntry($baseUrl . '/berita', now()->toIso8601String(), '0.80');
        $urls[] = $this->urlEntry($baseUrl . '/infografis', now()->toIso8601String(), '0.80');
        $urls[] = $this->urlEntry($baseUrl . '/dokumen', now()->toIso8601String(), '0.80');

        // Halaman profil
        $profilSlugs = ['about', 'visi-misi', 'SOTK', 'faq'];
        foreach ($profilSlugs as $slug) {
            $urls[] = $this->urlEntry($baseUrl . '/profil/' . $slug, now()->toIso8601String(), '0.60');
        }

        // Dokumen per tipe
        $tipes = \App\Models\RefTipeDokumen::active()->ordered()->pluck('kode');
        foreach ($tipes as $kode) {
            $urls[] = $this->urlEntry(
                $baseUrl . '/dokumen?type=' . strtolower($kode),
                now()->toIso8601String(),
                '0.70'
            );
        }

        // Semua dokumen hukum
        $dokumen = DokumenHukum::select('slug', 'tipe_dokumen_id', 'updated_at')
            ->with('tipeDokumen:id,kode')
            ->orderByDesc('updated_at')
            ->get();

        $this->info("  → {$dokumen->count()} dokumen hukum");

        foreach ($dokumen as $doc) {
            $urls[] = $this->urlEntry(
                $baseUrl . '/dokumen/' . $doc->tipe_kode . '/' . $doc->slug,
                $doc->updated_at->toIso8601String(),
                '0.70'
            );
        }

        // Semua berita aktif
        $berita = Berita::where('status', 1)
            ->select('slug', 'updated_at')
            ->orderByDesc('updated_at')
            ->get();

        $this->info("  → {$berita->count()} berita");

        foreach ($berita as $item) {
            $urls[] = $this->urlEntry(
                $baseUrl . '/berita/' . $item->slug,
                $item->updated_at->toIso8601String(),
                '0.60'
            );
        }

        // Semua infografis aktif
        $infografis = Infografis::where('status', 1)
            ->select('slug', 'updated_at')
            ->orderByDesc('updated_at')
            ->get();

        $this->info("  → {$infografis->count()} infografis");

        foreach ($infografis as $item) {
            $urls[] = $this->urlEntry(
                $baseUrl . '/infografis/' . $item->slug,
                $item->updated_at->toIso8601String(),
                '0.60'
            );
        }

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        $xml .= implode('', $urls);
        $xml .= '</urlset>' . PHP_EOL;

        File::put(public_path('sitemap.xml'), $xml);

        $total = count($urls);
        $this->info("✅ Sitemap generated: {$total} URLs → public/sitemap.xml");

        return Command::SUCCESS;
    }

    private function urlEntry(string $loc, string $lastmod, string $priority): string
    {
        return "  <url>\n"
            . "    <loc>{$loc}</loc>\n"
            . "    <lastmod>{$lastmod}</lastmod>\n"
            . "    <priority>{$priority}</priority>\n"
            . "  </url>\n";
    }
}
