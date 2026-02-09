<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    // Relasi Langsung (Anak)
    public function series()
    {
        return $this->hasMany(ProductSeries::class);
    }

    // Relasi Jauh (Cucu): Mengambil semua produk di bawah kategori ini
    // Berguna buat halaman "Lihat Semua PC Gaming"
    public function products()
    {
        return $this->hasManyThrough(Product::class, ProductSeries::class);
    }
}
