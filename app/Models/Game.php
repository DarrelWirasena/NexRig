<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'name',
        'image_url'
    ];
    // Relasi Many-to-Many ke Produk lewat tabel benchmarks
    public function products()
    {
        return $this->belongsToMany(Product::class, 'benchmarks')
            ->withPivot(['resolution', 'avg_fps']); // Ambil data FPS
    }
}
