<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use Filament\Widgets\Widget;

class TopViewedDokumenWidget extends Widget
{
    protected string $view = 'filament.widgets.top-viewed-dokumen-widget';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    public function getTopViewed(): array
    {
        return DokumenHukum::with('jenisDokumen')
            ->orderByDesc('view_count')
            ->limit(5)
            ->get()
            ->map(fn($doc) => [
                'judul' => $doc->judul,
                'jenis' => $doc->jenisDokumen->singkatan ?? '-',
                'nomor' => $doc->nomor,
                'tahun' => $doc->tahun,
                'views' => $doc->view_count,
                'slug' => $doc->slug,
            ])
            ->toArray();
    }
}
