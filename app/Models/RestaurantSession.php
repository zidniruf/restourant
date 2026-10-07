<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSession extends Model
{
    use HasFactory;

    // Mendaftarkan kolom yang boleh diisi
    protected $fillable = ['name', 'start_time', 'end_time'];

    // Relasi: 1 Sesi bisa dimiliki banyak Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}