<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\Widget;

class RecentBookingsWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-bookings';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function getBookings()
    {
        return Booking::with('table')
            ->latest()
            ->take(4)
            ->get();
    }
}