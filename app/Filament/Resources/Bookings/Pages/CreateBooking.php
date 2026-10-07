<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
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