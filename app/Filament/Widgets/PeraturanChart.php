<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use Filament\Widgets\ChartWidget;

class PeraturanChart extends ChartWidget
{
    protected static ?string $heading = 'Status Peraturan Perundang-undangan';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $berlakuCount = DokumenHukum::where('status', 'BERLAKU')->count();
        $tidakBerlakuCount = DokumenHukum::where('status', 'TIDAK BERLAKU')->count();
        $total = $berlakuCount + $tidakBerlakuCount;

        return [
            'labels' => [
                "Berlaku ({$berlakuCount})",
                "Tidak Berlaku ({$tidakBerlakuCount})",
            ],
            'datasets' => [
                [
                    'label' => 'Status Peraturan',
                    'data' => [$berlakuCount, $tidakBerlakuCount],
                    'backgroundColor' => ['#4CAF50', '#F44336'],
                    'hoverOffset' => 8,
                    'borderWidth' => 0,
                ],
            ],
            'total' => $total,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'cutout' => '70%', // Besar lubang tengah
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => fn($ctx) => $ctx->label,
                    ],
                ],
            ],
            // Hilangkan sumbu X dan Y
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
            // Gunakan plugin inline (bekerja di Filament v3)
            'onHover' => null,
            'animation' => [
                'onComplete' => function ($chart) {
                    $ctx = $chart->ctx;
                    $width = $chart->width;
                    $height = $chart->height;
                    $total = $chart->config->_config['data']['total'] ?? 0;
                    $ctx->save();

                    $fontSize = (min($width, $height) / 100) * 5;
                    $ctx->font = "bold {$fontSize}px sans-serif";
                    $ctx->fillStyle = '#333';
                    $ctx->textBaseline = 'middle';
                    $text = "Total: {$total}";
                    $textX = ($width - $ctx->measureText($text)->width) / 2;
                    $textY = $height / 2;
                    $ctx->fillText($text, $textX, $textY);

                    $ctx->restore();
                },
            ],
        ];
    }
}
