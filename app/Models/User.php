<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // 1. Usa esta propiedad estándar de Laravel en lugar de #[Fillable]
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles_id', // <--- ¡Ahora sí lo dejará pasar sin descartarlo!
    ];

    // Ocultar campos sensibles
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'roles_id');
    }
}
