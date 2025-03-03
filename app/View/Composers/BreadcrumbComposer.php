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
        
        // Jika route adalah 'list-doc', set pageTitle menjadi 'Produk Hukum'
        if (Route::currentRouteName() == 'list-doc') {
            $titles[$currentController] = 'PRODUK HUKUM';
        }
        
        // Gunakan judul sesuai controller, jika tidak ditemukan pakai "Publikasi"
        $pageTitle = isset($titles[$currentController]) ? $titles[$currentController] : 'INFORMASI HUKUM';


        $view->with(compact('breadcrumbs', 'pageTitle'));
    }
}