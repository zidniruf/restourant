<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    protected $fillable = [

        // WEBSITE
        'site_name',
        'logo',

        // HERO
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'hero_background',
        'hero_featured_image',
        'hero_button_text',
        'show_hero_food_image',
        

        // CONTACT
        'whatsapp',
        'instagram',
        'facebook',
        'tiktok',
        'maps_link',
        'google_maps_embed',

        // OPERATIONAL
        'business_hours',
        'holiday_days',
        'holiday_note',

        'is_reservation_enabled',

    ];

    protected $casts = [

        'business_hours' => 'array',

        'holiday_days' => 'array',

        'show_hero_food_image' => 'boolean',

        'is_reservation_enabled' => 'boolean',

    ];
}