<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    // Supaya bisa diisi massal (create/update)
    protected $guarded = ['id'];

    // Relasi Kebalikan: Sebuah gambar dimiliki oleh satu Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}