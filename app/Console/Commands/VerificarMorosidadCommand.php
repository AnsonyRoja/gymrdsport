<?php

namespace App\Console\Commands;

use App\Models\MembresiaUsuario;
use App\Models\Notificacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class VerificarMorosidadCommand extends Command
{
    /**
     * El nombre y firma del comando de consola.
     */
    protected $signature = 'gym:verificar-morosidad';

    /**
     * Descripción del comando.
     */
    protected $description = 'RFC-01: Evalúa el estatus de membresías y marca automáticamente a los clientes morosos tras vencer sus días de gracia.';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle(): int
    {
        $this->info('Iniciando evaluación de morosidad diaria...');
        $hoy = Carbon::now()->startOfDay();
        $contadorMorosos = 0;

        // 1. Obtener todas las suscripciones activas
        $suscripcionesActivas = MembresiaUsuario::where('estado_pago', 'activo')
            ->with('usuario')
            ->get();

        foreach ($suscripcionesActivas as $suscripcion) {
            $usuario = $suscripcion->usuario;
            if (!$usuario || $usuario->isAdmin() || $usuario->isEntrenador()) {
                continue;
            }

            $diasGracia = $usuario->dias_gracia ?? 3;
            $fechaCorteConGracia = Carbon::parse($suscripcion->fecha_fin)->addDays($diasGracia)->endOfDay();

            // Si la fecha de corte con días de gracia ya pasó
            if ($hoy->greaterThan($fechaCorteConGracia)) {
                // Verificar si tiene alguna suscripción futura o renovación ya pagada
                $tieneRenovacion = MembresiaUsuario::where('usuario_id', $usuario->id)
                    ->where('id', '!=', $suscripcion->id)
                    ->where('estado_pago', 'activo')
                    ->whereDate('fecha_fin', '>=', $hoy)
                    ->exists();

                if (!$tieneRenovacion) {
                    $suscripcion->update(['estado_pago' => 'moroso']);
                    $usuario->update(['estado' => 'moroso']);

                    // Crear notificación para el usuario
                    Notificacion::create([
                        'user_id' => $usuario->id,
                        'titulo' => 'Membresía Vencida - Estado Moroso',
                        'mensaje' => "Tu período de membresía ha expirado incluyendo los días de gracia. Por favor acércate a recepción o realiza tu pago para continuar disfrutando de las instalaciones.",
                        'tipo' => 'alerta',
                    ]);

                    $this->warn("Cliente {$usuario->name} (#{$usuario->id}) marcado como MOROSO.");
                    $contadorMorosos++;
                }
            }
        }

        $this->info("Evaluación finalizada. Total de clientes actualizados a moroso: {$contadorMorosos}");

        return Command::SUCCESS;
    }
}
