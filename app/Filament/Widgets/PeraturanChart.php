<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use Filament\Widgets\ChartWidget;

class PeraturanChart extends ChartWidget
{
    protected static ?string $heading = 'Status Peraturan';

    protected static ?int $sort = 4;
    
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $berlakuCount = DokumenHukum::where('status', 'BERLAKU')->count();
        $tidakBerlakuCount = DokumenHukum::where('status', 'TIDAK BERLAKU')->count();

        return [
            'labels' => ['Berlaku', 'Tidak Berlaku'],
            'datasets' => [
                [
                    'label' => 'Status',
                    'data' => [$berlakuCount, $tidakBerlakuCount],
                    'backgroundColor' => ['#4CAF50', '#F44336'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false, // Menghilangkan garis sumbu X
                ],
                'y' => [
                    'display' => false, // Menghilangkan garis sumbu Y
                ],
            ],
        ];
    }
}
