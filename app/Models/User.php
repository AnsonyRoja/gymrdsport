<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'estado',
    ];

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

    /**
     * Historial de membresías contratadas por el usuario.
     */
    public function membresiasUsuarios(): HasMany
    {
        return $this->hasMany(MembresiaUsuario::class, 'usuario_id');
    }

    /**
     * Membresías adquiridas por el usuario.
     */
    public function membresias(): BelongsToMany
    {
        return $this->belongsToMany(Membresia::class, 'membresias_usuarios', 'usuario_id', 'membresia_id')
            ->using(MembresiaUsuario::class)
            ->withPivot(['id', 'fecha_inicio', 'fecha_fin', 'estado_pago'])
            ->withTimestamps();
    }

    /**
     * Si el usuario es entrenador: clientes asignados a su cargo.
     */
    public function clientes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'entrenador_cliente', 'entrenador_id', 'cliente_id')
            ->withPivot(['fecha_asignacion', 'fecha_desvinculacion', 'estado'])
            ->withTimestamps();
    }

    /**
     * Clientes actualmente activos del entrenador (únicamente usuarios con rol activo de cliente).
     */
    public function clientesActivos(): BelongsToMany
    {
        return $this->clientes()
            ->wherePivot('estado', 'activo')
            ->where('users.rol', 'cliente')
            ->where('users.estado', 'activo');
    }

    /**
     * Si el usuario es cliente: entrenadores que le han sido asignados.
     */
    public function entrenadores(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'entrenador_cliente', 'cliente_id', 'entrenador_id')
            ->withPivot(['fecha_asignacion', 'fecha_desvinculacion', 'estado'])
            ->withTimestamps();
    }

    /**
     * Entrenador(es) actualmente activo(s) del cliente.
     */
    public function entrenadorActivo(): BelongsToMany
    {
        return $this->entrenadores()->wherePivot('estado', 'activo');
    }
}
