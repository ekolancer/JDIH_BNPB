<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use App\Models\RefJenisDokumen;
use App\Models\RefSubjek;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Storage;

class StatsOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.stats-overview-widget';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function getStats(): array
    {
        $totalDokumen = DokumenHukum::count();
        $totalSubjek = RefSubjek::count();
        $dokumenLengkap = $this->countDokumenWithFile();
        $totalJenis = RefJenisDokumen::count();

        // Persentase kelengkapan lampiran
        $pctLengkap = $totalDokumen > 0
            ? round(($dokumenLengkap / $totalDokumen) * 100)
            : 0;

        return [
            [
                'label' => 'Dokumen Hukum',
                'value' => number_format($totalDokumen),
                'raw' => $totalDokumen,
                'icon' => 'document-text',
                'color' => 'blue',
                'description' => 'Total dokumen hukum terdaftar',
                'trend' => null,
                'progress' => null,
            ],
            [
                'label' => 'Jenis Dokumen',
                'value' => number_format($totalJenis),
                'raw' => $totalJenis,
                'icon' => 'tag',
                'color' => 'violet',
                'description' => 'Total jenis dokumen terdaftar',
                'trend' => null,
                'progress' => null,
            ],
            [
                'label' => 'Subjek Hukum',
                'value' => number_format($totalSubjek),
                'raw' => $totalSubjek,
                'icon' => 'tag',
                'color' => 'amber',
                'description' => 'Total subjek terdaftar',
                'trend' => null,
                'progress' => null,
            ],
            [
                'label' => 'Lampiran Dokumen',
                'value' => number_format($dokumenLengkap),
                'raw' => $dokumenLengkap,
                'icon' => 'document-check',
                'color' => 'emerald',
                'description' => 'Total dokumen yang memiliki lampiran',
                'trend' => null,
                'progress' => $pctLengkap,
            ],
        ];
    }

    /**
     * Hitung dokumen yang file lampiran-nya benar-benar ada di disk.
     * Di-cache 10 menit agar tidak berat.
     */
    private function countDokumenWithFile(): int
    {
        return cache()->remember('widget_dokumen_file_count', 600, function () {
            $docs = DokumenHukum::where('lampiran', '!=', '-')
                ->whereNotNull('lampiran')
                ->where('lampiran', '!=', '')
                ->pluck('lampiran');

            $count = 0;
            $disk = Storage::disk('public');

            foreach ($docs as $path) {
                if ($disk->exists($path)) {
                    $count++;
                }
            }

            return $count;
        });
    }
}
