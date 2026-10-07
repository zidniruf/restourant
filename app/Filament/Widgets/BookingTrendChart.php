<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BookingTrendChart extends ChartWidget
{
    protected ?string $heading = 'Tren Booking (14 Hari Terakhir)';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $counts  = $days->map(fn ($date) => Booking::whereDate('created_at', $date)->count());
        $revenue = $days->map(fn ($date) => Booking::whereDate('created_at', $date)->sum('total_price') / 1000); // dalam ribuan biar skala mirip

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Booking',
                    'data' => $counts->values()->all(),
                    'borderColor' => '#2F6FE4',
                    'backgroundColor' => 'rgba(47,111,228,0.12)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 0,
                    'pointHoverRadius' => 4,
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Revenue (ribuan Rp)',
                    'data' => $revenue->values()->all(),
                    'borderColor' => '#1FA35C',
                    'backgroundColor' => 'rgba(31,163,92,0.08)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 0,
                    'pointHoverRadius' => 4,
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $days->map(fn ($d) => $d->translatedFormat('d M'))->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'boxWidth' => 8,
                        'padding' => 14,
                        'font' => ['size' => 11],
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => ['display' => false],
                    'ticks' => ['font' => ['size' => 10]],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'grid' => ['color' => '#F3F4F6'],
                    'ticks' => ['font' => ['size' => 10]],
                ],
            ],
        ];
    }

    protected function getMaxHeight(): ?string
    {
        return '200px';
    }
}