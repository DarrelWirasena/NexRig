<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImage;

class Product extends Model
{
    use HasFactory;
    
    // Karena nama tabel kita 'products', Laravel sudah tau otomatis.
    // Tapi kita perlu definisikan kolom yang boleh diisi (Mass Assignment)
    protected $guarded = ['id']; // Semua boleh diisi kecuali ID

    // Relasi: Produk milik satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Produk punya banyak gambar
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Relasi ke Components (Many-to-Many)
    public function components() {
        return $this->belongsToMany(Component::class, 'product_components')
                    ->withPivot('quantity');
    }

    // Relasi ke Games (Many-to-Many lewat Benchmarks)
    public function benchmarks() {
        // Ini trik: Kita anggap benchmark sebagai relasi ke Game
        return $this->belongsToMany(Game::class, 'benchmarks')
                    ->withPivot(['resolution', 'avg_fps']);
    }

    // ... kode sebelumnya ...

    // Relasi ke Intended Uses (Kegunaan)
    public function intendedUses()
    {
        return $this->hasMany(IntendedUse::class);
    }

    // Relasi ke Product Attributes (Spesifikasi Lain)
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
    
    // ... penutup class ...

}