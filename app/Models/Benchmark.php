<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Benchmark extends Model
{
    protected $fillable = ['product_id', 'game_id', 'resolution', 'avg_fps'];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('chatbot_product_context');
        });
        static::deleted(function () {
            Cache::forget('chatbot_product_context');
        });
    }

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