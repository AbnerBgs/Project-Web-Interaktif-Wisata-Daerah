<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'avatar', 'phone', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['is_active' => 'boolean', 'email_verified_at' => 'datetime'];

    // Cek apakah user adalah admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
