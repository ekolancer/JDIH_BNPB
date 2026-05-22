<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Helpers\SeoHelper;

class InfografisController extends Controller
{
    public function index(): View
    {
        $infografis = Infografis::where('status', 1)
            ->latest()
            ->paginate(10);

        return view('infografis.list', compact('infografis'));
    }

    public function show(string $slug): View
    {
        $infografis = Infografis::where('slug', $slug)->firstOrFail();

        DB::table('infografis')
            ->where('id', $infografis->id)
            ->increment('view_count');

        $seo = SeoHelper::meta($infografis);

        return view('infografis.detail', [
            'infografis' => $infografis,
            'seo' => $seo,
        ]);
    }
}
