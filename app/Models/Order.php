<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'usuario_id',
        'membresia_id',
        'monto_usd',
        'tasa_bcv',
        'monto_bs',
        'monto_pagado_usd',
        'monto_pagado_bs',
        'descuento_usd',
        'tipo_pago',
        'metodo_pago',
        'referencia_pago',
        'tipo_comprobante',
        'numero_comprobante',
        'numero_factura',
        'numero_control',
        'estado',
        'fecha_pago',
        'notas',
    ];

    /**
     * Retorna el número de documento correspondiente (Factura o Comprobante).
     */
    public function getDocumentoCodigoAttribute(): string
    {
        if ($this->tipo_comprobante === 'factura') {
            return $this->numero_factura ?? ('FAC-' . str_pad((string)$this->id, 5, '0', STR_PAD_LEFT));
        }

        return $this->numero_comprobante ?? ('CMP-' . str_pad((string)$this->id, 5, '0', STR_PAD_LEFT));
    }

    protected function casts(): array
    {
        return [
            'monto_usd' => 'decimal:2',
            'tasa_bcv' => 'decimal:2',
            'monto_bs' => 'decimal:2',
            'monto_pagado_usd' => 'decimal:2',
            'monto_pagado_bs' => 'decimal:2',
            'descuento_usd' => 'decimal:2',
            'fecha_pago' => 'date',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function membresia(): BelongsTo
    {
        return $this->belongsTo(Membresia::class, 'membresia_id');
    }
}
