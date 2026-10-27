<?php

namespace App\Http\Controllers;

use App\Models\User; // Asegúrate de importar el modelo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Asegúrate de importar Hash

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'roles_id' => 'required|exists:roles,id', // <--- Actualizado a 'roles_id'
        ]);

        // Crear el usuario
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => $request->roles_id, // <--- Actualizado a 'roles_id'
        ]);

        return redirect()->back()->with('success', '¡Usuario creado con éxito!');
    }
}
