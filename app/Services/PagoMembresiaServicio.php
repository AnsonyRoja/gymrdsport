<?php

namespace App\Services;

use App\Models\Membresia;
use App\Models\MembresiaUsuario;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PagoMembresiaServicio
{
    public function __construct(
        protected TasaBcvServicio $tasaBcvServicio
    ) {}

    /**
     * TRX-01: Generación y Pago de Orden de Membresía
     *
     * Recibe el ID del cliente y monto a pagar. Consulta la tasa del día,
     * aplica descuentos o adelantos previos, genera la orden con estado 'Pagada' o 'Parcial',
     * extiende la vigencia de la membresía y actualiza el estatus a 'Activo'.
     *
     * @param int $usuarioId
     * @param int $membresiaId
     * @param float $montoPagadoUsd
     * @param string $metodoPago
     * @param string|null $referenciaPago
     * @param float $descuentoUsd
     * @param string|null $notas
     * @param string $tipoComprobante
     * @return array
     * @throws InvalidArgumentException|\Throwable
     */
    public function generarYProcesarOrden(
        int $usuarioId,
        int $membresiaId,
        float $montoPagadoUsd,
        string $metodoPago = 'Pago Móvil',
        ?string $referenciaPago = null,
        float $descuentoUsd = 0.0,
        ?string $notas = null,
        string $tipoComprobante = 'comprobante'
    ): array {
        $usuario = User::findOrFail($usuarioId);
        $membresia = Membresia::findOrFail($membresiaId);

        return DB::transaction(function () use (
            $usuario,
            $membresia,
            $montoPagadoUsd,
            $metodoPago,
            $referenciaPago,
            $descuentoUsd,
            $notas,
            $tipoComprobante
        ) {
            // 1. Consultar tasa oficial del día del BCV (RFC-02)
            $tasaBcv = $this->tasaBcvServicio->obtenerTasaUsd();

            // 2. Cálculo de importes
            $costoBaseUsd = (float) $membresia->costo_base;
            $montoFinalUsd = max(0.0, $costoBaseUsd - $descuentoUsd);
            $montoBs = $this->tasaBcvServicio->convertirUsdABs($montoFinalUsd, $tasaBcv);

            $montoPagadoBs = $this->tasaBcvServicio->convertirUsdABs($montoPagadoUsd, $tasaBcv);

            // Determinar tipo de pago y estado de la orden
            $tipoPago = 'completo';
            $estadoOrden = 'Pagada';

            if ($montoPagadoUsd < $montoFinalUsd && $montoPagadoUsd > 0) {
                $tipoPago = 'parcial';
                $estadoOrden = 'Parcial';
            } elseif ($montoPagadoUsd > $montoFinalUsd) {
                $tipoPago = 'adelanto';
                $estadoOrden = 'Pagada';
            }

            // 3. Generar series independientes y registrar la orden de pago (RFC-02)
            $tipoComp = in_array(strtolower($tipoComprobante), ['factura', 'comprobante']) ? strtolower($tipoComprobante) : 'comprobante';

            if ($tipoComp === 'factura') {
                // Serie Fiscal Independiente (FAC-000001...) con Número de Control Fiscal
                $consecutivoFactura = Order::whereNotNull('numero_factura')->count() + 1;
                $numeroFactura = sprintf('FAC-%06d', $consecutivoFactura);
                $numeroControl = sprintf('00-%06d', $consecutivoFactura);
                $numeroComprobante = null;
            } else {
                // Serie Administrativa de Comprobante / Recibo Independiente (CMP-000001...)
                $consecutivoComprobante = Order::whereNotNull('numero_comprobante')->count() + 1;
                $numeroComprobante = sprintf('CMP-%06d', $consecutivoComprobante);
                $numeroFactura = null;
                $numeroControl = null;
            }

            $orden = Order::create([
                'usuario_id' => $usuario->id,
                'membresia_id' => $membresia->id,
                'monto_usd' => $montoFinalUsd,
                'tasa_bcv' => $tasaBcv,
                'monto_bs' => $montoBs,
                'monto_pagado_usd' => $montoPagadoUsd,
                'monto_pagado_bs' => $montoPagadoBs,
                'descuento_usd' => $descuentoUsd,
                'tipo_pago' => $tipoPago,
                'metodo_pago' => $metodoPago,
                'referencia_pago' => $referenciaPago,
                'tipo_comprobante' => $tipoComp,
                'numero_comprobante' => $numeroComprobante,
                'numero_factura' => $numeroFactura,
                'numero_control' => $numeroControl,
                'estado' => $estadoOrden,
                'fecha_pago' => now()->toDateString(),
                'notas' => $notas,
            ]);

            // 4. Extender o crear la vigencia de membresía (TRX-01)
            $hoy = Carbon::now()->startOfDay();
            $diasDuracion = (int) $membresia->duracion_dias;

            // Buscar membresía vigente actual
            $suscripcionActual = MembresiaUsuario::where('usuario_id', $usuario->id)
                ->where('estado_pago', 'activo')
                ->whereDate('fecha_fin', '>=', $hoy)
                ->orderByDesc('fecha_fin')
                ->first();

            if ($suscripcionActual) {
                // Extensión acumulativa sobre la fecha fin existente
                $fechaInicio = Carbon::parse($suscripcionActual->fecha_inicio);
                $fechaFin = Carbon::parse($suscripcionActual->fecha_fin)->addDays($diasDuracion);
                
                $suscripcion = MembresiaUsuario::create([
                    'usuario_id' => $usuario->id,
                    'membresia_id' => $membresia->id,
                    'fecha_inicio' => Carbon::parse($suscripcionActual->fecha_fin)->addDay()->toDateString(),
                    'fecha_fin' => $fechaFin->toDateString(),
                    'estado_pago' => 'activo',
                ]);
            } else {
                // Nueva suscripción desde hoy
                $fechaInicio = $hoy;
                $fechaFin = (clone $hoy)->addDays($diasDuracion);

                $suscripcion = MembresiaUsuario::create([
                    'usuario_id' => $usuario->id,
                    'membresia_id' => $membresia->id,
                    'fecha_inicio' => $fechaInicio->toDateString(),
                    'fecha_fin' => $fechaFin->toDateString(),
                    'estado_pago' => 'activo',
                ]);
            }

            // 5. Actualizar el estatus del usuario a 'activo' (levantando morosidad)
            $usuario->update([
                'estado' => 'activo',
            ]);

            return [
                'success' => true,
                'orden' => $orden,
                'suscripcion' => $suscripcion,
                'fecha_inicio' => $suscripcion->fecha_inicio,
                'fecha_fin' => $suscripcion->fecha_fin,
                'tasa_bcv' => $tasaBcv,
                'mensaje' => 'Orden procesada y vigencia de membresía extendida con éxito.',
            ];
        });
    }
}
