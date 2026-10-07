<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableType extends Model
{
    protected $fillable = ['name', 'color'];

    public function restaurantTables()
    {
        return $this->hasMany(RestaurantTable::class, 'type', 'name');
    }
}