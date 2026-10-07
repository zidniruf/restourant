<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;

class BookingStatusChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Status Booking';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $statuses = [
            'pending'   => 'Pending',
            'approved'  => 'Disetujui',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'rejected'  => 'Ditolak',
            'cancelled' => 'Dibatalkan',
        ];

        $counts = collect($statuses)->map(fn ($label, $key) => Booking::where('status', $key)->count());

        return [
            'datasets' => [
                [
                    'data' => $counts->values()->all(),
                    'backgroundColor' => [
                        '#D98E04', // pending - amber
                        '#2F6FE4', // approved - blue
                        '#7C5CFC', // confirmed - purple
                        '#1FA35C', // completed - emerald
                        '#E23B3B', // rejected - red
                        '#9CA3AF', // cancelled - gray
                    ],
                    'borderWidth' => 3,
                    'borderColor' => '#ffffff',
                    'hoverOffset' => 6,
                ],
            ],
            'labels' => array_values($statuses),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'cutout' => '70%',
            'maintainAspectRatio' => false,
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
        ];
    }

    protected function getMaxHeight(): ?string
    {
        return '190px';
    }
}