<?php
// app/Models/GalleryType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryType extends Model
{
    protected $fillable = ['name', 'color'];
}