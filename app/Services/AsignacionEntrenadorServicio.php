<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AsignacionEntrenadorServicio
{
    /**
     * Reasigna un cliente de un entrenador a otro de forma transaccional,
     * conservando el registro histórico de asignaciones previas.
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

            // 2. Verificar si el entrenador destino ya tuvo asignado a este cliente anteriormente
            $asignacionPreviaDestino = DB::table('entrenador_cliente')
                ->where('cliente_id', $clienteId)
                ->where('entrenador_id', $entrenadorDestinoId)
                ->first();

            if ($asignacionPreviaDestino) {
                // Reactivar la asignación con la nueva fecha
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
                // Insertar el nuevo registro de asignación
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

            return [
                'cliente_id' => $clienteId,
                'entrenador_anterior_id' => $entrenadorOrigenId,
                'entrenador_nuevo_id' => $entrenadorDestinoId,
                'fecha_reasignacion' => $hoy,
                'mensaje' => 'Reasignación de entrenador completada exitosamente sin pérdida de historial.',
            ];
        });
    }
}

