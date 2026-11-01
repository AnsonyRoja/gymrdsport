<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membresia;
use App\Models\MembresiaUsuario;
use App\Services\TasaBcvServicio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function __construct(
        protected TasaBcvServicio $tasaBcvServicio
    ) {}

    /**
     * Muestra el panel de administración de planes de membresía.
     */
    public function index(Request $request): View
    {
        $tasaBcv = $this->tasaBcvServicio->obtenerTasaUsd();

        $query = Membresia::withCount(['membresiasUsuarios as atletas_inscritos_count']);

        if ($request->filled('search')) {
            $busqueda = $request->search;
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        if ($request->filled('tipo')) {
            if ($request->tipo === 'vip') {
                $query->where(function ($q) {
                    $q->where('es_vip', true)->orWhere('tipo', 'vip');
                });
            } else {
                $query->where('tipo', $request->tipo);
            }
        }

        if ($request->filled('estado')) {
            $query->where('activo', $request->estado === 'activo');
        }

        $memberships = $query->orderBy('duracion_dias')->orderBy('costo_base')->paginate(12)->withQueryString();

        // Métricas rápidas
        $totalPlanes = Membresia::count();
        $planesActivos = Membresia::where('activo', true)->count();
        $planesVip = Membresia::where('es_vip', true)->orWhere('tipo', 'vip')->count();
        $totalAtletasSuscritos = MembresiaUsuario::where('estado_pago', 'activo')->count();

        return view('admin.memberships.index', compact(
            'memberships',
            'tasaBcv',
            'totalPlanes',
            'planesActivos',
            'planesVip',
            'totalAtletasSuscritos'
        ));
    }

    /**
     * Almacena un nuevo plan de membresía.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'costo_base' => 'required|numeric|min:0',
            'duracion_dias' => 'required|integer|min:1|max:1000',
            'tipo' => 'required|string|in:estandar,vip,promocional,estudiante',
            'es_vip' => 'nullable|boolean',
            'descripcion' => 'nullable|string|max:1000',
            'activo' => 'nullable|boolean',
        ], [
            'nombre.required' => 'El nombre del plan de membresía es obligatorio.',
            'costo_base.required' => 'Indique el costo base en USD.',
            'duracion_dias.required' => 'Indique la duración en días (ej: 30 para 1 mes).',
            'tipo.required' => 'Seleccione la categoría del plan.',
        ]);

        $esVip = $request->boolean('es_vip') || $validated['tipo'] === 'vip';

        Membresia::create([
            'nombre' => $validated['nombre'],
            'costo_base' => $validated['costo_base'],
            'duracion_dias' => $validated['duracion_dias'],
            'tipo' => $validated['tipo'],
            'es_vip' => $esVip,
            'descripcion' => $validated['descripcion'] ?? null,
            'activo' => $request->has('activo') ? $request->boolean('activo') : true,
        ]);

        return redirect()->route('admin.memberships.index')->with('success', "Plan de membresía '{$validated['nombre']}' creado exitosamente.");
    }

    /**
     * Actualiza un plan de membresía existente.
     */
    public function update(Request $request, Membresia $membership): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'costo_base' => 'required|numeric|min:0',
            'duracion_dias' => 'required|integer|min:1|max:1000',
            'tipo' => 'required|string|in:estandar,vip,promocional,estudiante',
            'es_vip' => 'nullable|boolean',
            'descripcion' => 'nullable|string|max:1000',
            'activo' => 'nullable|boolean',
        ]);

        $esVip = $request->boolean('es_vip') || $validated['tipo'] === 'vip';

        $membership->update([
            'nombre' => $validated['nombre'],
            'costo_base' => $validated['costo_base'],
            'duracion_dias' => $validated['duracion_dias'],
            'tipo' => $validated['tipo'],
            'es_vip' => $esVip,
            'descripcion' => $validated['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.memberships.index')->with('success', "Plan de membresía '{$membership->nombre}' actualizado con éxito.");
    }

    /**
     * Alterna el estado activo / inactivo de un plan de membresía.
     */
    public function toggleStatus(Membresia $membership): RedirectResponse
    {
        $membership->update([
            'activo' => !$membership->activo,
        ]);

        $estadoTexto = $membership->activo ? 'habilitado' : 'deshabilitado';

        return redirect()->route('admin.memberships.index')->with('success', "Plan '{$membership->nombre}' {$estadoTexto} correctamente.");
    }

    /**
     * Elimina o desactiva un plan de membresía.
     */
    public function destroy(Membresia $membership): RedirectResponse
    {
        // Verificar si tiene suscripciones u órdenes asociadas
        $tieneSuscripciones = $membership->membresiasUsuarios()->exists();

        if ($tieneSuscripciones) {
            $membership->update(['activo' => false]);
            return redirect()->route('admin.memberships.index')->with('warning', "El plan '{$membership->nombre}' tiene historial de suscripciones registradas, por lo que fue desactivado en lugar de eliminarse para proteger la integridad contable.");
        }

        $membership->delete();

        return redirect()->route('admin.memberships.index')->with('success', "Plan de membresía '{$membership->nombre}' eliminado con éxito.");
    }
}
