<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ProductAttribute extends Model
{
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}