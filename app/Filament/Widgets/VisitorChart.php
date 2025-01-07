<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Flowframe\Trend\Trend;
use Filament\Support\RawJs;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class VisitorChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = true;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Pengunjung Website';

    protected function getData(): array
    {
        $data = Trend::model(Visitor::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => $data->map(fn(TrendValue $value) => (int) $value->aggregate), // Pastikan bilangan bulat
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('F')), // Ubah string menjadi Carbon
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
    {
        scales: {
            y: {
                ticks: {
                    callback: (value) => {
                        // Periksa apakah nilai adalah bilangan bulat
                        if (Number.isInteger(value)) {
                            return value + ' x'; // Tambahkan simbol 'x' setelah nilai
                        }
                        return ''; // Abaikan nilai desimal
                    },
                },
            },
        },
    }
JS);
    }

    protected function getType(): string
    {
        return 'line';
    }
}
