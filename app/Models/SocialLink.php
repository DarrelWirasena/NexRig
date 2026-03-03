<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = ['platform', 'url', 'is_active', 'order'];

    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('order')->get();
    }
}
