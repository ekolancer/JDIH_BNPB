<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use App\Models\RefJenisDokumen;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class KomposisiProdukHukumChart extends ChartWidget
{
    protected static ?string $heading = 'Komposisi Produk Hukum';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '260px';
    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        $years = DokumenHukum::select('tahun')
            ->whereNotNull('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        $filters = ['all' => 'Semua Tahun'];
        foreach ($years as $year) {
            $filters[$year] = $year;
        }

        return $filters;
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $query = DokumenHukum::query();

        if ($activeFilter !== 'all') {
            $query->where('tahun', $activeFilter);
        }

        $data = $query->select('ref_jenis_dokumen_id', DB::raw('count(*) as count'))
            ->groupBy('ref_jenis_dokumen_id')
            ->with('jenisDokumen')
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->jenisDokumen ? $item->jenisDokumen->nama : 'Tidak Diketahui',
                    'count' => $item->count,
                ];
            });

        $labels = $data->pluck('nama')->toArray();
        $counts = $data->pluck('count')->toArray();
        $total = array_sum($counts);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Dokumen',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF6384',
                        '#C9CBCF',
                        '#4BC0C0',
                        '#FF6384',
                    ],
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
            'cutout' => '70%',
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
                        'label' => fn($ctx) => $ctx->label . ': ' . $ctx->raw,
                    ],
                ],
            ],
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
            'animation' => [
                'onComplete' => function ($chart) {
                    $ctx = $chart->ctx;
                    $width = $chart->width;
                    $height = $chart->height;
                    $total = $chart->config->_config['data']['total'] ?? 0;
                    $ctx->save();

                    $fontSize = (min($width, $height) / 100) * 5;
                    $ctx->font = "bold {$fontSize}px Poppins";
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
