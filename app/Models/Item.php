<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [

        'title',
        'slug',
        'description',
        'image',
        'price',
        'category_id',
        'properties',
        'is_recommended',
        'is_active',

    ];

    protected $casts = [

        'properties' => 'array',

        'is_recommended' => 'boolean',

        'is_active' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookingItems()
{
    return $this->hasMany(BookingItem::class);
}

    /*
    |--------------------------------------------------------------------------
    | AUTO SLUG
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {

            // BUAT SLUG DASAR
            $slug = Str::slug($item->title);

            // SLUG AWAL
            $originalSlug = $slug;

            // CEK DUPLIKAT
            $count = 1;

            while (
                static::where('slug', $slug)
                    ->where('id', '!=', $item->id)
                    ->exists()
            ) {

                $slug = $originalSlug . '-' . $count;

                $count++;
            }

            $item->slug = $slug;

        });
    }
}