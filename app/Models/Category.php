<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function series()
    {
        return $this->hasMany(ProductSeries::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, ProductSeries::class);
    }

    // Cek apakah kategori masih punya series/produk aktif
    public function hasActiveContent(): bool
    {
        return $this->series()->where('is_active', true)->exists();
    }

    // Disable semua series + produk dalam kategori ini
    // Disable semua series + produk dalam kategori ini
    public function disableAll(): void
    {
        // Ambil semua series id dulu
        $seriesIds = $this->series()->pluck('id');
        
        // Update series
         $this->series()->update(['is_active' => false]);
        
        // Update produk menggunakan seriesIds (hindari ambiguous column)
       Product::whereIn('product_series_id', $seriesIds)->update(['is_active' => false]);

        $this->update(['is_active' => false]);
    }
}