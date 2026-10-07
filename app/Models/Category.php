<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        // Saat CREATE baru
        static::creating(function (Category $category) {
            $slug = Str::slug($category->name);
            $original = $slug;
            $count = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $original . '-' . $count++;
            }
            $category->slug = $slug;
        });

        // Saat UPDATE - hanya kalau nama berubah
        static::updating(function (Category $category) {
            if ($category->isDirty('name')) {
                $slug = Str::slug($category->name);
                $original = $slug;
                $count = 1;
                while (static::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                    $slug = $original . '-' . $count++;
                }
                $category->slug = $slug;
            }
        });
    }
}