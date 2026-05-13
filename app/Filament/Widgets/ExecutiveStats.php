<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\DokumenHukum;

class ExecutiveStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected function getStats(): array
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
            Stat::make('Total Dokumen', number_format($totalDocs))
                ->icon('heroicon-o-square-3-stack-3d')
                ->color('primary'),
            Stat::make('Total Unduhan', number_format($download))
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('info'),
            Stat::make('Dokumen Belum Lengkap', $problemDocs)
                ->icon('heroicon-o-clipboard-document')
                ->color('warning'),
            Stat::make('Metadata Health Score', $grade . ' (' . $healthScore . '%)')
                ->icon('heroicon-o-star')
                ->color('success'),
        ];
    }

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

    protected function calculateProblemDocs(): int
    {
        $disk = Storage::disk('public');

        $dbFiles = DokumenHukum::whereNotNull('lampiran')
            ->where('lampiran', '!=', '')
            ->pluck('lampiran')
            ->map(fn($path) => ltrim($path, '/'))
            ->toArray();

        $storageFiles = collect($disk->allFiles())->toArray();

        $missingFiles = array_diff($dbFiles, $storageFiles);

        $emptyLampiran = DokumenHukum::whereNull('lampiran')
            ->orWhereRaw("TRIM(lampiran) = ''")
            ->count();

        return count($missingFiles) + $emptyLampiran;
    }
}
