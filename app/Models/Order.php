<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    // Relasi: Pesanan milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Pesanan punya banyak item barang
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}