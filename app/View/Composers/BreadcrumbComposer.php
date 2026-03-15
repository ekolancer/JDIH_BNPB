<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class BreadcrumbComposer
{
    public function compose(View $view)
    {
        $segments = request()->segments();
        $breadcrumbs = [];
        $url = '';

        // Daftar akronim agar tidak otomatis menjadi Capitalize (tetap Uppercase)
        $acronyms = ['faq', 'jdih', 'bnpb'];

        foreach ($segments as $index => $segment) {
            $url .= '/' . $segment;

            // Logika Penamaan Segment
            if ($segment === 'dokumen') {
                $name = 'Produk Hukum';
            } elseif (in_array(strtolower($segment), $acronyms)) {
                $name = strtoupper($segment);
            } else {
                // Mengubah 'peraturan-pemerintah' menjadi 'Peraturan Pemerintah'
                $name = Str::title(str_replace('-', ' ', $segment));
            }

            $breadcrumbs[] = [
                'name' => $name,
                'url' => url($url)
            ];
        }

        $view->with([
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => $this->determinePageTitle($segments)
        ]);
    }

    private function determinePageTitle($segments)
    {
        $routeName = Route::currentRouteName();

        // Jika berada di halaman detail dokumen (segmen ke-3 ada)
        if ($routeName === 'dokumen.detail' || count($segments) >= 3) {
            return 'DETAIL DOKUMEN';
        }

        // Jika berada di list tipe dokumen (segmen ke-2 ada)
        if ($routeName === 'dokumen.tipe' || count($segments) === 2) {
            return strtoupper(str_replace('-', ' ', $segments[1] ?? 'PRODUK HUKUM'));
        }

        return 'PRODUK HUKUM';
    }
}
