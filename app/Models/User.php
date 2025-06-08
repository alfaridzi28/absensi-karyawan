<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    // Role helper functions
    public function isAdmin(): bool
    {
        return $this->role === 1;
    }

    public function isEmployee(): bool
    {
        return $this->role === 2;
    }

    public function hasAbsenMasukToday(): bool
    {
        return $this->absensis()
            ->whereDate('created_at', now()->toDateString())
            ->whereNotNull('waktu_masuk')
            ->exists();
    }

    public function hasAbsenIzinToday(): bool
    {
        return $this->absensis()
            ->whereDate('created_at', now()->toDateString())
            ->where('type', 'izin')
            ->exists();
    }

    public function hasAbsenPulangToday(): bool
    {
        return $this->absensis()
            ->whereDate('created_at', now()->toDateString())
            ->whereNotNull('waktu_pulang')
            ->exists();
    }
}
