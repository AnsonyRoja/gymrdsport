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
        'es_vip',
        'tipo',
        'descripcion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'costo_base' => 'decimal:2',
            'duracion_dias' => 'integer',
            'es_vip' => 'boolean',
            'activo' => 'boolean',
        ];
    }

    /**
     * Determina si la membresía es VIP o cuenta con beneficios exclusivos.
     */
    public function isVip(): bool
    {
        return $this->es_vip || strtolower($this->tipo) === 'vip';
    }

    /**
     * Retorna una representación legible de la duración (ej: "1 Mes (30 días)").
     */
    public function getDuracionTextoAttribute(): string
    {
        if ($this->duracion_dias === 30) {
            return '1 Mes (30 días)';
        } elseif ($this->duracion_dias === 15) {
            return '15 Días (Quincenal)';
        } elseif ($this->duracion_dias === 60) {
            return '2 Meses (60 días)';
        } elseif ($this->duracion_dias === 90) {
            return '3 Meses (Trimestral)';
        } elseif ($this->duracion_dias === 180) {
            return '6 Meses (Semestral)';
        } elseif ($this->duracion_dias === 365) {
            return '1 Año (Anual)';
        }

        return "{$this->duracion_dias} días";
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
