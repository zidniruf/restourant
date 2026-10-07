<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
protected $fillable = [
    'name',
    'message',
    'rating',
    'image',
    'is_approved',
];

    protected $casts = [
        'is_approved' => 'boolean',
    ];
}