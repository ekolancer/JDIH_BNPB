<?php

namespace App\Filament\Widgets;

use App\Models\Artikel;
use App\Models\DokumenHukum;
use App\Models\Monograf;
use App\Models\Peraturan;
use App\Models\Putusan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Dokumen', DokumenHukum::where('tipeDok', 'peraturan')->count())
                ->description('Peraturan Undang-Undang')
                ->color('success')
                ->descriptionIcon('heroicon-o-scale'),
            Stat::make('Jumlah Dokumen', DokumenHukum::where('tipeDok', 'monografi')->count())
                ->description('Monografi')
                ->color('success')
                ->descriptionIcon('heroicon-o-scale'),
            Stat::make('Jumlah Dokumen', DokumenHukum::where('tipeDok', 'artikel')->count())
                ->description('Artikel Hukum')
                ->color('success')
                ->descriptionIcon('heroicon-o-newspaper'),
            Stat::make('Jumlah Dokumen', DokumenHukum::where('tipeDok', 'putusan')->count())
                ->description('Putusan Pengadilan')
                ->color('success')
                ->descriptionIcon('heroicon-o-building-library'),
        ];
    }
}
