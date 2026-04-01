<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_address_id',
        'order_date',
        'total_price',
        'status',
        'midtrans_order_id',
        'snap_token',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'shipping_latitude',
        'shipping_longitude',
        'payment_type',
    ];

    protected $casts = [
        'order_date'         => 'datetime',
        'total_price'        => 'float',
        'shipping_latitude'  => 'float',
        'shipping_longitude' => 'float',
    ];

    // ── Relasi ───────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class, 'user_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Helper: apakah koordinat tujuan tersedia? ────────────────
    public function hasShippingCoordinates(): bool
    {
        return ! is_null($this->shipping_latitude)
            && ! is_null($this->shipping_longitude);
    }
}