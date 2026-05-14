<?php

namespace App\Filament\Pages;

use App\Services\GoogleAnalyticsService;
use Filament\Pages\Page;
use Filament\Actions\Action;

class GoogleAnalytics extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Google Analytics';

    protected static ?string $title = 'Google Analytics';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Systems';
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function getView(): string
    {
        return 'filament.pages.google-analytics';
    }

    // Date range state
    public string $dateRange = '30daysAgo';
    public string $endDate   = 'today';
    public string $period    = '30';

    // Data properties
    public array $overview        = [];
    public array $sessionsChart   = [];
    public array $topPages        = [];
    public array $trafficSources  = [];
    public array $devices         = [];
    public array $countries       = [];
    public array $browsers        = [];
    public array $newVsReturning  = [];

    public bool $loading = false;
    public ?string $error = null;

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->error = null;

        try {
            $service = app(GoogleAnalyticsService::class);

            $this->overview       = $service->getOverviewStats($this->dateRange, $this->endDate);
            $this->sessionsChart  = $service->getSessionsOverTime($this->dateRange, $this->endDate);
            $this->topPages       = $service->getTopPages($this->dateRange, $this->endDate);
            $this->trafficSources = $service->getTrafficSources($this->dateRange, $this->endDate);
            $this->devices        = $service->getDeviceBreakdown($this->dateRange, $this->endDate);
            $this->countries      = $service->getTopCountries($this->dateRange, $this->endDate);
            $this->browsers       = $service->getTopBrowsers($this->dateRange, $this->endDate);
            $this->newVsReturning = $service->getNewVsReturning($this->dateRange, $this->endDate);
        } catch (\Exception $e) {
            $this->error = 'Gagal memuat data Google Analytics: ' . $e->getMessage();
        }
    }

    public function setPeriod(string $period): void
    {
        $this->period    = $period;
        $this->dateRange = match ($period) {
            '7'   => '7daysAgo',
            '14'  => '14daysAgo',
            '30'  => '30daysAgo',
            '90'  => '90daysAgo',
            '365' => '365daysAgo',
            default => '30daysAgo',
        };
        $this->endDate = 'today';
        $this->loadData();
        $this->dispatch('ga-data-updated', data: $this->getChartData());
    }

    public function refreshData(): void
    {
        app(GoogleAnalyticsService::class)->clearCache();
        $this->loadData();
        $this->dispatch('ga-data-updated', data: $this->getChartData());

        \Filament\Notifications\Notification::make()
            ->title('Data berhasil diperbarui')
            ->success()
            ->send();
    }

    public function getChartData(): array
    {
        return [
            'sessionsChart'  => $this->sessionsChart,
            'trafficSources' => $this->trafficSources,
            'devices'        => $this->devices,
            'browsers'       => $this->browsers,
            'newVsReturning' => $this->newVsReturning,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Data')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action('refreshData'),
        ];
    }
}
