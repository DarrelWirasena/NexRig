<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    // Relasi: Item milik satu Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: Item adalah satu Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}