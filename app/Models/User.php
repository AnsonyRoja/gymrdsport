<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'roles_id',
        'rol',
        'estado',
        'cedula',
        'telefono',
        'peso',
        'altura',
        'historial_medico',
        'objetivos',
        'dias_gracia',
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
            'peso' => 'decimal:2',
            'altura' => 'decimal:2',
            'dias_gracia' => 'integer',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'roles_id');
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
     * Membresía activa o más reciente del usuario.
     */
    public function membresiaActiva()
    {
        return $this->membresiasUsuarios()
            ->with('membresia')
            ->orderByDesc('fecha_fin')
            ->first();
    }

    /**
     * Órdenes de pago asociadas al cliente.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'usuario_id')->orderByDesc('created_at');
    }

    /**
     * Rutinas asignadas al cliente.
     */
    public function rutinas(): HasMany
    {
        return $this->hasMany(Rutina::class, 'cliente_id');
    }

    /**
     * Rutinas creadas por el entrenador.
     */
    public function rutinasComoEntrenador(): HasMany
    {
        return $this->hasMany(Rutina::class, 'entrenador_id');
    }

    /**
     * Notificaciones dirigidas al usuario.
     */
    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'user_id')->orderByDesc('created_at');
    }

    /**
     * Registros de asistencia y acceso.
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Attendance::class, 'user_id')->orderByDesc('created_at');
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
            ->where('users.rol', 'cliente');
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

    /**
     * Alias de relación para atletas asignados al entrenador.
     */
    public function atletas(): BelongsToMany
    {
        return $this->clientes();
    }

    /**
     * Alias de relación para el entrenador del atleta.
     */
    public function entrenador(): BelongsToMany
    {
        return $this->entrenadores();
    }

    /**
     * Helper para verificar si es administrador.
     */
    public function isAdmin(): bool
    {
        return $this->rol === 'admin' || ($this->role && str_contains(strtolower($this->role->name), 'admin'));
    }

    /**
     * Helper para verificar si es entrenador.
     */
    public function isEntrenador(): bool
    {
        return $this->rol === 'entrenador' || ($this->role && str_contains(strtolower($this->role->name), 'entrenador'));
    }

    /**
     * Helper para verificar si es cliente.
     */
    public function isCliente(): bool
    {
        return !$this->isAdmin() && !$this->isEntrenador();
    }

    /**
     * Helper para verificar si el usuario se encuentra moroso (RFC-01).
     */
    public function isMoroso(): bool
    {
        // Administradores y entrenadores nunca son considerados morosos
        if ($this->isAdmin() || $this->isEntrenador()) {
            return false;
        }

        if ($this->estado === 'moroso') {
            return true;
        }

        $membresia = $this->membresiaActiva();
        if (!$membresia) {
            return false;
        }

        if ($membresia->estado_pago === 'moroso') {
            return true;
        }

        // Si expiró fecha_fin + días de gracia
        $fechaVencimientoGracia = \Carbon\Carbon::parse($membresia->fecha_fin)->addDays($this->dias_gracia ?? 3);
        if (now()->startOfDay()->greaterThan($fechaVencimientoGracia)) {
            return true;
        }

        return false;
    }
}
