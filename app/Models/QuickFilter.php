<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class QuickFilter extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'is_active',
        'order'
    ];

    protected static function booted()
    {
        // Berjalan setiap kali data baru dibuat atau data lama di-update
        static::saved(function () {
            Cache::forget('quick_filters_cache');
        });

        // Berjalan setiap kali data dihapus
        static::deleted(function () {
            Cache::forget('quick_filters_cache');
        });
    }
}