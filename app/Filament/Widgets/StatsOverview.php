<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use App\Models\Visitor;
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

        // --- 4️⃣ Jumlah pengunjung unik total ---
        $uniqueVisitors = Visitor::distinct('ip_address')->count('ip_address');

        // 📊 Ambil data 14 hari terakhir agar bisa bandingkan minggu ke minggu
        $downloadsByDate = DB::table('dokumen_hukums')
            ->selectRaw('DATE(updated_at) as date, SUM(download_count) as total')
            ->where('updated_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $visitorsByDate = Visitor::selectRaw('DATE(created_at) as date, COUNT(DISTINCT ip_address) as total')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // 🧮 Hitung 7 hari terakhir vs minggu sebelumnya
        $downloadsThisWeek = $downloadsByDate->slice(-7)->sum();
        $downloadsLastWeek = $downloadsByDate->slice(0, -7)->sum();
        $visitorsThisWeek = $visitorsByDate->slice(-7)->sum();
        $visitorsLastWeek = $visitorsByDate->slice(0, -7)->sum();

        // 📈 Persentase perubahan
        $downloadChange = $this->calcPercentageChange($downloadsLastWeek, $downloadsThisWeek);
        $visitorChange = $this->calcPercentageChange($visitorsLastWeek, $visitorsThisWeek);

        // 🔢 Data chart (7 hari terakhir)
        $downloadsChart = array_pad($downloadsByDate->slice(-7)->values()->toArray(), 7, 0);
        $visitorsChart = array_pad($visitorsByDate->slice(-7)->values()->toArray(), 7, 0);

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

            // 👥 Pengunjung Unik (dengan tren minggu ini)
            Stat::make('Pengunjung Unik', number_format($uniqueVisitors))
                ->description(($visitorChange >= 0 ? 'Naik ' : 'Turun ') . abs($visitorChange) . '% dari minggu lalu')
                ->descriptionIcon($visitorChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($visitorChange >= 0 ? 'success' : 'danger')
                ->chart($visitorsChart),
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
}
