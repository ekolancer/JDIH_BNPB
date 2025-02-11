<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class BreadcrumbComposer
{
    public function compose(View $view)
    {
        $segments = request()->segments();
        $breadcrumbs = [];
        $url = '';

        foreach ($segments as $segment) {
            $url .= '/' . $segment;
            $breadcrumbs[] = [
                'name' => ucfirst(str_replace('-', ' ', $segment)),
                'url' => url($url)
            ];
        }

        // Ambil nama controller saat ini
        $currentController = Route::current() ? class_basename(Route::current()->controller) : null;

        // Tentukan judul berdasarkan controller
        $titles = [
            'DokumenController' => 'PRODUK HUKUM',
            'DashboardController' => 'PROFILE JDIH'
        ];

        // Gunakan judul sesuai controller, jika tidak ditemukan pakai "Publikasi"
        $pageTitle = $titles[$currentController] ?? 'PUBLIKASI';

        $view->with(compact('breadcrumbs', 'pageTitle'));
    }
}