<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class TrainerController extends Controller
{
    /**
     * Muestra el listado de entrenadores con conteo de atletas y listado de clientes disponibles.
     */
    public function index(): View
    {
        // Obtener entrenadores con conteo de atletas activos (filtrando estrictamente usuarios con rol 'cliente' y estado 'activo')
        $trainers = User::where('rol', 'entrenador')
            ->withCount(['clientesActivos as total_atletas'])
            ->with(['clientesActivos' => function ($query) {
                $query->select('users.id', 'users.name', 'users.email')
                    ->where('users.rol', 'cliente')
                    ->where('users.estado', 'activo')
                    ->withPivot('fecha_asignacion');
            }])
            ->latest()
            ->get();

        // Obtener todos los clientes para el selector de asignación rápida (excluyendo entrenadores o administradores)
        $clientes = User::where('rol', 'cliente')
            ->where('estado', 'activo')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.trainers.index', compact('trainers', 'clientes'));
    }

    /**
     * Registra un nuevo miembro del personal técnico con rol de 'entrenador'.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre del entrenador es requerido.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado en el sistema.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'El campo contraseña debe contener al menos 8 caracteres.',
        ], [
            'password' => 'contraseña',
            'name' => 'nombre',
            'email' => 'correo electrónico',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'rol' => 'entrenador',
            'estado' => 'activo',
        ]);

        return redirect()->route('admin.trainers.index')->with('success', 'Entrenador registrado exitosamente.');
    }

    /**
     * Asciende a un cliente existente a entrenador de forma independiente.
     * Elimina en cascada cualquier asignación previa como atleta sin tocar correo ni contraseña.
     */
    public function promoteClient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('rol', 'cliente'),
            ],
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente para ascender.',
            'cliente_id.exists' => 'El cliente seleccionado no existe o no tiene rol de cliente.',
        ]);

        $cliente = User::findOrFail((int) $validated['cliente_id']);

        DB::transaction(function () use ($cliente) {
            $hoy = Carbon::now()->toDateString();
            $ahora = Carbon::now();

            // 1. Desvinculación en cascada como atleta previo:
            // Desactiva cualquier relación activa donde figure como atleta/cliente
            DB::table('entrenador_cliente')
                ->where('cliente_id', $cliente->id)
                ->where('estado', 'activo')
                ->update([
                    'estado' => 'inactivo',
                    'fecha_desvinculacion' => $hoy,
                    'updated_at' => $ahora,
                ]);

            // 2. Ascender rol a 'entrenador' y asegurar estatus 'activo'
            // No se solicita ni sobreescribe correo ni contraseña
            $cliente->update([
                'rol' => 'entrenador',
                'estado' => 'activo',
            ]);
        });

        return redirect()->route('admin.trainers.index')
            ->with('success', "El cliente '{$cliente->name}' ({$cliente->email}) ha sido ascendido exitosamente a entrenador.");
    }

    /**
     * Actualiza el Nombre, Correo y Estatus del entrenador seleccionado.
     */
    public function update(Request $request, User $trainer): RedirectResponse
    {
        // Asegurar que el usuario sea entrenador
        if ($trainer->rol !== 'entrenador') {
            return redirect()->route('admin.trainers.index')->withErrors(['trainer' => 'El usuario seleccionado no es un entrenador.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($trainer->id)],
            'estado' => ['required', 'string', Rule::in(['activo', 'inactivo'])],
        ], [
            'name.required' => 'El nombre del entrenador es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado con otro usuario.',
            'estado.required' => 'Debe seleccionar un estatus.',
            'estado.in' => 'El estatus seleccionado no es válido.',
        ], [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'estado' => 'estatus',
        ]);

        $trainer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'estado' => $validated['estado'],
        ]);

        return redirect()->route('admin.trainers.index')->with('success', 'Datos del entrenador actualizados correctamente.');
    }

    /**
     * Elimina al entrenador liberando/desvinculando previamente sus relaciones con clientes.
     */
    public function destroy(User $trainer): RedirectResponse
    {
        if ($trainer->rol !== 'entrenador') {
            return redirect()->route('admin.trainers.index')->withErrors(['trainer' => 'El usuario no tiene rol de entrenador.']);
        }

        DB::transaction(function () use ($trainer) {
            $hoy = Carbon::now()->toDateString();
            $ahora = Carbon::now();

            // 1. Desvincular de forma limpia a todos sus atletas/clientes activos
            DB::table('entrenador_cliente')
                ->where('entrenador_id', $trainer->id)
                ->where('estado', 'activo')
                ->update([
                    'estado' => 'inactivo',
                    'fecha_desvinculacion' => $hoy,
                    'updated_at' => $ahora,
                ]);

            // 2. Eliminar los registros de la tabla pivote asociados al entrenador para evitar violación de llaves foráneas
            DB::table('entrenador_cliente')
                ->where('entrenador_id', $trainer->id)
                ->delete();

            // 3. Eliminar al entrenador
            $trainer->delete();
        });

        return redirect()->route('admin.trainers.index')->with('success', 'Entrenador eliminado y sus atletas han sido desvinculados exitosamente.');
    }

    /**
     * Asignación rápida: vincula un cliente a un entrenador seleccionado.
     */
    public function assignClient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entrenador_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('rol', 'entrenador'),
            ],
            'cliente_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('rol', 'cliente'),
            ],
        ], [
            'entrenador_id.required' => 'Debe seleccionar un entrenador válido.',
            'cliente_id.required' => 'Debe seleccionar un cliente para asignar.',
        ]);

        $entrenadorId = (int) $validated['entrenador_id'];
        $clienteId = (int) $validated['cliente_id'];
        $hoy = Carbon::now()->toDateString();
        $ahora = Carbon::now();

        DB::transaction(function () use ($entrenadorId, $clienteId, $hoy, $ahora) {
            // Desvincular cualquier entrenador activo previo que tuviera el cliente
            DB::table('entrenador_cliente')
                ->where('cliente_id', $clienteId)
                ->where('estado', 'activo')
                ->where('entrenador_id', '!=', $entrenadorId)
                ->update([
                    'estado' => 'inactivo',
                    'fecha_desvinculacion' => $hoy,
                    'updated_at' => $ahora,
                ]);

            // Comprobar si ya existió asignación previa con este mismo entrenador
            $asignacionPrevia = DB::table('entrenador_cliente')
                ->where('entrenador_id', $entrenadorId)
                ->where('cliente_id', $clienteId)
                ->first();

            if ($asignacionPrevia) {
                DB::table('entrenador_cliente')
                    ->where('entrenador_id', $entrenadorId)
                    ->where('cliente_id', $clienteId)
                    ->update([
                        'fecha_asignacion' => $hoy,
                        'fecha_desvinculacion' => null,
                        'estado' => 'activo',
                        'updated_at' => $ahora,
                    ]);
            } else {
                DB::table('entrenador_cliente')->insert([
                    'entrenador_id' => $entrenadorId,
                    'cliente_id' => $clienteId,
                    'fecha_asignacion' => $hoy,
                    'fecha_desvinculacion' => null,
                    'estado' => 'activo',
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                ]);
            }
        });

        return redirect()->route('admin.trainers.index')->with('success', 'Cliente vinculado correctamente al entrenador.');
    }

    /**
     * Desvincula o quita a un cliente asignado a un entrenador.
     */
    public function unassignClient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entrenador_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('rol', 'entrenador'),
            ],
            'cliente_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('rol', 'cliente'),
            ],
        ], [
            'entrenador_id.required' => 'Identificador de entrenador inválido.',
            'cliente_id.required' => 'Identificador de cliente inválido.',
        ]);

        $entrenadorId = (int) $validated['entrenador_id'];
        $clienteId = (int) $validated['cliente_id'];
        $hoy = Carbon::now()->toDateString();
        $ahora = Carbon::now();

        // Actualizar el estado en la tabla pivote entrenador_cliente
        $afectados = DB::table('entrenador_cliente')
            ->where('entrenador_id', $entrenadorId)
            ->where('cliente_id', $clienteId)
            ->where('estado', 'activo')
            ->update([
                'estado' => 'inactivo',
                'fecha_desvinculacion' => $hoy,
                'updated_at' => $ahora,
            ]);

        if ($afectados === 0) {
            DB::table('entrenador_cliente')
                ->where('entrenador_id', $entrenadorId)
                ->where('cliente_id', $clienteId)
                ->delete();
        }

        return redirect()->route('admin.trainers.index')->with('success', 'Cliente desvinculado del entrenador exitosamente.');
    }
}
