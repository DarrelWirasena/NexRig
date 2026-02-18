<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_url',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    protected $appends = ['full_url'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFullUrlAttribute(): string
    {
        if (strpos($this->image_url, 'http') === 0) {
            return $this->image_url;
        }

        // Biarkan Adapter yang menghitung URL-nya (lebih aman)
        return Storage::disk('cloudinary')->url($this->image_url);
    }

    public function getSrcAttribute(): string
    {
        if (!$this->image_url) return 'https://placehold.co/200'; // Fallback jika null

        if (strpos($this->image_url, 'http') === 0) {
            return $this->image_url;
        }

        return Storage::disk('cloudinary')->url($this->image_url);
    }
}