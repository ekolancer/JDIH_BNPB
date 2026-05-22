<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use Filament\Widgets\Widget;

class TopDownloadedDokumenWidget extends Widget
{
    protected string $view = 'filament.widgets.top-downloaded-dokumen-widget';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 1;

    public function getTopDownloaded(): array
    {
        return DokumenHukum::with('jenisDokumen')
            ->orderByDesc('download_count')
            ->limit(5)
            ->get()
            ->map(fn($doc) => [
                'judul' => $doc->judul,
                'jenis' => $doc->jenisDokumen->singkatan ?? '-',
                'nomor' => $doc->nomor,
                'tahun' => $doc->tahun,
                'downloads' => $doc->download_count,
                'slug' => $doc->slug,
            ])
            ->toArray();
    }
}
