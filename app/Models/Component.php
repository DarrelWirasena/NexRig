<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Component extends Model {
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('chatbot_product_context');
        });
        static::deleted(function () {
            Cache::forget('chatbot_product_context');
        });
    }

    // Relasi Many-to-Many: Komponen bisa dipakai di banyak Produk
    public function products() {
        return $this->belongsToMany(Product::class, 'product_components')
                    ->withPivot('quantity'); // Ambil kolom jumlah di tabel tengah
    }
}
