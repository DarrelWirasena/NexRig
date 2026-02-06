<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model {
    protected $guarded = ['id'];
    // Relasi Many-to-Many ke Produk lewat tabel benchmarks
    public function products() {
        return $this->belongsToMany(Product::class, 'benchmarks')
                    ->withPivot(['resolution', 'avg_fps']); // Ambil data FPS
    }
}
