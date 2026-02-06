<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $guarded = ['id'];
    // Relasi: Satu Kategori punya banyak Produk
    public function products() {
        return $this->hasMany(Product::class);
    }
}
