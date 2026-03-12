<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class KomposisiProdukHukumChart extends ChartWidget
{
    protected static ?string $heading = 'Komposisi Produk Hukum';

    protected static ?int $sort = 4;
    
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = DokumenHukum::query()
            ->select('ref_jenis_dokumen_id', DB::raw('count(*) as total'))
            ->whereNotNull('ref_jenis_dokumen_id')
            ->groupBy('ref_jenis_dokumen_id')
            ->with('jenisDokumen:id,nama')
            ->get()
            ->map(fn($item) => [
                // Format label langsung dari PHP seperti PeraturanChart
                'nama'  => ($item->jenisDokumen?->nama ?? 'Tidak Diketahui')
                           . ' (' . $item->total . ')',
                'total' => (int) $item->total,
            ]);

        return [
            'labels'   => $data->pluck('nama')->toArray(),
            'datasets' => [
                [
                    'data'                => $data->pluck('total')->toArray(),
                    'backgroundColor'     => [
                        '#FF6384', '#36A2EB', '#FFCE56',
                        '#4BC0C0', '#9966FF', '#FF9F40',
                        '#C9CBCF', '#7BC8A4', '#E8A838',
                        '#EA526F',
                    ],
                    'hoverBackgroundColor' => [
                        '#e0506f', '#2a91d8', '#e6b840',
                        '#3aabab', '#8855ee', '#e88c2a',
                        '#b0b3b8', '#63b58e', '#d09520',
                        '#d43d5a',
                    ],
                    'hoverOffset' => 10,
                    'borderWidth' => 0,
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
            'maintainAspectRatio' => true,
            'aspectRatio'         => 1.6,
            'layout' => [
                'padding' => ['left' => 0],
            ],
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
                    'enabled'   => true,
                    'mode'      => 'nearest',
                    'intersect' => true,
                ],
            ],
        ];
    }
}