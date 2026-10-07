<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Str;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createTransaction(\App\Models\Booking $booking)
    {
        // =====================================================
        // Pastikan unique_token sudah ada sebelum buat transaksi
        // Dibutuhkan untuk URL struk setelah bayar
        // =====================================================
        if (empty($booking->unique_token)) {
            $booking->update(['unique_token' => Str::random(32)]);
            $booking->refresh(); // ambil data terbaru dari DB
        }

        // DP 50% dari total harga
        $dpAmount = (int) ($booking->total_price * 0.5);

        // Order ID unik — format: BOOK-{id}-{timestamp}
        $orderId = 'BOOK-' . $booking->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $dpAmount,
            ],
            'customer_details' => [
                'first_name' => $booking->name,
                'phone'      => $booking->phone,
            ],
            'item_details' => [
                [
                    'id'       => 'DP-' . $booking->id,
                    'price'    => $dpAmount,
                    'quantity' => 1,
                    'name'     => 'DP 50% Reservasi Tempat ' . ($booking->table->name ?? $booking->restaurant_table_id),
                ]
            ],
            // ✅ Setelah bayar → redirect ke halaman struk pelanggan
            'callbacks' => [
                'finish' => url('/booking/struk/' . $booking->unique_token),
            ],
        ];

        // Simpan order_id ke database supaya webhook bisa mencocokkan
        $booking->update(['midtrans_order_id' => $orderId]);

        return Snap::createTransaction($params);
    }
}