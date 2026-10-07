<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [

        'title',
        'description',
        'image',
        'start_date',
        'end_date',
        'is_active',

    ];

    protected $casts = [

        'is_active' => 'boolean',

        'start_date' => 'date',

        'end_date' => 'date',

    ];
}