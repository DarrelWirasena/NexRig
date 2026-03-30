<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_series_id',
        'name',
        'slug',
        'tier',
        'price',
        'stock',
        'track_stock',
        'short_description',
        'description',
        'is_active',
        'restock_note',   
    ];

    protected $casts = [
        'track_stock' => 'boolean',
        'is_active'   => 'boolean',
    ];

    // ─────────────────────────────────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Hanya produk yang stoknya tersedia.
     * Produk dengan track_stock = false dianggap selalu tersedia.
     */
    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('track_stock', false)
              ->orWhere('stock', '>', 0);
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Cek apakah stok tersedia untuk qty tertentu.
     */
    public function hasStock(int $qty = 1): bool
    {
        if (!$this->track_stock) return true;
        return $this->stock >= $qty;
    }

    /**
     * Kurangi stok. Throw exception jika stok tidak cukup.
     */
    public function decrementStock(int $qty = 1): void
    {
        if (!$this->track_stock) return;

        if ($this->stock < $qty) {
            throw new \RuntimeException("Stok {$this->name} tidak mencukupi.");
        }

        $this->decrement('stock', $qty);
    }

    /**
     * Kembalikan stok (saat order dibatalkan).
     */
    public function incrementStock(int $qty = 1): void
    {
        if (!$this->track_stock) return;
        $this->increment('stock', $qty);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Relasi
    // ─────────────────────────────────────────────────────────────────────────

    public function series()
    {
        return $this->belongsTo(ProductSeries::class, 'product_series_id');
    }

    public function getCategoryAttribute()
    {
        return $this->series ? $this->series->category : null;
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function components()
    {
        return $this->belongsToMany(Component::class, 'product_components')
            ->withPivot('quantity');
    }

    public function benchmarks()
    {
        return $this->hasMany(Benchmark::class);
    }

    public function intendedUses()
    {
        return $this->belongsToMany(IntendedUse::class, 'intended_use_product');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Events
    // ─────────────────────────────────────────────────────────────────────────

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('navbar_categories');
            Cache::forget('chatbot_product_context');
        });

        static::deleted(function ($product) {
            $product->orderItems()->delete();
            Cache::forget('navbar_categories');
            Cache::forget('chatbot_product_context');
        });
    }
}