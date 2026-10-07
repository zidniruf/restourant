<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'capacity',
        'is_active',
        'is_paid',
        'pricing_type',
        'price',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity'  => 'integer',
        'is_paid'   => 'boolean',
        'price'     => 'decimal:2',
    ];

    // Helper label tipe tempat
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'lantai' => 'Per Lantai',
            'area'   => 'Area',
            default  => 'Per Meja',
        };
    }

    /**
     * Hitung biaya sewa tempat.
     * - flat     : biaya tetap, tidak peduli durasi
     * - per_hour : biaya × jumlah jam (default 1 jam kalau tidak diisi)
     */
    public function calculatePrice(?int $hours = null): float
    {
        if (!$this->is_paid) {
            return 0;
        }

        if ($this->pricing_type === 'per_hour') {
            $hours = $hours && $hours > 0 ? $hours : 1;
            return (float) $this->price * $hours;
        }

        // flat
        return (float) $this->price;
    }
}