<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSeries extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Disable semua produk dalam series ini
    public function disableAllProducts(): void
    {
        $this->products()->update(['is_active' => false]);
    }
}