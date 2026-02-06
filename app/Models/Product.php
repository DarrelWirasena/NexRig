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
}