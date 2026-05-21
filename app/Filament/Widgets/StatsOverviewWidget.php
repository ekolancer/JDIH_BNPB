<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use App\Models\RefSubjek;
use Filament\Widgets\Widget;

class StatsOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.stats-overview-widget';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function getStats(): array
    {
        $totalDokumen   = DokumenHukum::count();
        $totalSubjek    = RefSubjek::count();
        $dokumenLengkap = $this->countDokumenWithFile();
        $totalDownload  = (int) DokumenHukum::sum('download_count');

        // Persentase kelengkapan lampiran
        $pctLengkap = $totalDokumen > 0
            ? round(($dokumenLengkap / $totalDokumen) * 100)
            : 0;

        return [
            [
                'label'       => 'Total Dokumen',
                'value'       => number_format($totalDokumen),
                'raw'         => $totalDokumen,
                'icon'        => 'document-text',
                'color'       => 'blue',
                'description' => 'Seluruh dokumen hukum',
                'trend'       => null,
                'progress'    => null,
            ],
            [
                'label'       => 'Subjek Hukum',
                'value'       => number_format($totalSubjek),
                'raw'         => $totalSubjek,
                'icon'        => 'tag',
                'color'       => 'amber',
                'description' => 'Total subjek terdaftar',
                'trend'       => null,
                'progress'    => null,
            ],
            [
                'label'       => 'Lampiran Lengkap',
                'value'       => number_format($dokumenLengkap),
                'raw'         => $dokumenLengkap,
                'icon'        => 'document-check',
                'color'       => 'emerald',
                'description' => "{$pctLengkap}% file tersedia di storage",
                'trend'       => null,
                'progress'    => $pctLengkap,
            ],
            [
                'label'       => 'Total Download',
                'value'       => $this->formatNumber($totalDownload),
                'raw'         => $totalDownload,
                'icon'        => 'arrow-down-tray',
                'color'       => 'violet',
                'description' => 'Unduhan keseluruhan',
                'trend'       => null,
                'progress'    => null,
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
            $disk = \Illuminate\Support\Facades\Storage::disk('public');

            foreach ($docs as $path) {
                if ($disk->exists($path)) {
                    $count++;
                }
            }

            return $count;
        });
    }

    private function formatNumber(int $number): string
    {
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M';
        }
        if ($number >= 1000) {
            return number_format($number / 1000, 1) . 'K';
        }
        return number_format($number);
    }
}
