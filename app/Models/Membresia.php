<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Membresia extends Model
{
    use HasFactory;

    protected $table = 'membresias';

    protected $fillable = [
        'nombre',
        'costo_base',
        'duracion_dias',
    ];

    protected function casts(): array
    {
        return [
            'costo_base' => 'decimal:2',
            'duracion_dias' => 'integer',
        ];
    }

    /**
     * Registros de suscripciones asociadas a este plan de membresía.
     */
    public function membresiasUsuarios(): HasMany
    {
        return $this->hasMany(MembresiaUsuario::class, 'membresia_id');
    }

    /**
     * Usuarios que han adquirido este tipo de membresía.
     */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'membresias_usuarios', 'membresia_id', 'usuario_id')
            ->using(MembresiaUsuario::class)
            ->withPivot(['id', 'fecha_inicio', 'fecha_fin', 'estado_pago'])
            ->withTimestamps();
    }
}
