<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $guarded = [];

    protected $fillable = [
        // ==== KOLOM LAMA (tidak diubah) ====
        'name',
        'phone',
        'booking_date',
        'booking_time',
        'guest_count',
        'note',
        'payment_link',
        'dp_amount',
        'status',
        'reject_reason',
        'qr_token',
        'total_price',
        'restaurant_table_id',
        'restaurant_session_id',
        'payment_status',
        'checkin_status',
        'midtrans_order_id',
        'paid_at',
        'qr_code',
        'event_time',
        'food_ready_time',
        'qr_code_path',

        // ==== KOLOM BARU (dari migration langkah 1) ====
        'snap_token',
        'checked_in_at',
        'rejection_reason',
    ];

    protected $casts = [
        'booking_date'  => 'date',
        'paid_at'       => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    // =====================================================
    // Auto-generate qr_token saat booking baru dibuat
    // =====================================================
protected static function boot()
{
    parent::boot();

    static::creating(function ($booking) {
        if (empty($booking->unique_token)) {
            $booking->unique_token = \Illuminate\Support\Str::random(32);
        }
    });
}

    // =====================================================
    // RELASI
    // =====================================================

    public function session()
    {
        return $this->belongsTo(RestaurantSession::class, 'restaurant_session_id');
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    // =====================================================
    // HELPERS
    // =====================================================

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isCheckedIn(): bool
    {
        return $this->checkin_status === 'checked_in';
    }

    // Label status untuk tampilan
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'approved'  => '✅ Disetujui',
            'rejected'  => '❌ Ditolak',
            'completed' => '🏁 Selesai',
            default     => '⏳ Menunggu',
        };
    }

    // Label pembayaran untuk tampilan
    public function getPaymentLabelAttribute(): string
    {
        return match($this->payment_status) {
            'paid'    => '✅ Lunas',
            'failed'  => '❌ Gagal',
            default   => '⏳ Belum Bayar',
        };
    }
}