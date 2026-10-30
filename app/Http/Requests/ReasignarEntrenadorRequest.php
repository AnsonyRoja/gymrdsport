<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReasignarEntrenadorRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la reasignación de entrenador.
     */
    public function rules(): array
    {
        return [
            'cliente_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('rol', 'cliente');
                }),
            ],
            'entrenador_origen_id' => [
                'required',
                'integer',
                'different:entrenador_destino_id',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('rol', 'entrenador');
                }),
            ],
            'entrenador_destino_id' => [
                'required',
                'integer',
                'different:entrenador_origen_id',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('rol', 'entrenador');
                }),
            ],
        ];
    }

    /**
     * Nombres legibles para los atributos.
     */
    public function attributes(): array
    {
        return [
            'cliente_id' => 'cliente',
            'entrenador_origen_id' => 'entrenador de origen',
            'entrenador_destino_id' => 'entrenador de destino',
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'cliente_id.required' => 'El :attribute es obligatorio.',
            'cliente_id.exists' => 'El usuario indicado como cliente no existe o no tiene el rol de cliente.',
            'entrenador_origen_id.required' => 'El :attribute es obligatorio.',
            'entrenador_origen_id.exists' => 'El :attribute seleccionado no existe o no tiene el rol de entrenador.',
            'entrenador_destino_id.required' => 'El :attribute es obligatorio.',
            'entrenador_destino_id.exists' => 'El :attribute seleccionado no existe o no tiene el rol de entrenador.',
            'entrenador_destino_id.different' => 'El :attribute debe ser diferente al :attribute.',
        ];
    }
}
