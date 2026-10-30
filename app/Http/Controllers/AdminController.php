<?php

namespace App\Http\Controllers;

use App\Models\MembresiaUsuario;
use App\Models\User;
use App\Services\AsignacionEntrenadorServicio;
use App\Services\TasaBcvServicio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

class AdminController extends Controller
{
    public function __construct(
        protected AsignacionEntrenadorServicio $asignacionServicio,
        protected TasaBcvServicio $tasaBcvServicio
    ) {}

    /**
     * Muestra el panel principal de administración con las 5 métricas globales.
     */
    public function index(): View
    {
        // 1. Tasa oficial de cambio del Banco Central de Venezuela (BCV)
        $tasaBcv = $this->tasaBcvServicio->obtenerTasaUsd();

        // 2. Ingresos globales:
        // Suma del costo base de las membresías con estado_pago = 'activo'
        $totalIngresosUsd = (float) DB::table('membresias_usuarios')
            ->join('membresias', 'membresias_usuarios.membresia_id', '=', 'membresias.id')
            ->where('membresias_usuarios.estado_pago', 'activo')
            ->sum('membresias.costo_base');

        // Conversión oficial a Bolívares (VES)
        $totalIngresosBs = $this->tasaBcvServicio->convertirUsdABs($totalIngresosUsd, $tasaBcv);

        // 3. Usuarios activos (con membresía activa en membresias_usuarios)
        $usuariosActivos = MembresiaUsuario::where('estado_pago', 'activo')
            ->distinct('usuario_id')
            ->count('usuario_id');

        // 4. Usuarios morosos (con membresía morosa en membresias_usuarios)
        $usuariosMorosos = MembresiaUsuario::where('estado_pago', 'moroso')
            ->distinct('usuario_id')
            ->count('usuario_id');

        // Conteos por estado de membresía
        $conteoMembresiasActivas = MembresiaUsuario::where('estado_pago', 'activo')->count();
        $conteoMembresiasMorosas = MembresiaUsuario::where('estado_pago', 'moroso')->count();
        $conteoMembresiasInactivas = MembresiaUsuario::where('estado_pago', 'inactivo')->count();

        // 5. Asistencia general (conteo de accesos registrados)
        $conteoAsistenciaGeneral = 0;
        if (Schema::hasTable('asistencias')) {
            $conteoAsistenciaGeneral = DB::table('asistencias')->count();
        }

        // Tasa de conversión de usuarios (clientes convertidos a planes de pago)
        $totalUsuarios = User::count();
        $usuariosConMembresia = MembresiaUsuario::distinct('usuario_id')->count('usuario_id');
        $tasaConversion = $totalUsuarios > 0
            ? round(($usuariosConMembresia / $totalUsuarios) * 100, 2)
            : 0.0;

        return view('admin.dashboard', compact(
            'tasaBcv',
            'totalIngresosUsd',
            'totalIngresosBs',
            'usuariosActivos',
            'usuariosMorosos',
            'conteoMembresiasActivas',
            'conteoMembresiasMorosas',
            'conteoMembresiasInactivas',
            'conteoAsistenciaGeneral',
            'tasaConversion',
            'totalUsuarios',
            'usuariosConMembresia'
        ));
    }

    /**
     * Reasigna un cliente a un nuevo entrenador sin perder el historial anterior.
     */
    public function reasignarEntrenador(Request $request): JsonResponse|RedirectResponse
    {
        $datosValidados = $request->validate([
            'cliente_id' => 'required|integer|exists:users,id',
            'entrenador_origen_id' => 'required|integer|exists:users,id',
            'entrenador_destino_id' => 'required|integer|different:entrenador_origen_id|exists:users,id',
        ]);

        try {
            $resultado = $this->asignacionServicio->reasignar(
                (int) $datosValidados['cliente_id'],
                (int) $datosValidados['entrenador_origen_id'],
                (int) $datosValidados['entrenador_destino_id']
            );

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'data' => $resultado,
                ], 200);
            }

            return redirect()->back()->with('success', $resultado['mensaje']);
        } catch (InvalidArgumentException $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return redirect()->back()->withErrors(['reasignacion' => $e->getMessage()]);
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error inesperado al procesar la reasignación.',
                ], 500);
            }

            return redirect()->back()->withErrors(['reasignacion' => 'Error interno al reasignar entrenador.']);
        }
    }
}

