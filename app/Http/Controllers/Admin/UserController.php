<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AsignacionEntrenadorServicio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class UserController extends Controller
{
    public function __construct(
        protected AsignacionEntrenadorServicio $asignacionServicio
    ) {}

    /**
     * Muestra el listado de clientes/usuarios con su entrenador asignado actual
     * y la lista de entrenadores disponibles para reasignación dinámica.
     */
    public function index(): View
    {
        // Obtener clientes con su entrenador activo (a través de la relación entrenadorActivo)
        $clients = User::where('rol', 'cliente')
            ->with(['entrenadorActivo' => function ($query) {
                $query->select('users.id', 'users.name', 'users.email');
            }])
            ->latest()
            ->paginate(15);

        // Lista de entrenadores activos para el selector de reasignación
        $trainers = User::where('rol', 'entrenador')
            ->where('estado', 'activo')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.users.index', compact('clients', 'trainers'));
    }

    /**
     * Reasigna dinámicamente un nuevo entrenador a un cliente específico (RFC-03 / TRX-02)
     * o desvincula al entrenador actual dejándolo como "Sin Entrenador".
     */
    public function reassignTrainer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('rol', 'cliente'),
            ],
            'entrenador_destino_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('rol', 'entrenador'),
            ],
            'entrenador_origen_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('rol', 'entrenador'),
            ],
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente válido.',
            'entrenador_destino_id.exists' => 'El entrenador seleccionado no es válido.',
        ]);

        $clienteId = (int) $validated['cliente_id'];
        $entrenadorDestinoId = !empty($validated['entrenador_destino_id']) ? (int) $validated['entrenador_destino_id'] : null;

        // Si no se proporcionó entrenador_origen_id, buscar el entrenador activo actual del cliente
        $cliente = User::with('entrenadorActivo')->findOrFail($clienteId);
        $entrenadorActual = $cliente->entrenadorActivo->first();
        $entrenadorOrigenId = !empty($validated['entrenador_origen_id']) 
            ? (int) $validated['entrenador_origen_id'] 
            : ($entrenadorActual ? $entrenadorActual->id : null);

        // Caso 1: Se seleccionó "Sin Entrenador" (null / vacío)
        if ($entrenadorDestinoId === null) {
            if (!$entrenadorOrigenId) {
                return redirect()->route('admin.users.index')->withErrors([
                    'reassign' => 'El cliente ya se encuentra sin entrenador asignado.'
                ]);
            }

            // Desvincular al entrenador actual limpiamente en la tabla pivote
            $hoy = now()->toDateString();
            $ahora = now();

            \Illuminate\Support\Facades\DB::table('entrenador_cliente')
                ->where('cliente_id', $clienteId)
                ->where('estado', 'activo')
                ->update([
                    'estado' => 'inactivo',
                    'fecha_desvinculacion' => $hoy,
                    'updated_at' => $ahora,
                ]);

            return redirect()->route('admin.users.index')->with('success', 'Entrenador desvinculado exitosamente. El cliente ahora figura como sin asignar.');
        }

        // Caso 2: El entrenador destino es el mismo que el actual
        if ($entrenadorOrigenId === $entrenadorDestinoId) {
            return redirect()->route('admin.users.index')->withErrors([
                'reassign' => 'El cliente ya se encuentra actualmente asignado a este mismo entrenador.'
            ]);
        }

        try {
            if ($entrenadorOrigenId) {
                // Ejecución transaccional limpia desvinculando al anterior e integrando al nuevo
                $this->asignacionServicio->reasignar($clienteId, $entrenadorOrigenId, $entrenadorDestinoId);
            } else {
                // Si el cliente no tenía entrenador previo, se vincula directamente
                \Illuminate\Support\Facades\DB::table('entrenador_cliente')->insert([
                    'entrenador_id' => $entrenadorDestinoId,
                    'cliente_id' => $clienteId,
                    'fecha_asignacion' => now()->toDateString(),
                    'fecha_desvinculacion' => null,
                    'estado' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->route('admin.users.index')->with('success', 'Entrenador reasignado exitosamente al cliente.');
        } catch (InvalidArgumentException $e) {
            return redirect()->route('admin.users.index')->withErrors(['reassign' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return redirect()->route('admin.users.index')->withErrors(['reassign' => 'Error interno al procesar la reasignación.']);
        }
    }

    /**
     * TRX-02: Reasignación Masiva de Clientes a un Nuevo Entrenador
     */
    public function reassignBulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'clientes_ids' => 'required|array|min:1',
            'clientes_ids.*' => 'required|integer|exists:users,id',
            'entrenador_origen_id' => 'required|integer|exists:users,id',
            'entrenador_destino_id' => 'required|integer|different:entrenador_origen_id|exists:users,id',
        ], [
            'clientes_ids.required' => 'Debe seleccionar al menos un cliente.',
            'entrenador_destino_id.different' => 'El entrenador de destino debe ser diferente al de origen.',
        ]);

        $resultado = $this->asignacionServicio->reasignarMasivo(
            $validated['clientes_ids'],
            (int) $validated['entrenador_origen_id'],
            (int) $validated['entrenador_destino_id']
        );

        return redirect()->route('admin.users.index')->with(
            'success',
            "Reasignación masiva completada: {$resultado['exitosos']} clientes transferidos con éxito."
        );
    }
}
