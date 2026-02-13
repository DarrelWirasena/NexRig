<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// PENTING:
// Kita tidak perlu tulis 'use App\Models\ProductImage' 
// karena mereka satu folder (namespace). PHP otomatis tau.

class Product extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    // 1. Relasi ke Series (Orang Tua Langsung)
    public function series()
    {
        return $this->belongsTo(ProductSeries::class, 'product_series_id');
    }

    // 2. Accessor: Jalan Pintas ke Kategori (Kakek)
    // Supaya di frontend tetap bisa panggil: $product->category->name
    public function getCategoryAttribute()
    {
        return $this->series ? $this->series->category : null;
    }

    // 3. Relasi Gambar
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // 4. Relasi Komponen (Many-to-Many)
    public function components() {
        return $this->belongsToMany(Component::class, 'product_components')
                    ->withPivot('quantity');
    }

    // 5. Relasi Benchmark Game (Many-to-Many)
    public function benchmarks()
{
    return $this->hasMany(Benchmark::class);
}
    // 6. Relasi Kegunaan yang Dituju (Many-to-Many)
    public function intendedUses()
    {
        return $this->belongsToMany(IntendedUse::class, 'intended_use_product');
    }

    // 7. Relasi Spesifikasi Tambahan
   public function attributes()
{
    // Ini menghubungkan Produk ke banyak ProductAttribute
    return $this->hasMany(ProductAttribute::class);
}
}