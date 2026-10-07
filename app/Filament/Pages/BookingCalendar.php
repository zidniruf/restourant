<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use BackedEnum;
use Filament\Pages\Page;

class BookingCalendar extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Kalender Booking';
    protected static string|\UnitEnum|null $navigationGroup = 'Booking Management';
    protected string $view = 'filament.pages.booking-calendar';

    public array $events = [];

public function mount(): void
{
    $bookings = Booking::with(['table', 'session', 'items.item'])->get();

    $this->events = $bookings->map(function ($booking) {
        return [
            'id'    => $booking->id,
            'start' => $booking->booking_date->format('Y-m-d'),
            'extendedProps' => [
                'name'           => $booking->name,
                'phone'          => $booking->phone,
                'guest_count'    => $booking->guest_count,
                'table'          => optional($booking->table)->name ?? '-',
                'session'        => optional($booking->session)->name ?? '-',
                'session_time'   => $booking->session
                    ? substr($booking->session->start_time, 0, 5) . '–' . substr($booking->session->end_time, 0, 5)
                    : '-',
                'event_time'     => $booking->event_time     // ✅ tambah
                    ? substr($booking->event_time, 0, 5) . ' WIB'
                    : '-',
                'food_ready_time' => $booking->food_ready_time // ✅ tambah
                    ? substr($booking->food_ready_time, 0, 5) . ' WIB'
                    : '-',
                'status'         => $booking->status,
                'total_price'    => $booking->total_price,
                'checkin_status' => $booking->checkin_status,
                'checked_in_at'  => $booking->checked_in_at?->format('d M Y, H:i'),
                'note'           => $booking->note,
                'menus'          => $booking->items->map(fn($item) =>
                    ($item->item->title ?? '-') . ' x' . $item->qty
                )->implode(', '),
                'booking_date'   => $booking->booking_date->format('Y-m-d'),
            ],
        ];
    })->toArray();
}
}