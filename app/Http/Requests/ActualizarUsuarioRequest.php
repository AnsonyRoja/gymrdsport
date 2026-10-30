<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ActualizarUsuarioRequest extends FormRequest
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
        $userId = $this->route('user')?->id ?? $this->route('usuario')?->id ?? $this->route('usuario') ?? $this->route('user') ?? $this->route('id') ?? $this->input('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => [
                'nullable',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'rol' => ['sometimes', 'required', 'string', Rule::in(['admin', 'entrenador', 'cliente'])],
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
            'name.required' => 'El campo :attribute no puede quedar vacío.',
            'nombre.required' => 'El campo :attribute no puede quedar vacío.',
            'email.required' => 'El :attribute no puede quedar vacío.',
            'email.email' => 'Debe ingresar un :attribute válido.',
            'email.unique' => 'Este :attribute ya pertenece a otro usuario registrado.',
            'rol.required' => 'La selección de :attribute es obligatoria.',
            'rol.in' => 'El :attribute seleccionado no es válido (debe ser admin, entrenador o cliente).',
        ];
    }
}
