<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'nombre_servicio',
        'descripcion',
        'hora_inicio',
        'hora_fin',
        'cupos_disponibles',
        'estatus',
    ];

    protected function casts(): array
    {
        return [
            'cupos_disponibles' => 'integer',
        ];
    }
}
