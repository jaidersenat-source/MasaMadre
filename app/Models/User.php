<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'panaderia_id', 'activo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'activo'   => 'boolean',
        ];
    }

    // Relación: usuario → panadería
    public function panaderia()
    {
        return $this->belongsTo(Panaderia::class);
    }

    // Helpers de rol
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPanaderia(): bool
    {
        return $this->role === 'panaderia';
    }
}