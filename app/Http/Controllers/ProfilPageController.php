<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\ProfilPage;

class ProfilPageController extends Controller
{
    /**
     * Menampilkan halaman profil berdasarkan slug.
     * Slug 'faq' ditangani secara khusus untuk menampilkan data FAQ.
     */
    public function show(string $slug)
    {
        // Halaman FAQ — data diambil dari tabel faqs
        if ($slug === 'faq') {
            return view('components.overview.page', [
                'slug' => $slug,
                'page' => (object) [
                    'title'   => 'FAQ',
                    'content' => null,
                    'image'   => null,
                ],
                'faqs' => Faq::orderBy('sort_order')->get(),
            ]);
        }

        // Halaman profil umum — data diambil dari tabel profil_pages
        $page = ProfilPage::where('slug', $slug)->firstOrFail();

        return view('components.overview.page', [
            'page' => $page,
            'slug' => $slug,
            'faqs' => [],
        ]);
    }
}
