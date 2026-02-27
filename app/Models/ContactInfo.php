<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $fillable = [
        'type',
        'label',
        'title',
        'value',
        'url',
        'display_value',
        'is_active',
        'order',
    ];

    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('order')->get();
    }
}