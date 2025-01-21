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

    protected static ?string $heading = 'Traffic Overview';

    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter; // Filter yang dipilih

        // Tentukan rentang tanggal berdasarkan filter yang dipilih
        switch ($activeFilter) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear(); // Rentang waktu mulai dari awal tahun
                $endDate = now()->endOfMonth(); // Rentang waktu hingga bulan sekarang
                break;
            default:
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
        }

        $data = Trend::model(Visitor::class)
            ->between(
                start: $startDate, // Menggunakan rentang tanggal yang ditentukan oleh filter
                end: $endDate, // Menggunakan rentang tanggal yang ditentukan oleh filter
            );

            if ($activeFilter == 'year') {
                // Jika filter adalah 'year', gunakan perMonth()
                $data = $data->perMonth()->count();
            } else {
                // Defaultnya adalah perDay
                $data = $data->perDay()->count();
            }

        // Menyesuaikan label berdasarkan filter
        $labels = [];

        switch ($activeFilter) {
            case 'week':
                // Untuk filter "week", tampilkan 7 hari dalam minggu
                $labels = collect(range(0, 6))->map(function ($dayOffset) use ($startDate) {
                    return $startDate->copy()->addDays($dayOffset)->format('l'); // Format hari (Senin, Selasa, dll.)
                });
                break;

            case 'month':
                // Untuk filter "month", tampilkan setiap hari dalam bulan
                $labels = collect(range(1, $startDate->daysInMonth))->map(function ($day) use ($startDate) {
                    return $startDate->copy()->day($day)->format('d'); // Format hari (01, 02, dll.)
                });
                break;

            case 'year':
                // Untuk filter "year", tampilkan bulan dalam tahun
                $labels = collect(range(1, now()->month))->map(function ($month) {
                    return \Carbon\Carbon::create()->month($month)->format('F'); // Format nama bulan hanya sampai bulan sekarang
                });
                break;

            default:
                // Untuk filter "today" atau default lainnya, tampilkan hanya satu hari
                $labels = [$startDate->format('l')]; // Format hari (Senin, dll.)
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => $data->map(fn(TrendValue $value) => (int) $value->aggregate), // Pastikan bilangan bulat
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels, // Menampilkan label sesuai dengan filter
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
        return 'bar';
    }
}
