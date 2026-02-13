<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// --- 1. IMPORT TAMBAHAN UNTUK FILAMENT ---
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

// --- 2. TAMBAHKAN 'implements FilamentUser' DI SINI ---
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // --- KODE LAMA ANDA (TIDAK DIUBAH) ---
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- 3. FUNGSI BARU UNTUK FILAMENT (Cek Role Admin) ---
    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya user dengan kolom role berisi 'admin' yang bisa masuk
        return $this->role === 'admin';
    }
}