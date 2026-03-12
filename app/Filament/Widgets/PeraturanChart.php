<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use Filament\Widgets\ChartWidget;
use Filament\Support\RawJs;

class PeraturanChart extends ChartWidget
{
    protected static ?string $heading = 'Status Peraturan Perundang-undangan';

    protected static ?int $sort = 3;
    
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $berlaku      = DokumenHukum::where('status', 'BERLAKU')->count();
        $tidakBerlaku = DokumenHukum::where('status', 'TIDAK BERLAKU')->count();

        return [
            'labels' => [
                "Berlaku ({$berlaku})",
                "Tidak Berlaku ({$tidakBerlaku})",
            ],
            'datasets' => [
                [
                    'data'                => [$berlaku, $tidakBerlaku],
                    'backgroundColor'     => ['#4CAF50', '#F44336'],
                    'hoverBackgroundColor' => ['#43A047', '#E53935'],
                    'hoverOffset'         => 10,
                    'borderWidth'         => 0,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'left',
                    'align'    => 'center',
                    'labels'   => [
                        'padding'         => 14,
                        'usePointStyle'   => true,
                        'pointStyle'      => 'rect',
                        'pointStyleWidth' => 9,
                        'font'            => ['size' => 11],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
        ];
    }

    // Gunakan extraJsOptions hanya untuk:
    // 1. Override maintainAspectRatio (tidak bisa dari PHP)
    // 2. Custom tooltip agar tidak double
    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
            maintainAspectRatio: true,
            aspectRatio: 1.8,
            plugins: {
                tooltip: {
                    callbacks: {
                        // Tampilkan label saja tanpa value (sudah ada di label)
                        label: function(context) {
                            return ' ' + context.label;
                        }
                    }
                }
            }
        }
        JS);
    }
}