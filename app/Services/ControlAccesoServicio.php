<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ControlAccesoServicio
{
    /**
     * TRX-03: Marcaje de Asistencia y Verificación de Acceso
     *
     * Evalúa en un solo flujo la identidad del usuario/entrenador,
     * verifica su rol e indica si su membresía/horario está al día.
     * Si cumple, inserta el registro de asistencia con timestamp y autoriza el ingreso;
     * si es moroso o está fuera de horario, rechaza la transacción.
     *
     * @param string $identificador Email, Cédula o ID de usuario
     * @param string $horaTurnoProgramada Opcional, para entrenadores (formato H:i)
     * @return array
     */
    public function procesarMarcaje(string $identificador, ?string $horaTurnoProgramada = '08:00'): array
    {
        $identificadorLimpio = trim($identificador);

        // Buscar usuario por ID numérico, email o cédula
        $usuario = User::query()
            ->where('id', $identificadorLimpio)
            ->orWhere('email', $identificadorLimpio)
            ->orWhere('cedula', $identificadorLimpio)
            ->first();

        if (!$usuario) {
            return [
                'autorizado' => false,
                'codigo' => 'NO_ENCONTRADO',
                'titulo' => 'Usuario No Identificado',
                'mensaje' => 'No se encontró ningún usuario con el identificador ingresado.',
                'usuario' => null,
            ];
        }

        $ahora = Carbon::now();
        $hoy = $ahora->toDateString();
        $horaActual = $ahora->format('H:i:s');

        // Flujo A: Si es ENTRENADOR o STAFF (RFC-04)
        if ($usuario->isEntrenador() || $usuario->rol === 'entrenador') {
            return DB::transaction(function () use ($usuario, $hoy, $horaActual, $horaTurnoProgramada, $ahora) {
                // Verificar si ya tiene check-in hoy sin check-out
                $asistenciaAbierta = Attendance::where('user_id', $usuario->id)
                    ->whereDate('fecha', $hoy)
                    ->whereNull('salida')
                    ->first();

                if ($asistenciaAbierta) {
                    // Check-out: Registrar hora de salida
                    $asistenciaAbierta->update([
                        'salida' => $horaActual,
                        'estatus' => 'Turno Cumplido',
                        'observaciones' => ($asistenciaAbierta->observaciones ? $asistenciaAbierta->observaciones . ' | ' : '') . 'Salida de turno registrada.',
                    ]);

                    return [
                        'autorizado' => true,
                        'codigo' => 'SALIDA_REGISTRADA',
                        'titulo' => 'Check-out Exitoso',
                        'mensaje' => "Jornada laboral finalizada para el entrenador {$usuario->name}.",
                        'usuario' => $usuario,
                        'tipo' => 'entrenador',
                        'hora' => $horaActual,
                        'estatus' => 'Turno Cumplido',
                    ];
                }

                // Check-in de inicio de turno: Evaluar puntualidad vs horario programado (RFC-04)
                $horaTurno = Carbon::parse($horaTurnoProgramada);
                $horaLlegada = Carbon::parse($horaActual);
                $diferenciaMinutos = $horaTurno->diffInMinutes($horaLlegada, false);

                // Si llega más de 15 minutos tarde: Retardo
                $estatus = ($diferenciaMinutos > 15) ? 'Retardo' : 'Puntual';
                $obs = $estatus === 'Retardo' 
                    ? "Llegada con retardo de {$diferenciaMinutos} min respecto al turno {$horaTurnoProgramada}."
                    : 'Ingreso puntual a su turno de trabajo.';

                $asistencia = Attendance::create([
                    'user_id' => $usuario->id,
                    'fecha' => $hoy,
                    'hora_turno_inicio' => $horaTurnoProgramada . ':00',
                    'entrada' => $horaActual,
                    'salida' => null,
                    'tipo_registro' => 'jornada_entrenador',
                    'resultado_acceso' => 'autorizado',
                    'estatus' => $estatus,
                    'observaciones' => $obs,
                ]);

                return [
                    'autorizado' => true,
                    'codigo' => 'ENTRADA_ENTRENADOR',
                    'titulo' => 'Check-in de Entrenador',
                    'mensaje' => "Entrada registrada para {$usuario->name} ({$estatus}).",
                    'usuario' => $usuario,
                    'tipo' => 'entrenador',
                    'hora' => $horaActual,
                    'estatus' => $estatus,
                ];
            });
        }

        // Flujo B: Si es ADMINISTRADOR
        if ($usuario->isAdmin()) {
            Attendance::create([
                'user_id' => $usuario->id,
                'fecha' => $hoy,
                'entrada' => $horaActual,
                'salida' => null,
                'tipo_registro' => 'acceso_gimnasio',
                'resultado_acceso' => 'autorizado',
                'estatus' => 'Puntual',
                'observaciones' => 'Ingreso de Personal Administrativo.',
            ]);

            return [
                'autorizado' => true,
                'codigo' => 'ADMIN_AUTORIZADO',
                'titulo' => 'Acceso Administrativo',
                'mensaje' => "Bienvenido(a), Administrador(a) {$usuario->name}.",
                'usuario' => $usuario,
                'tipo' => 'admin',
                'hora' => $horaActual,
                'estatus' => 'Puntual',
            ];
        }

        // Flujo C: Si es CLIENTE / ATLETA (TRX-03 & RFC-01)
        $membresiaActiva = $usuario->membresiaActiva();
        $esMoroso = $usuario->isMoroso();

        if ($esMoroso || !$membresiaActiva) {
            // RECHAZO: Registrar incidencia de acceso denegado por morosidad
            Attendance::create([
                'user_id' => $usuario->id,
                'fecha' => $hoy,
                'entrada' => $horaActual,
                'salida' => null,
                'tipo_registro' => 'acceso_gimnasio',
                'resultado_acceso' => 'rechazado_moroso',
                'estatus' => 'Ausencia',
                'observaciones' => 'Acceso rechazado por morosidad o membresía expirada.',
            ]);

            return [
                'autorizado' => false,
                'codigo' => 'RECHAZADO_MOROSO',
                'titulo' => 'Acceso Denegado - Pago Pendiente',
                'mensaje' => "El cliente {$usuario->name} posee una membresía vencida o estado moroso. Debe regularizar su mensualidad para ingresar.",
                'usuario' => $usuario,
                'tipo' => 'cliente',
                'membresia' => $membresiaActiva,
            ];
        }

        // AUTORIZADO: Membresía al día
        Attendance::create([
            'user_id' => $usuario->id,
            'fecha' => $hoy,
            'entrada' => $horaActual,
            'salida' => null,
            'tipo_registro' => 'acceso_gimnasio',
            'resultado_acceso' => 'autorizado',
            'estatus' => 'Puntual',
            'observaciones' => "Membresía activa: {$membresiaActiva->membresia->nombre} (Vence: {$membresiaActiva->fecha_fin})",
        ]);

        return [
            'autorizado' => true,
            'codigo' => 'AUTORIZADO',
            'titulo' => '¡Acceso Autorizado!',
            'mensaje' => "Bienvenido(a) a Gym RD Sport, {$usuario->name}. ¡Buen entrenamiento!",
            'usuario' => $usuario,
            'tipo' => 'cliente',
            'hora' => $horaActual,
            'membresia' => $membresiaActiva,
        ];
    }
}
