<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Notification;

class BookingPaidNotification extends Notification
{
    public function __construct(public Booking $booking) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'booking_id'    => $this->booking->id,
            'customer_name' => $this->booking->name,
            'table'         => $this->booking->table->name ?? '-',
            'booking_date'  => $this->booking->booking_date->format('d M Y'),
            'amount'        => $this->booking->dp_amount,
            'message'       => '💳 ' . $this->booking->name . ' telah membayar DP!',
            'type'          => 'payment_received',
        ];
    }
}