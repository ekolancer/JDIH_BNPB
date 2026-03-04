<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Support\Enums\IconPosition;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // --- 1️⃣ Jumlah semua dokumen ---
        $totalDocs = DokumenHukum::count();

        // --- 2️⃣ Jumlah dokumen lengkap (cek file fisik dengan cache 10 menit) ---
        $docsWithFile = cache()->remember(
            'docs_with_file',
            now()->addMinutes(10),
            fn() => $this->countDocsWithActualFile()
        );

        // --- 3️⃣ Jumlah total unduhan ---
        $downloadCount = DB::table('dokumen_hukums')->sum('download_count');

        // 📊 Ambil data 14 hari terakhir agar bisa bandingkan minggu ke minggu
        $downloadsByDate = DB::table('dokumen_hukums')
            ->selectRaw('DATE(updated_at) as date, SUM(download_count) as total')
            ->where('updated_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // 🧮 Hitung 7 hari terakhir vs minggu sebelumnya
        $downloadsThisWeek = $downloadsByDate->slice(-7)->sum();
        $downloadsLastWeek = $downloadsByDate->slice(0, -7)->sum();

        // 📈 Persentase perubahan
        $downloadChange = $this->calcPercentageChange($downloadsLastWeek, $downloadsThisWeek);

        // 🔢 Data chart (7 hari terakhir)
        $downloadsChart = array_pad($downloadsByDate->slice(-7)->values()->toArray(), 7, 0);

        // 🧠 Metadata Health Score
        $healthScoreData = cache()->remember(
            'metadata_health_score',
            now()->addMinutes(10),
            fn() => $this->calculateHealthScore()
        );

        $healthScore = $healthScoreData['score'];
        $healthTrend = $healthScoreData['trend'];
        $healthChart = $healthScoreData['chart'];

        return [
            // 📘 Total Dokumen
            Stat::make('Total', number_format($totalDocs))
                ->description('Dokumen Hukum Terdaftar')
                ->descriptionIcon('heroicon-o-cloud-arrow-down', IconPosition::After)
                ->color('primary')
                ->chart([$totalDocs, $totalDocs, $totalDocs, $totalDocs, $totalDocs, $totalDocs, $totalDocs]),

            // 📂 Dokumen Lengkap
            Stat::make('Total', number_format($docsWithFile))
                ->description('Dokumen Lengkap')
                ->descriptionIcon('heroicon-o-hand-thumb-up', IconPosition::After)
                ->color('primary')
                ->chart([$docsWithFile, $docsWithFile, $docsWithFile, $docsWithFile, $docsWithFile, $docsWithFile, $docsWithFile]),

            // ⬇️ Total Unduhan (dengan tren minggu ini)
            Stat::make('Total Unduhan', number_format($downloadCount))
                ->description(($downloadChange >= 0 ? 'Naik ' : 'Turun ') . abs($downloadChange) . '% dari minggu lalu')
                ->descriptionIcon($downloadChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($downloadChange >= 0 ? 'success' : 'danger')
                ->chart($downloadsChart),

            // 🧠 Metadata Health Score
            Stat::make('Health Score', $healthScore . '%')
                ->description(($healthTrend >= 0 ? 'Naik ' : 'Turun ') . abs($healthTrend) . '% dari minggu lalu')
                ->descriptionIcon($healthTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color(
                    $healthScore >= 80 ? 'success' : ($healthScore >= 60 ? 'warning' : 'danger')
                )
                ->chart($healthChart),
        ];
    }

    /**
     * Hitung dokumen yang benar-benar memiliki file di storage.
     */
    protected function countDocsWithActualFile(): int
    {
        $disk = Storage::disk('public');
        $count = 0;

        foreach (DokumenHukum::whereNotNull('lampiran')->where('lampiran', '<>', '')->cursor() as $dok) {
            $path = ltrim($dok->lampiran, '/');
            if ($disk->exists($path)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Hitung persentase perubahan antar dua periode.
     */
    protected function calcPercentageChange(int $old, int $new): int
    {
        if ($old === 0 && $new === 0) return 0;
        if ($old === 0) return 100;
        return (int) round((($new - $old) / $old) * 100);
    }

    /**
     * Hitung Metadata Health Score
     */
    protected function calculateHealthScore(): array
    {
        $total = DokumenHukum::count();

        if ($total === 0) {
            return [
                'score' => 0,
                'trend' => 0,
                'chart' => [0, 0, 0, 0, 0, 0, 0],
            ];
        }

        $docs = DokumenHukum::withCount('subjekRef')->get();

        $totalScore = 0;

        foreach ($docs as $doc) {
            $score = 0;

            // ✅ Ada File (50%)
            if (!empty($doc->lampiran) && Storage::disk('public')->exists(ltrim($doc->lampiran, '/'))) {
                $score += 50;
            }

            // ✅ Ada Abstrak (25%)
            if (!empty($doc->abstrak)) {
                $score += 25;
            }

            // ✅ Subjek Hukum >= 2 (25%)
            if ($doc->subjek_ref_count >= 2) {
                $score += 25;
            }

            $totalScore += $score;
        }

        $finalScore = (int) round($totalScore / $total);

        // --- Trend 7 hari terakhir ---
        $thisWeek = DokumenHukum::where('created_at', '>=', now()->subDays(7))
            ->withCount('subjekRef')
            ->get();

        $lastWeek = DokumenHukum::whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])
            ->withCount('subjekRef')
            ->get();

        $thisScore = $this->averageHealth($thisWeek);
        $lastScore = $this->averageHealth($lastWeek);

        $trend = $this->calcPercentageChange($lastScore, $thisScore);

        // Simple static chart (7 points)
        $chart = array_fill(0, 7, $finalScore);

        return [
            'score' => $finalScore,
            'trend' => $trend,
            'chart' => $chart,
        ];
    }

    // Hitung rata-rata health score untuk koleksi dokumen tertentu
    protected function averageHealth($collection): int
    {
        if ($collection->count() === 0) return 0;

        $totalScore = 0;

        foreach ($collection as $doc) {
            $score = 0;

            if (!empty($doc->lampiran) && Storage::disk('public')->exists(ltrim($doc->lampiran, '/'))) {
                $score += 50;
            }

            if (!empty($doc->abstrak)) {
                $score += 25;
            }

            if ($doc->subjek_ref_count >= 2) {
                $score += 25;
            }

            $totalScore += $score;
        }

        return (int) round($totalScore / $collection->count());
    }
}
