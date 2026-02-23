<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'category', 'image_url', 
        'excerpt', 'content', 'author', 'reading_time', 
        'tags', 'status', 'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSrcAttribute(): string
    {
        return $this->image_url ?? 'https://placehold.co/800x400';
    }
}