<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembresiaUsuario extends Pivot
{
    /**
     * Clave primaria auto-incremental.
     */
    public $incrementing = true;

    protected $table = 'membresias_usuarios';

    protected $fillable = [
        'usuario_id',
        'membresia_id',
        'fecha_inicio',
        'fecha_fin',
        'estado_pago',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    /**
     * Usuario titular de la membresía.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Plan o tipo de membresía correspondiente.
     */
    public function membresia(): BelongsTo
    {
        return $this->belongsTo(Membresia::class, 'membresia_id');
    }
}
