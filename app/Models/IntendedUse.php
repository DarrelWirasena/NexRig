<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntendedUse extends Model
{
    protected $fillable = ['title', 'description', 'icon_url'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'intended_use_product');
    }
}