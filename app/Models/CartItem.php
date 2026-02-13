<?php

namespace App\Models;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity'];

    // Relasi ke Produk (Untuk ambil nama, harga, gambar)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    // Relasi ke User (Opsional, tapi bagus ada)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}