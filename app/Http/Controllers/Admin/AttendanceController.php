<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    /**
     * Muestra la tabla corporativa minimalista de asistencia del personal y formulario de marcaje.
     */
    public function index(): View
    {
        $attendances = Attendance::with('user')
            ->orderBy('fecha', 'desc')
            ->orderBy('entrada', 'desc')
            ->paginate(15);

        // Obtener personal técnico y administrativo (excluyendo solo clientes)
        $staffMembers = User::whereIn('rol', ['admin', 'entrenador'])
            ->where('estado', 'activo')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'rol']);

        // Marcajes activos hoy que aún no tienen salida registrada
        $hoy = Carbon::now('America/Caracas')->toDateString();
        $marcajesActivosHoy = Attendance::where('fecha', $hoy)
            ->whereNull('salida')
            ->pluck('user_id')
            ->toArray();

        return view('admin.attendance.index', compact('attendances', 'staffMembers', 'marcajesActivosHoy'));
    }

    /**
     * Procesa el marcaje de entrada (check-in) o salida (check-out) del personal.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
            ],
            'tipo_marcaje' => ['required', 'string', Rule::in(['entrada', 'salida'])],
            'hora_turno' => ['nullable', 'date_format:H:i'],
            'hora_marcaje' => ['nullable', 'date_format:H:i'],
            'observaciones' => ['nullable', 'string', 'max:255'],
        ], [
            'user_id.required' => 'Debe seleccionar un miembro del personal.',
            'tipo_marcaje.required' => 'Debe indicar el tipo de marcaje.',
        ]);

        $userId = (int) $validated['user_id'];
        $tipoMarcaje = $validated['tipo_marcaje'];

        // Obtener la fecha y hora actual en la zona horaria de Venezuela ('America/Caracas')
        $ahora = Carbon::now('America/Caracas');
        $hoy = $ahora->toDateString();

        // 2. Validación: La hora de entrada o salida no puede ser mayor a la hora actual del sistema
        if (!empty($validated['hora_marcaje'])) {
            $horaMarcajeCarbon = Carbon::createFromFormat('Y-m-d H:i', "{$hoy} {$validated['hora_marcaje']}", 'America/Caracas');

            if ($horaMarcajeCarbon->greaterThan($ahora)) {
                return redirect()->route('admin.attendance.index')
                    ->withInput()
                    ->withErrors([
                        'hora_marcaje' => 'La hora de marcaje no puede ser posterior a la hora actual.'
                    ]);
            }

            $horaMarcaje = $horaMarcajeCarbon->format('H:i:s');
        } else {
            $horaMarcaje = $ahora->format('H:i:s');
        }

        if ($tipoMarcaje === 'entrada') {
            // Verificar si el usuario ya registró entrada hoy y aún no marca salida
            $registroExistente = Attendance::where('user_id', $userId)
                ->where('fecha', $hoy)
                ->whereNull('salida')
                ->first();

            if ($registroExistente) {
                return redirect()->route('admin.attendance.index')->withErrors([
                    'marcaje' => 'Este usuario ya tiene una entrada registrada hoy y su turno se encuentra activo.'
                ]);
            }

            // Evaluar hora contra el turno establecido (por defecto 08:00 si no se especifica)
            $horaTurnoTexto = $validated['hora_turno'] ?? '08:00';
            $horaTurno = Carbon::createFromFormat('H:i', $horaTurnoTexto, 'America/Caracas');
            $horaEntradaCarbon = Carbon::createFromFormat('H:i:s', $horaMarcaje, 'America/Caracas');

            // Tolerancia de 10 minutos para puntualidad
            $limitePuntualidad = $horaTurno->copy()->addMinutes(10);
            // Si supera más de 120 minutos (2 horas) se considera inasistencia injustificada
            $limiteInasistencia = $horaTurno->copy()->addMinutes(120);

            if ($horaEntradaCarbon->greaterThan($limiteInasistencia)) {
                $estatus = 'Inasistencia';
            } elseif ($horaEntradaCarbon->greaterThan($limitePuntualidad)) {
                $estatus = 'Retardo';
            } else {
                $estatus = 'A tiempo';
            }

            Attendance::create([
                'user_id' => $userId,
                'fecha' => $hoy,
                'hora_turno_inicio' => $horaTurno->format('H:i:s'),
                'entrada' => $horaMarcaje,
                'salida' => null,
                'estatus' => $estatus,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            return redirect()->route('admin.attendance.index')->with('success', "Entrada registrada exitosamente con estatus: {$estatus}.");
        } else {
            // Check-out (Salida)
            $registroActivo = Attendance::where('user_id', $userId)
                ->where('fecha', $hoy)
                ->whereNull('salida')
                ->latest('entrada')
                ->first();

            if (!$registroActivo) {
                // Si no hay entrada previa hoy, busca el último turno pendiente
                $registroActivo = Attendance::where('user_id', $userId)
                    ->whereNull('salida')
                    ->latest('fecha')
                    ->first();
            }

            if (!$registroActivo) {
                return redirect()->route('admin.attendance.index')->withErrors([
                    'marcaje' => 'No se encontró un registro de entrada pendiente de salida para este usuario.'
                ]);
            }

            // Validar que la hora de salida no sea anterior a la de entrada
            $horaEntradaGuardada = Carbon::createFromFormat('H:i:s', $registroActivo->entrada, 'America/Caracas');
            $horaSalidaCarbon = Carbon::createFromFormat('H:i:s', $horaMarcaje, 'America/Caracas');

            if ($horaSalidaCarbon->lessThan($horaEntradaGuardada)) {
                return redirect()->route('admin.attendance.index')
                    ->withInput()
                    ->withErrors([
                        'hora_marcaje' => 'La hora de salida no puede ser anterior a la hora de entrada registrada (' . $horaEntradaGuardada->format('h:i A') . ').'
                    ]);
            }

            $registroActivo->update([
                'salida' => $horaMarcaje,
                'observaciones' => $validated['observaciones'] ? ($registroActivo->observaciones . ' | ' . $validated['observaciones']) : $registroActivo->observaciones,
            ]);

            return redirect()->route('admin.attendance.index')->with('success', 'Salida (check-out) registrada exitosamente.');
        }
    }
}
