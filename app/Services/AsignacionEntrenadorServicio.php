<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\Rutina;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AsignacionEntrenadorServicio
{
    /**
     * TRX-02: Reasignación Individual de Entrenador
     *
     * Desvincula la relación Usuario_ID <-> Entrenador_ID_Origen y crea el nuevo vínculo
     * Usuario_ID <-> Entrenador_ID_Destino. Transfiere el historial de rutinas activas
     * y notifica a ambas partes en el dashboard.
     *
     * @param int $clienteId
     * @param int $entrenadorOrigenId
     * @param int $entrenadorDestinoId
     * @return array
     * @throws InvalidArgumentException|\Throwable
     */
    public function reasignar(int $clienteId, int $entrenadorOrigenId, int $entrenadorDestinoId): array
    {
        if ($entrenadorOrigenId === $entrenadorDestinoId) {
            throw new InvalidArgumentException('El entrenador de origen y de destino no pueden ser el mismo.');
        }

        return DB::transaction(function () use ($clienteId, $entrenadorOrigenId, $entrenadorDestinoId) {
            $ahora = Carbon::now();
            $hoy = $ahora->toDateString();

            // 1. Desvincular al entrenador actual (actualiza el estado a inactivo y registra la fecha de desvinculación)
            $filasActualizadas = DB::table('entrenador_cliente')
                ->where('cliente_id', $clienteId)
                ->where('entrenador_id', $entrenadorOrigenId)
                ->update([
                    'estado' => 'inactivo',
                    'fecha_desvinculacion' => $hoy,
                    'updated_at' => $ahora,
                ]);

            if ($filasActualizadas === 0) {
                throw new InvalidArgumentException('No existe una vinculación activa previa entre el cliente y el entrenador de origen especificado.');
            }

            // 2. Vincular o reactivar al nuevo entrenador
            $asignacionPreviaDestino = DB::table('entrenador_cliente')
                ->where('cliente_id', $clienteId)
                ->where('entrenador_id', $entrenadorDestinoId)
                ->first();

            if ($asignacionPreviaDestino) {
                DB::table('entrenador_cliente')
                    ->where('cliente_id', $clienteId)
                    ->where('entrenador_id', $entrenadorDestinoId)
                    ->update([
                        'fecha_asignacion' => $hoy,
                        'fecha_desvinculacion' => null,
                        'estado' => 'activo',
                        'updated_at' => $ahora,
                    ]);
            } else {
                DB::table('entrenador_cliente')->insert([
                    'cliente_id' => $clienteId,
                    'entrenador_id' => $entrenadorDestinoId,
                    'fecha_asignacion' => $hoy,
                    'fecha_desvinculacion' => null,
                    'estado' => 'activo',
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                ]);
            }

            // 3. Transferir el historial de rutinas activas (TRX-02)
            $rutinasTransferidas = Rutina::where('cliente_id', $clienteId)
                ->where('entrenador_id', $entrenadorOrigenId)
                ->update(['entrenador_id' => $entrenadorDestinoId]);

            // 4. Notificaciones en el dashboard (TRX-02)
            $cliente = User::find($clienteId);
            $entrenadorNuevo = User::find($entrenadorDestinoId);
            $entrenadorViejo = User::find($entrenadorOrigenId);

            $nombreNuevo = $entrenadorNuevo ? $entrenadorNuevo->name : 'Nuevo Entrenador';
            $nombreViejo = $entrenadorViejo ? $entrenadorViejo->name : 'Entrenador Anterior';
            $nombreCliente = $cliente ? $cliente->name : 'Atleta';

            // Notificación al Cliente
            Notificacion::create([
                'user_id' => $clienteId,
                'titulo' => 'Actualización de Entrenador Asignado',
                'mensaje' => "Tu nuevo entrenador asignado es {$nombreNuevo}. Tu historial de rutinas ha sido transferido.",
                'tipo' => 'info',
            ]);

            // Notificación al Nuevo Entrenador
            Notificacion::create([
                'user_id' => $entrenadorDestinoId,
                'titulo' => 'Nuevo Atleta Asignado',
                'mensaje' => "Se te ha asignado al atleta {$nombreCliente}. Puedes consultar su perfil y gestionar sus rutinas.",
                'tipo' => 'exito',
            ]);

            // Notificación al Entrenador Anterior
            Notificacion::create([
                'user_id' => $entrenadorOrigenId,
                'titulo' => 'Reasignación de Atleta',
                'mensaje' => "El atleta {$nombreCliente} ha sido transferido a {$nombreNuevo}.",
                'tipo' => 'info',
            ]);

            return [
                'cliente_id' => $clienteId,
                'entrenador_anterior_id' => $entrenadorOrigenId,
                'entrenador_nuevo_id' => $entrenadorDestinoId,
                'rutinas_transferidas' => $rutinasTransferidas,
                'fecha_reasignacion' => $hoy,
                'mensaje' => 'Reasignación de entrenador completada exitosamente, rutinas transferidas y partes notificadas.',
            ];
        });
    }

    /**
     * TRX-02: Reasignación Masiva de Clientes a un Nuevo Entrenador
     *
     * @param array $clientesIds
     * @param int $entrenadorOrigenId
     * @param int $entrenadorDestinoId
     * @return array
     */
    public function reasignarMasivo(array $clientesIds, int $entrenadorOrigenId, int $entrenadorDestinoId): array
    {
        $resultados = [];
        $errores = [];

        foreach ($clientesIds as $clienteId) {
            try {
                $res = $this->reasignar((int) $clienteId, $entrenadorOrigenId, $entrenadorDestinoId);
                $resultados[] = $res;
            } catch (\Throwable $e) {
                $errores[] = [
                    'cliente_id' => $clienteId,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'total_procesados' => count($clientesIds),
            'exitosos' => count($resultados),
            'fallidos' => count($errores),
            'detalles' => $resultados,
            'errores' => $errores,
        ];
    }
}
