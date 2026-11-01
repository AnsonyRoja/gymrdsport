<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\User;
use App\Services\TasaBcvServicio;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected TasaBcvServicio $tasaBcvServicio
    ) {}

    /**
     * Muestra la vista consolidada del Dashboard según el rol del usuario (Módulo B).
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        // El administrador gestiona la operación del gimnasio, no tiene perfil atlético
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Si es entrenador, redirigir a su panel operativo
        if ($user->isEntrenador()) {
            return redirect()->route('trainer.dashboard');
        }

        // Datos para Módulo de Clientes (Módulo B):
        // 1. Tasa oficial BCV del día
        $tasaBcv = $this->tasaBcvServicio->obtenerTasaUsd();

        // 2. Membresía activa o más reciente
        $membresiaUsuario = $user->membresiaActiva();
        $diasRestantes = 0;
        $diasGraciaRestantes = 0;
        $estadoMembresia = 'Sin membresía';
        $esMoroso = $user->isMoroso();

        if ($user->isAdmin()) {
            $estadoMembresia = 'Acceso Total (Administrador)';
        } elseif ($user->isEntrenador()) {
            $estadoMembresia = 'Personal Técnico';
        } elseif ($membresiaUsuario) {
            $hoy = now()->startOfDay();
            $fechaFin = Carbon::parse($membresiaUsuario->fecha_fin)->startOfDay();
            $diasGracia = $user->dias_gracia ?? 3;
            $fechaFinConGracia = (clone $fechaFin)->addDays($diasGracia);

            $diasRestantes = $hoy->diffInDays($fechaFin, false);
            $diasGraciaRestantes = $hoy->diffInDays($fechaFinConGracia, false);

            if ($esMoroso) {
                $estadoMembresia = 'Moroso (Vencida)';
            } elseif ($diasRestantes >= 0) {
                $estadoMembresia = 'Activa';
            } elseif ($diasGraciaRestantes >= 0) {
                $estadoMembresia = 'Período de Gracia';
            } else {
                $estadoMembresia = 'Vencida';
            }
        } else {
            $estadoMembresia = 'Sin membresía';
        }

        // 3. Entrenador asignado activo
        $entrenadorAsignado = $user->entrenadorActivo()->first();

        // 4. Rutinas asignadas al cliente (organizadas por día)
        $diasOrden = ['Lunes' => 1, 'Martes' => 2, 'Miércoles' => 3, 'Jueves' => 4, 'Viernes' => 5, 'Sábado' => 6, 'Domingo' => 7];
        $rutinas = $user->rutinas()
            ->where('estado', 'activa')
            ->get()
            ->sortBy(fn($r) => $diasOrden[$r->dia_semana] ?? 99);

        // 5. Historial de pagos y órdenes (RFC-02)
        $historialPagos = $user->orders()->take(10)->get();

        // 6. Asistencias recientes
        $asistenciasRecientes = $user->asistencias()->take(10)->get();

        // 7. Notificaciones del sistema
        $notificaciones = $user->notificaciones()->take(5)->get();

        return view('dashboard', compact(
            'user',
            'tasaBcv',
            'membresiaUsuario',
            'diasRestantes',
            'diasGraciaRestantes',
            'estadoMembresia',
            'esMoroso',
            'entrenadorAsignado',
            'rutinas',
            'historialPagos',
            'asistenciasRecientes',
            'notificaciones'
        ));
    }

    /**
     * Actualiza el perfil físico, médico y objetivos del cliente (Módulo B - Formulario de Inscripción).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'cedula' => 'nullable|string|max:30',
            'telefono' => 'nullable|string|max:30',
            'peso' => 'nullable|numeric|min:20|max:300',
            'altura' => 'nullable|numeric|min:50|max:260',
            'historial_medico' => 'nullable|string|max:1000',
            'objetivos' => 'nullable|string|max:1000',
        ]);

        $user->update($validated);

        return redirect()->route('dashboard')->with('success', 'Perfil físico y médico actualizado correctamente.');
    }

    /**
     * Marcar notificación como leída.
     */
    public function markNotificationAsRead(Notificacion $notificacion): RedirectResponse
    {
        if ($notificacion->user_id === Auth::id()) {
            $notificacion->update(['leida' => true]);
        }

        return redirect()->back();
    }
}
