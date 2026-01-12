<?php

namespace App\Filament\Pages;

use Dotswan\FilamentLaravelPulse\Widgets\PulseCache;
use Dotswan\FilamentLaravelPulse\Widgets\PulseExceptions;
use Dotswan\FilamentLaravelPulse\Widgets\PulseQueues;
use Dotswan\FilamentLaravelPulse\Widgets\PulseServers;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowOutGoingRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowQueries;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseUsage;
use Filament\Pages\Dashboard;

class SystemMonitoring extends Dashboard
{
    // 🧭 Label dan ikon di sidebar
    protected static ?string $navigationLabel = 'Monitoring';

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    // 🌐 URL untuk dashboard ini
    protected static string $routePath = 'monitoring';

    // 🧩 Judul halaman
    protected static ?string $title = 'Laravel Pulse Dashboard';

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    // 🎯 Widget Laravel Pulse yang ingin ditampilkan
    public function getWidgets(): array
    {
        return [
            PulseServers::class,
            PulseCache::class,
            PulseExceptions::class,
            PulseSlowRequests::class,
            PulseQueues::class,
            PulseSlowQueries::class,
            PulseSlowOutGoingRequests::class,
        ];
    }

    // 🔲 Jumlah kolom (grid)
    public function getColumns(): int|string|array
    {
        return 10; // Gunakan 12 kolom penuh untuk layout fleksibel
    }
}
