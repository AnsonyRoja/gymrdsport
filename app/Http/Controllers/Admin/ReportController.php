<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Membresia;
use App\Models\MembresiaUsuario;
use App\Models\Order;
use App\Models\Rutina;
use App\Models\User;
use App\Services\TasaBcvServicio;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct(
        protected TasaBcvServicio $tasaBcvServicio
    ) {}

    /**
     * Muestra el centro de reportes gerenciales y operativos del gimnasio.
     */
    public function index(Request $request): View
    {
        $periodo = $request->get('periodo', 'este_mes');
        $tab = $request->get('tab', 'financiero');
        $tasaBcv = $this->tasaBcvServicio->obtenerTasaUsd();

        // Determinar rango de fechas
        $hoy = Carbon::now()->startOfDay();
        [$fechaInicio, $fechaFin] = match ($periodo) {
            'hoy' => [$hoy->copy(), $hoy->copy()->endOfDay()],
            'semana' => [$hoy->copy()->startOfWeek(), $hoy->copy()->endOfWeek()],
            'este_mes' => [$hoy->copy()->startOfMonth(), $hoy->copy()->endOfMonth()],
            'mes_anterior' => [$hoy->copy()->subMonth()->startOfMonth(), $hoy->copy()->subMonth()->endOfMonth()],
            'ano' => [$hoy->copy()->startOfYear(), $hoy->copy()->endOfYear()],
            'personalizado' => [
                $request->filled('desde') ? Carbon::parse($request->desde)->startOfDay() : $hoy->copy()->startOfMonth(),
                $request->filled('hasta') ? Carbon::parse($request->hasta)->endOfDay() : $hoy->copy()->endOfDay(),
            ],
            default => [$hoy->copy()->startOfMonth(), $hoy->copy()->endOfMonth()],
        };

        // -------------------------------------------------------------
        // 1. REPORTE FINANCIERO Y FACTURACIÓN
        // -------------------------------------------------------------
        $queryOrdenesPeriodo = Order::whereBetween('fecha_pago', [
            $fechaInicio->toDateString(),
            $fechaFin->toDateString()
        ]);

        $totalIngresosUsd = (float) (clone $queryOrdenesPeriodo)->where('estado', 'Pagada')->sum('monto_pagado_usd');
        $totalIngresosBs = (float) (clone $queryOrdenesPeriodo)->where('estado', 'Pagada')->sum('monto_pagado_bs');
        $totalFacturasFiscales = (clone $queryOrdenesPeriodo)->where('tipo_comprobante', 'factura')->count();
        $totalComprobantes = (clone $queryOrdenesPeriodo)->where('tipo_comprobante', 'comprobante')->count();

        // Desglose por método de pago
        $desgloseMetodos = (clone $queryOrdenesPeriodo)
            ->where('estado', 'Pagada')
            ->select('metodo_pago', DB::raw('SUM(monto_pagado_usd) as total_usd'), DB::raw('SUM(monto_pagado_bs) as total_bs'), DB::raw('COUNT(*) as total_transacciones'))
            ->groupBy('metodo_pago')
            ->orderByDesc('total_usd')
            ->get();

        // Transacciones del período
        $ordenes = (clone $queryOrdenesPeriodo)
            ->with(['usuario', 'membresia'])
            ->latest('fecha_pago')
            ->get();

        // -------------------------------------------------------------
        // 2. REPORTE DE MEMBRESÍAS Y MOROSIDAD (RFC-01)
        // -------------------------------------------------------------
        $clientes = User::where('rol', 'cliente')->with(['membresias', 'entrenadores'])->get();
        
        $totalClientes = $clientes->count();
        $clientesMorosos = $clientes->filter(fn($c) => $c->isMoroso());
        $clientesActivos = $clientes->filter(fn($c) => !$c->isMoroso());

        // Clientes por vencer en los próximos 7 días
        $en7Dias = Carbon::now()->addDays(7)->endOfDay();
        $clientesPorVencer = $clientes->filter(function ($cliente) use ($hoy, $en7Dias) {
            $ultima = $cliente->membresias()->where('estado_pago', 'activo')->orderByDesc('fecha_fin')->first();
            if (!$ultima) return false;
            $fin = Carbon::parse($ultima->fecha_fin);
            return $fin->greaterThanOrEqualTo($hoy) && $fin->lessThanOrEqualTo($en7Dias);
        });

        // -------------------------------------------------------------
        // 3. REPORTE DE CONTROL DE ACCESO Y ASISTENCIA (RFC-04 & TRX-03)
        // -------------------------------------------------------------
        $accesosQuery = Attendance::whereBetween('fecha', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->whereIn('tipo_registro', ['acceso_cliente', 'acceso_gimnasio']);
        $totalAccesos = (clone $accesosQuery)->count();
        $accesosPermitidos = (clone $accesosQuery)->where('resultado_acceso', 'autorizado')->count();
        $accesosDenegadosMorosidad = (clone $accesosQuery)->where('resultado_acceso', 'denegado')->count();

        // Asistencia de entrenadores (RFC-04)
        $asistenciasPersonal = Attendance::with('user')
            ->whereBetween('fecha', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->where('tipo_registro', 'jornada_entrenador')
            ->latest('fecha')
            ->get();

        $asistenciasPuntuales = $asistenciasPersonal->where('estatus', 'Puntual')->count();
        $asistenciasRetardos = $asistenciasPersonal->where('estatus', 'Retardo')->count();

        // -------------------------------------------------------------
        // 4. REPORTE DE ENTRENADORES Y ATLETAS (RFC-03)
        // -------------------------------------------------------------
        $entrenadores = User::where('rol', 'entrenador')
            ->withCount(['atletas as atletas_count'])
            ->with(['atletas.membresias'])
            ->get();

        return view('admin.reports.index', compact(
            'periodo',
            'tab',
            'tasaBcv',
            'fechaInicio',
            'fechaFin',
            'totalIngresosUsd',
            'totalIngresosBs',
            'totalFacturasFiscales',
            'totalComprobantes',
            'desgloseMetodos',
            'ordenes',
            'totalClientes',
            'clientesActivos',
            'clientesMorosos',
            'clientesPorVencer',
            'totalAccesos',
            'accesosPermitidos',
            'accesosDenegadosMorosidad',
            'asistenciasPersonal',
            'asistenciasPuntuales',
            'asistenciasRetardos',
            'entrenadores'
        ));
    }
}
