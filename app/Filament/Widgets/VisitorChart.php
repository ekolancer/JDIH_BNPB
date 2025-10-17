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

    protected static ?int $sort = 4;
    
    protected static ?string $maxHeight = '250px';

    // protected int | string | array $columnSpan = [
    //     'sm' => 2,  // Mengambil 2 kolom pada layar kecil
    //     'lg' => 2,  // Mengambil 4 kolom pada layar besar
    // ];

    protected static ?string $heading = 'Pengunjung Situs';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Per Hari',
            'week' => 'Per Minggu',
            'month' => 'Per Bulan',
            'year' => 'Per Tahun',
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
                if ($activeFilter === 'today') {
                    // Untuk filter "today", tampilkan jam 00:00 - 23:00
                    $labels = collect(range(0, 23))->map(function ($hour) use ($startDate) {
                        return $startDate->copy()->startOfDay()->addHours($hour)->format('H:00'); // Format jam (00:00, 01:00, ...)
                    });

                    // Ambil data per jam untuk hari ini
                    $data = Trend::model(Visitor::class)
                        ->between(start: $startDate, end: $endDate)
                        ->perHour()
                        ->count();
                } else {
                    // Untuk filter default lainnya, tampilkan hanya satu hari
                    $labels = [$startDate->format('l')]; // Format hari (Senin, dll.)
                }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Visitor',
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
                            return value;
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
