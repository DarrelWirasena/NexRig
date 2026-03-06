<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',            // Home / Office / Other
        'recipient_name',
        'phone',
        'province',         // Nama provinsi
        'city',             // Nama kota/kabupaten
        'district',         // Nama kecamatan
        'village',          // Nama kelurahan/desa
        'postal_code',      // Auto-fill dari API wilayah
        'full_address',     // Alamat detail (jalan, no, RT/RW)
        'latitude',         // Dari Nominatim geocoding
        'longitude',        // Dari Nominatim geocoding
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude'   => 'float',
        'longitude'  => 'float',
    ];

    // ── Relasi ───────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Helper: koordinat tersedia? ──────────────────────────────
    public function hasCoordinates(): bool
    {
        return ! is_null($this->latitude) && ! is_null($this->longitude);
    }

    // ── Helper: format wilayah lengkap 1 baris ───────────────────
    public function getFullRegionAttribute(): string
    {
        return collect([
            $this->village,
            $this->district,
            $this->city,
            $this->province,
        ])->filter()->implode(', ');
    }

    // ── Scope: hanya alamat default ──────────────────────────────
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // ── Scope: hanya alamat dengan koordinat (untuk live tracking) ─
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }
}