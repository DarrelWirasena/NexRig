<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class IntendedUse extends Model
{
    protected $fillable = ['title', 'description', 'icon_url'];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('chatbot_product_context');
        });
        static::deleted(function () {
            Cache::forget('chatbot_product_context');
        });
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'intended_use_product');
    }
}