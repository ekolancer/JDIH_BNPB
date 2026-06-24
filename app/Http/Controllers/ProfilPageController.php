<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\ProfilPage;
use Illuminate\View\View;

class ProfilPageController extends Controller
{
    private const SPECIAL_PAGE_HANDLERS = [
        'faq' => 'faqPage',
    ];

    /**
     * Membangun daftar menu sidebar: semua ProfilPage dari DB + entri FAQ.
     */
    private function sidebarMenus(): array
    {
        $pages = ProfilPage::orderBy('id')->get(['slug', 'title'])->map(fn ($p) => [
            'slug' => $p->slug,
            'title' => $p->title,
        ])->toArray();

        // FAQ selalu ada di akhir (data dari tabel faqs, bukan profil_pages)
        $hasFaq = collect($pages)->contains('slug', 'faq');
        if (! $hasFaq) {
            $pages[] = ['slug' => 'faq', 'title' => 'FAQ'];
        }

        return $pages;
    }

    /**
     * Menampilkan halaman profil berdasarkan slug.
     */
    public function show(string $slug): View
    {
        $menus = $this->sidebarMenus();
        $specialPage = $this->resolveSpecialPage($slug, $menus);

        if ($specialPage) {
            return $specialPage;
        }

        $page = ProfilPage::where('slug', $slug)->firstOrFail();

        return view('components.overview.page', [
            'page' => $page,
            'slug' => $slug,
            'faqs' => [],
            'menus' => $menus,
        ]);
    }

    private function resolveSpecialPage(string $slug, array $menus): ?View
    {
        $handler = self::SPECIAL_PAGE_HANDLERS[$slug] ?? null;

        if (! $handler) {
            return null;
        }

        return $this->{$handler}($slug, $menus);
    }

    private function faqPage(string $slug, array $menus): View
    {
        return view('components.overview.page', [
            'slug' => $slug,
            'page' => (object) ['title' => 'FAQ', 'content' => null, 'image' => null],
            'faqs' => Faq::orderBy('sort_order')->get(),
            'menus' => $menus,
        ]);
    }
}
