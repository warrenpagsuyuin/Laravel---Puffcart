<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',   // ✅ THIS WAS MISSING
        'brand',
        'price',
        'description',
        'stock',
    ];
}