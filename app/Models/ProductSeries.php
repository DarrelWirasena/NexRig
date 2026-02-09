<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSeries extends Model
{
    protected $guarded = ['id'];

    // Ke Atas (Category)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Ke Bawah (Products/Variants)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}