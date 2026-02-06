<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model {
    protected $guarded = ['id'];
    // Relasi Many-to-Many: Komponen bisa dipakai di banyak Produk
    public function products() {
        return $this->belongsToMany(Product::class, 'product_components')
                    ->withPivot('quantity'); // Ambil kolom jumlah di tabel tengah
    }
}
