<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\DokumenHukum;

class ExecutiveStats extends Widget
{
    protected string $view = 'filament.widgets.executive-stats';

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected int|string|array $columnSpan = 'full';

    public function getStats(): array
    {
        $totalDocs = DokumenHukum::count();

        $download = DB::table('dokumen_hukums')->sum('download_count');

        $healthScore = $this->calculateHealthScore();

        $grade = match (true) {
            $healthScore >= 90 => 'A',
            $healthScore >= 75 => 'B',
            $healthScore >= 50 => 'C',
            $healthScore >= 25 => 'D',
            default => 'E',
        };
        $problemDocs = $this->calculateProblemDocs();

        return [
            [
                'label' => 'TOTAL' . "\n" . 'DOKUMEN',
                'value' => number_format($totalDocs),
                'color' => 'blue',
                'icon' => 'heroicon-o-square-3-stack-3d',
            ],
            [
                'label' => 'TOTAL' . "\n" . 'UNDUHAN',
                'value' => number_format($download),
                'color' => 'cyan',
                'icon' => 'heroicon-o-cloud-arrow-down',
            ],
            [
                'label' => 'DOKUMEN' . "\n" . 'BELUM LENGKAP',
                'value' => $problemDocs,
                'color' => 'amber',
                'icon' => 'heroicon-o-clipboard-document',
                'progress' => min(100, round(($problemDocs / max(1, $totalDocs)) * 100)),
            ],
            [
                'label' => 'METADATA' . "\n" . 'HEALTH SCORE',
                'value' => $grade,
                'color' => 'green',
                'icon' => 'heroicon-o-star',
                'progress' => $healthScore,
            ],
        ];
    }

    /**
     * Fungsi untuk menghitung health score.
     */
    protected function calculateHealthScore(): int
    {
        $total = DokumenHukum::count();
        if ($total === 0) return 0;

        $baseQuery = DB::table('dokumen_hukums')
            ->leftJoin('dokumen_subjek as pivot', 'dokumen_hukums.id', '=', 'pivot.dokumen_hukum_id')
            ->selectRaw("
            dokumen_hukums.id,
            MAX(CASE WHEN dokumen_hukums.lampiran IS NOT NULL AND dokumen_hukums.lampiran != '' THEN 1 ELSE 0 END) as has_file,
            MAX(CASE WHEN dokumen_hukums.abstrak IS NOT NULL AND dokumen_hukums.abstrak != '' THEN 1 ELSE 0 END) as has_abstrak,
            COUNT(pivot.subjek_id) as subjek_count
        ")
            ->groupBy('dokumen_hukums.id');

        $result = DB::query()
            ->fromSub($baseQuery, 'x')
            ->selectRaw("
            SUM(
                (has_file * 50) +
                (has_abstrak * 25) +
                (CASE 
                    WHEN subjek_count >= 2 THEN 25
                    ELSE 15
                 END)
            ) as total_score
        ")
            ->first();

        return (int) round($result->total_score / $total);
    }

    /**
     * Fungsi untuk menghitung jumlah dokumen yang memiliki file fisik.
     */
    protected function calculateProblemDocs(): int
    {
        $disk = Storage::disk('public');

        // Ambil semua lampiran dari DB
        $dbFiles = DokumenHukum::whereNotNull('lampiran')
            ->where('lampiran', '!=', '')
            ->pluck('lampiran')
            ->map(fn($path) => ltrim($path, '/'))
            ->toArray();

        // Ambil semua file yang benar-benar ada di storage
        $storageFiles = collect($disk->allFiles())
            ->toArray();

        // File yang ada di DB tapi tidak ada di storage
        $missingFiles = array_diff($dbFiles, $storageFiles);

        // Dokumen tanpa lampiran
        $emptyLampiran = DokumenHukum::whereNull('lampiran')
            ->orWhereRaw("TRIM(lampiran) = ''")
            ->count();

        return count($missingFiles) + $emptyLampiran;
    }
}
