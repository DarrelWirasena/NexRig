<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Benchmark extends Model
{
    protected $fillable = ['product_id', 'game_id', 'resolution', 'avg_fps'];

    // Ini yang dicari oleh Filament
   public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}