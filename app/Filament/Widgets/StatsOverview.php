<?php

namespace App\Filament\Widgets;

use App\Models\Artikel;
use App\Models\Monograf;
use App\Models\Peraturan;
use App\Models\Putusan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return [
            Stat::make('Peraturan Perundang - Undangan', Peraturan::count())
                ->description('Jumlah Peraturan')
                ->color('success'),
            Stat::make('Monografi', Monograf::count())
                ->description('Jumlah Monografi')
                ->color('success'),
            Stat::make('Putasan', Putusan::count())
                ->description('Jumlah Putusan')
                ->color('success'),
            Stat::make('Artikel', Artikel::count())
                ->description('Jumlah Artikel')
                ->color('success'),
        ];
    }
}
