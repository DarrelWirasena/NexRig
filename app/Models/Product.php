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
    public function benchmarks() {
        return $this->belongsToMany(Game::class, 'benchmarks')
                    ->withPivot(['resolution', 'avg_fps']);
    }

    // 6. Relasi Kegunaan (Icon Fitur)
    public function intendedUses()
    {
        return $this->hasMany(IntendedUse::class);
    }

    // 7. Relasi Spesifikasi Tambahan
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}