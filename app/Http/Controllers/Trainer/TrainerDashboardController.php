<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Rutina;
use App\Models\User;
use App\Services\ControlAccesoServicio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerDashboardController extends Controller
{
    public function __construct(
        protected ControlAccesoServicio $controlAccesoServicio
    ) {}

    /**
     * Muestra el Dashboard Operativo del Entrenador (Módulo C).
     */
    public function index(): View
    {
        $entrenador = Auth::user();

        // 1. Atletas asignados actualmente al entrenador
        $atletas = $entrenador->clientesActivos()
            ->with(['membresiasUsuarios' => function ($q) {
                $q->orderByDesc('fecha_fin');
            }, 'rutinas'])
            ->get();

        // 2. Alertas de vencimiento de membresías de atletas asignados (expiran en menos de 5 días o vencidas)
        $alertasVencimiento = $atletas->filter(function ($atleta) {
            $membresia = $atleta->membresiaActiva();
            if (!$membresia) {
                return true;
            }
            $diasRestantes = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($membresia->fecha_fin), false);
            return $diasRestantes <= 5;
        });

        // 3. Asistencia del entrenador hoy (check-in / check-out RFC-04)
        $asistenciaHoy = Attendance::where('user_id', $entrenador->id)
            ->where('fecha', now()->toDateString())
            ->first();

        // 4. Rutinas creadas por este entrenador
        $rutinas = Rutina::with('cliente')
            ->where('entrenador_id', $entrenador->id)
            ->latest()
            ->get();

        // 5. Notificaciones recibidas
        $notificaciones = $entrenador->notificaciones()->take(5)->get();

        return view('trainer.dashboard', compact(
            'entrenador',
            'atletas',
            'alertasVencimiento',
            'asistenciaHoy',
            'rutinas',
            'notificaciones'
        ));
    }

    /**
     * Check-in / Check-out rápido de jornada laboral desde el dashboard (RFC-04).
     */
    public function toggleShift(Request $request): RedirectResponse
    {
        $entrenador = Auth::user();
        $resultado = $this->controlAccesoServicio->procesarMarcaje($entrenador->email);

        if ($resultado['autorizado']) {
            return redirect()->route('trainer.dashboard')->with('success', $resultado['mensaje']);
        }

        return redirect()->route('trainer.dashboard')->withErrors(['shift_error' => $resultado['mensaje']]);
    }

    /**
     * Vista de detalle del perfil deportivo / médico de un atleta asignado.
     */
    public function showAthlete(User $athlete): View
    {
        $entrenador = Auth::user();

        // Verificar que el atleta esté asignado a este entrenador
        $esAsignado = $entrenador->clientesActivos()->where('users.id', $athlete->id)->exists();
        if (!$esAsignado && !$entrenador->isAdmin()) {
            abort(403, 'No tienes asignado a este atleta.');
        }

        $rutinas = Rutina::where('cliente_id', $athlete->id)->latest()->get();
        $membresia = $athlete->membresiaActiva();

        return view('trainer.athletes.show', compact('athlete', 'rutinas', 'membresia'));
    }

    /**
     * Guardar o actualizar rutina de ejercicios para un atleta (Módulo C).
     */
    public function storeRoutine(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:users,id',
            'titulo' => 'required|string|max:255',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'observaciones' => 'nullable|string|max:1000',
            'ejercicios' => 'required|array|min:1',
            'ejercicios.*.nombre' => 'required|string|max:255',
            'ejercicios.*.series' => 'required|numeric|min:1',
            'ejercicios.*.repeticiones' => 'required|string|max:50',
            'ejercicios.*.descanso' => 'nullable|string|max:50',
            'ejercicios.*.notas' => 'nullable|string|max:255',
        ]);

        $entrenador = Auth::user();

        Rutina::create([
            'cliente_id' => $validated['cliente_id'],
            'entrenador_id' => $entrenador->id,
            'titulo' => $validated['titulo'],
            'dia_semana' => $validated['dia_semana'],
            'observaciones' => $validated['observaciones'] ?? null,
            'ejercicios' => $validated['ejercicios'],
            'estado' => 'activa',
        ]);

        return redirect()->back()->with('success', 'Plan de rutina creado y asignado exitosamente al atleta.');
    }

    /**
     * Eliminar rutina asignada.
     */
    public function destroyRoutine(Rutina $routine): RedirectResponse
    {
        $entrenador = Auth::user();
        if ($routine->entrenador_id !== $entrenador->id && !$entrenador->isAdmin()) {
            abort(403, 'No autorizado para eliminar esta rutina.');
        }

        $routine->delete();

        return redirect()->back()->with('success', 'Rutina eliminada correctamente.');
    }
}
