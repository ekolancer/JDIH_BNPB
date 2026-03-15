<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Str;

class BreadcrumbComposer
{
    public function compose(View $view)
    {
        $segments = request()->segments();
        $breadcrumbs = [];
        $currentPath = '';

        $acronyms = ['faq', 'jdih', 'bnpb'];

        foreach ($segments as $index => $segment) {
            // Logika khusus untuk Produk Hukum (Urutan Segmen ke-2)
            // Route: /dokumen/{tipe}/{slug}
            if ($segments[0] === 'dokumen' && $index === 1) {
                // Jangan tambahkan ke path URL, tapi buat URL dengan Query String
                $name = Str::title(str_replace('-', ' ', $segment));
                $url = url('/dokumen?tipeDok=' . Str::studly($segment));

                // Update currentPath agar segment selanjutnya (slug) 
                // tetap mengarah ke path /dokumen/Peraturan/slug
                $currentPath .= '/' . $segment;
            } else {
                $currentPath .= '/' . $segment;
                $url = url($currentPath);

                // Labeling logic
                if ($segment === 'dokumen') {
                    $name = 'Produk Hukum';
                } elseif (in_array(strtolower($segment), $acronyms)) {
                    $name = strtoupper($segment);
                } else {
                    $name = Str::title(str_replace('-', ' ', $segment));
                }
            }

            $breadcrumbs[] = [
                'name' => $name,
                'url' => $url
            ];
        }

        $view->with([
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => $this->determinePageTitle($segments)
        ]);
    }

    private function determinePageTitle($segments)
    {
        $firstSegment = $segments[0] ?? '';
        return match ($firstSegment) {
            'dokumen'               => 'PRODUK HUKUM',
            'berita', 'infografis'  => 'INFORMASI HUKUM',
            default                 => 'INFORMASI PUBLIK',
        };
    }
}
