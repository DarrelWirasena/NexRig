<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        // Jika sudah URL lengkap, return as-is
        if (strpos($this->image_url, 'http') === 0) {
            return $this->image_url;
        }
        
        $cloudName = config('cloudinary.cloud_name');
        $publicId = $this->image_url;
        
        // Hapus "products/" jika ada
        if (strpos($publicId, 'products/') === 0) {
            $publicId = substr($publicId, 9); // Remove "products/"
        }
        
        return "https://res.cloudinary.com/{$cloudName}/image/upload/{$publicId}";
    }
}