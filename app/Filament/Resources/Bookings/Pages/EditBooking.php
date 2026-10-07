<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Resources\Pages\EditRecord;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function afterSave(): void
    {
        $this->recalculateTotal();
    }

    protected function recalculateTotal(): void
    {
        $booking = $this->record->fresh('items');

        $total = $booking->items->sum(function ($item) {
            return $item->price * $item->qty;
        });

        $booking->update(['total_price' => $total]);
    }
}