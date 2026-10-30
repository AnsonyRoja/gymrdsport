<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AlmacenarUsuarioRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'rol' => ['required', 'string', Rule::in(['admin', 'entrenador', 'cliente'])],
            'estado' => ['sometimes', 'string', 'max:50'],
        ];
    }

    /**
     * Nombres de atributos personalizados para los mensajes de error.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre completo',
            'nombre' => 'nombre completo',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'rol' => 'rol de usuario',
            'estado' => 'estado',
        ];
    }

    /**
     * Mensajes personalizados para las reglas de validación.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El campo :attribute es obligatorio.',
            'nombre.required' => 'El campo :attribute es obligatorio.',
            'email.required' => 'El :attribute es obligatorio.',
            'email.email' => 'Debe ingresar un :attribute válido.',
            'email.unique' => 'Este :attribute ya se encuentra registrado en el sistema.',
            'password.required' => 'La :attribute es obligatoria.',
            'rol.required' => 'La selección de :attribute es obligatoria.',
            'rol.in' => 'El :attribute seleccionado no es válido (debe ser admin, entrenador o cliente).',
        ];
    }
}
