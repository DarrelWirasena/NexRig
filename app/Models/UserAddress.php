<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',          // Sesuai migrasi
        'city',
        'postal_code',
        'full_address',   // Sesuai migrasi
        'is_default',     // Sesuai migrasi
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}