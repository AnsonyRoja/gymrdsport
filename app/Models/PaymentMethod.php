<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $fillable = [
        'nombre',
        'codigo',
        'tipo',
        'moneda',
        'instrucciones',
        'requiere_referencia',
        'activo',
        'orden',
    ];

    protected $casts = [
        'requiere_referencia' => 'boolean',
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Scope para obtener sólo métodos activos.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para ordenar por prioridad y nombre.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }

    /**
     * Verifica si es método en Cripto (USDT, Binance).
     */
    public function isCripto(): bool
    {
        return strtolower($this->tipo) === 'cripto' || str_contains(strtoupper($this->codigo), 'USDT');
    }

    /**
     * Retorna el color distintivo para badge según la moneda/tipo.
     */
    public function getBadgeClassAttribute(): string
    {
        return match (strtoupper($this->moneda)) {
            'USDT' => 'bg-teal-50 text-teal-700 border-teal-200',
            'USD' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'VES' => 'bg-blue-50 text-blue-700 border-blue-200',
            default => 'bg-purple-50 text-purple-700 border-purple-200',
        };
    }
}
