@extends('layouts.admin')

@section('title', 'Reportes y Estadísticas')
@section('page-title', 'Centro de Reportes Gerenciales y Operativos')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Encabezado y Filtros de Período -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-2 border-b border-slate-200/60 no-print">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Centro de Reportes y Auditoría</h1>
            <p class="text-xs text-slate-500 mt-1">
                Informes financieros multimoneda (BCV), morosidad (RFC-01), control de asistencia (RFC-04) y carga técnica deportiva.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Selector de períodos rápidos -->
            <div class="inline-flex rounded-xl p-1 bg-slate-100 border border-slate-200 text-xs font-semibold">
                <a href="{{ route('admin.reports.index', ['periodo' => 'hoy', 'tab' => $tab]) }}" 
                   class="px-3 py-1.5 rounded-lg transition-colors {{ $periodo === 'hoy' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Hoy
                </a>
                <a href="{{ route('admin.reports.index', ['periodo' => 'semana', 'tab' => $tab]) }}" 
                   class="px-3 py-1.5 rounded-lg transition-colors {{ $periodo === 'semana' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Esta Semana
                </a>
                <a href="{{ route('admin.reports.index', ['periodo' => 'este_mes', 'tab' => $tab]) }}" 
                   class="px-3 py-1.5 rounded-lg transition-colors {{ $periodo === 'este_mes' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Este Mes
                </a>
                <a href="{{ route('admin.reports.index', ['periodo' => 'ano', 'tab' => $tab]) }}" 
                   class="px-3 py-1.5 rounded-lg transition-colors {{ $periodo === 'ano' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Año
                </a>
            </div>

            <!-- Botón de Imprimir Reporte -->
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Imprimir / PDF</span>
            </button>
        </div>
    </div>

    <!-- Banner imprimible de membrete del Gimnasio -->
    <div class="hidden print:block pb-4 mb-4 border-b border-slate-300">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-black text-slate-900">GYM RD SPORT, C.A.</h2>
                <p class="text-xs text-slate-600">RIF: J-50123894-0 | Reporte General de Gestión</p>
                <p class="text-xs text-slate-500">Período: {{ $fechaInicio->format('d/m/Y') }} al {{ $fechaFin->format('d/m/Y') }}</p>
            </div>
            <div class="text-right text-xs text-slate-600">
                <p>Tasa Oficial BCV: Bs. {{ number_format($tasaBcv, 2) }} / USD</p>
                <p>Fecha de emisión: {{ now()->translatedFormat('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Indicadores Principales (KPI Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Tarjeta 1: Recaudación Período -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Recaudación Período</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">$</span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900">${{ number_format($totalIngresosUsd, 2) }}</div>
                <div class="text-xs font-bold text-emerald-700 mt-0.5">Bs. {{ number_format($totalIngresosBs, 2) }}</div>
                <div class="text-[10px] text-slate-400 mt-1">A tasa BCV oficial (Bs. {{ number_format($tasaBcv, 2) }})</div>
            </div>
        </div>

        <!-- Tarjeta 2: Documentos Emitidos -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Documentos Emitidos</span>
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900">{{ $totalFacturasFiscales + $totalComprobantes }}</div>
                <div class="flex items-center gap-2 mt-0.5 text-xs">
                    <span class="font-bold text-indigo-700">{{ $totalFacturasFiscales }} Facturas</span>
                    <span class="text-slate-300">•</span>
                    <span class="font-bold text-slate-600">{{ $totalComprobantes }} Comprobantes</span>
                </div>
                <div class="text-[10px] text-slate-400 mt-1">Conforme a preferencia del cliente</div>
            </div>
        </div>

        <!-- Tarjeta 3: Morosidad (RFC-01) -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Morosidad (RFC-01)</span>
                <span class="w-7 h-7 rounded-lg bg-red-50 text-red-600 flex items-center justify-center font-bold text-xs">!</span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-red-600">{{ $clientesMorosos->count() }} <span class="text-xs font-normal text-slate-400">/ {{ $totalClientes }}</span></div>
                <div class="text-xs font-bold text-amber-700 mt-0.5">{{ $clientesPorVencer->count() }} por vencer (7 días)</div>
                <div class="text-[10px] text-slate-400 mt-1">{{ $clientesActivos->count() }} atletas solventes activos</div>
            </div>
        </div>

        <!-- Tarjeta 4: Asistencias y Accesos -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Flujo en Kiosco</span>
                <span class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900">{{ $totalAccesos }}</div>
                <div class="flex items-center gap-2 mt-0.5 text-xs">
                    <span class="font-bold text-emerald-700">{{ $accesosPermitidos }} aprobados</span>
                    <span class="text-slate-300">•</span>
                    <span class="font-bold text-red-600">{{ $accesosDenegadosMorosidad }} rebotados</span>
                </div>
                <div class="text-[10px] text-slate-400 mt-1">Control de paso por torniquete</div>
            </div>
        </div>
    </div>

    <!-- Navegación de Pestañas de Reportes -->
    <div class="border-b border-slate-200 no-print">
        <nav class="flex space-x-6">
            <a href="{{ route('admin.reports.index', ['periodo' => $periodo, 'tab' => 'financiero']) }}" 
               class="pb-3 text-xs font-bold border-b-2 transition-colors {{ $tab === 'financiero' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                1. Financiero y Facturación (BCV / USDT)
            </a>
            <a href="{{ route('admin.reports.index', ['periodo' => $periodo, 'tab' => 'morosidad']) }}" 
               class="pb-3 text-xs font-bold border-b-2 transition-colors {{ $tab === 'morosidad' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                2. Membresías y Morosidad (RFC-01)
            </a>
            <a href="{{ route('admin.reports.index', ['periodo' => $periodo, 'tab' => 'asistencia']) }}" 
               class="pb-3 text-xs font-bold border-b-2 transition-colors {{ $tab === 'asistencia' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                3. Control de Acceso y Personal (RFC-04)
            </a>
            <a href="{{ route('admin.reports.index', ['periodo' => $periodo, 'tab' => 'entrenadores']) }}" 
               class="pb-3 text-xs font-bold border-b-2 transition-colors {{ $tab === 'entrenadores' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                4. Carga Deportiva y Entrenadores (RFC-03)
            </a>
        </nav>
    </div>

    <!-- CONTENIDO DE PESTAÑAS -->

    <!-- TAB 1: REPORTE FINANCIERO Y FACTURACIÓN -->
    @if($tab === 'financiero')
    <div class="space-y-6">

        <!-- Métodos de Pago y Desglose de Documentos -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Desglose por Método de Pago -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center justify-between">
                    <span>Recaudación por Método de Pago</span>
                    <span class="text-xs font-normal text-slate-400">Total transacciones: {{ $ordenes->count() }}</span>
                </h3>
                
                <div class="divide-y divide-slate-100">
                    @forelse($desgloseMetodos as $metodo)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if(str_contains(strtoupper($metodo->metodo_pago), 'USDT'))
                                    <span class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs">₮</span>
                                @elseif(str_contains(strtoupper($metodo->metodo_pago), 'MÓVIL') || str_contains(strtoupper($metodo->metodo_pago), 'MOVIL'))
                                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">PM</span>
                                @elseif(str_contains(strtoupper($metodo->metodo_pago), 'EFECTIVO'))
                                    <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">EF</span>
                                @else
                                    <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs">TX</span>
                                @endif
                                <div>
                                    <p class="font-bold text-slate-900 text-xs">{{ $metodo->metodo_pago }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $metodo->total_transacciones }} operación(es)</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-slate-900 text-xs font-mono">${{ number_format($metodo->total_usd, 2) }}</p>
                                <p class="text-[10px] text-emerald-700 font-semibold font-mono">Bs. {{ number_format($metodo->total_bs, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">No hay cobros registrados en este período.</p>
                    @endforelse
                </div>
            </div>

            <!-- Proporción Factura vs Comprobante -->
            <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 mb-2">Comprobantes vs Facturas</h3>
                    <p class="text-xs text-slate-400 mb-4">Emisión según requerimiento fiscal del cliente.</p>

                    <div class="space-y-4">
                        <div class="p-3.5 rounded-xl bg-indigo-50/60 border border-indigo-100">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-indigo-900">Facturas Fiscales</span>
                                <span class="font-extrabold text-indigo-700">{{ $totalFacturasFiscales }}</span>
                            </div>
                            <p class="text-[10px] text-indigo-600 mt-1">Con RIF y formato legal para contribuyentes</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-slate-800">Comprobantes de Pago</span>
                                <span class="font-extrabold text-slate-900">{{ $totalComprobantes }}</span>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1">Recibos de cobro y caja de control interno</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 text-[11px] text-slate-400">
                    Todas las transacciones en Bolívares se fijan a la tasa BCV del día para asegurar la validez legal.
                </div>
            </div>
        </div>

        <!-- Tabla Detallada de Transacciones del Período -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Detalle de Cobros y Órdenes del Período</h3>
                <span class="text-xs text-slate-400 font-mono">{{ $ordenes->count() }} registros</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold uppercase text-slate-500">
                            <th class="py-3 px-4">Documento / Tipo</th>
                            <th class="py-3 px-4">Cliente / Cédula</th>
                            <th class="py-3 px-4">Membresía</th>
                            <th class="py-3 px-4">Total USD</th>
                            <th class="py-3 px-4">Total Bs (BCV)</th>
                            <th class="py-3 px-4">Método</th>
                            <th class="py-3 px-4">Fecha</th>
                            <th class="py-3 px-4 text-right no-print">Imprimir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($ordenes as $order)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                    <div>{{ $order->documento_codigo }}</div>
                                    <span class="text-[10px] font-sans font-semibold {{ $order->tipo_comprobante === 'factura' ? 'text-indigo-600' : 'text-slate-500' }}">
                                        {{ $order->tipo_comprobante === 'factura' ? 'Factura Fiscal' : 'Comprobante' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900">{{ $order->usuario->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $order->usuario->cedula ?? $order->usuario->email }}</div>
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-800">
                                    {{ $order->membresia->nombre ?? 'Plan General' }}
                                </td>
                                <td class="py-3 px-4 font-bold font-mono text-emerald-700">
                                    ${{ number_format($order->monto_pagado_usd, 2) }}
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-700">
                                    Bs. {{ number_format($order->monto_pagado_bs, 2) }}
                                </td>
                                <td class="py-3 px-4">
                                    @if(str_contains(strtoupper($order->metodo_pago), 'USDT'))
                                        <span class="inline-flex items-center gap-1 font-bold text-teal-800 bg-teal-50 border border-teal-200 px-1.5 py-0.5 rounded text-[10px]">
                                            USDT
                                        </span>
                                    @else
                                        <span class="font-medium text-slate-700">{{ $order->metodo_pago }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($order->fecha_pago)->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4 text-right no-print">
                                    <a href="{{ route('admin.payments.invoice', $order) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[10px]">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400">
                                    No hay cobros registrados en el rango de fechas seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @endif

    <!-- TAB 2: REPORTE DE MEMBRESÍAS Y MOROSIDAD (RFC-01) -->
    @if($tab === 'morosidad')
    <div class="space-y-6">

        <!-- Clientes Morosos -->
        <div class="bg-white rounded-xl border border-red-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 bg-red-50/60 border-b border-red-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-red-950 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        Listado de Clientes Morosos (RFC-01)
                    </h3>
                    <p class="text-xs text-red-700 mt-0.5">Atletas con vigencia caducada cuyo acceso al gimnasio está bloqueado.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                    {{ $clientesMorosos->count() }} morosos
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase text-slate-500">
                            <th class="py-3 px-4">Cliente</th>
                            <th class="py-3 px-4">Cédula / Teléfono</th>
                            <th class="py-3 px-4">Entrenador Asignado</th>
                            <th class="py-3 px-4">Última Membresía</th>
                            <th class="py-3 px-4">Fecha Vencimiento</th>
                            <th class="py-3 px-4">Estado Acceso</th>
                            <th class="py-3 px-4 text-right no-print">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($clientesMorosos as $moroso)
                            @php
                                $ultimaMembresia = $moroso->membresias()->orderByDesc('fecha_fin')->first();
                            @endphp
                            <tr class="hover:bg-red-50/30 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900">{{ $moroso->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $moroso->email }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-700">{{ $moroso->cedula ?? 'Sin cédula' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $moroso->telefono ?? 'Sin teléfono' }}</div>
                                </td>
                                <td class="py-3 px-4 text-slate-700">
                                    {{ $moroso->entrenadores->first()->name ?? 'Sin asignar' }}
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-800">
                                    {{ $ultimaMembresia->membresia->nombre ?? 'Plan General' }}
                                </td>
                                <td class="py-3 px-4 text-red-700 font-bold whitespace-nowrap">
                                    {{ $ultimaMembresia ? \Carbon\Carbon::parse($ultimaMembresia->fecha_fin)->format('d/m/Y') : 'Sin historial' }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">
                                        Acceso Bloqueado
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right no-print">
                                    <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-900 text-white font-bold text-[10px] hover:bg-slate-800">
                                        Cobrar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    ¡Excelente! No hay clientes con estado de morosidad activo actualmente.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Clientes Por Vencer en los Próximos 7 Días -->
        <div class="bg-white rounded-xl border border-amber-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 bg-amber-50/60 border-b border-amber-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-amber-950 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Membresías por Vencer en los Próximos 7 Días
                    </h3>
                    <p class="text-xs text-amber-700 mt-0.5">Gestión proactiva de cobranza y fidelización.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    {{ $clientesPorVencer->count() }} atletas
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase text-slate-500">
                            <th class="py-3 px-4">Cliente</th>
                            <th class="py-3 px-4">Cédula</th>
                            <th class="py-3 px-4">Plan Vigente</th>
                            <th class="py-3 px-4">Vence el</th>
                            <th class="py-3 px-4">Días Restantes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($clientesPorVencer as $atleta)
                            @php
                                $membresiaActiva = $atleta->membresias()->where('estado_pago', 'activo')->orderByDesc('fecha_fin')->first();
                                $diasRestantes = $membresiaActiva ? (int) now()->diffInDays(\Carbon\Carbon::parse($membresiaActiva->fecha_fin), false) : 0;
                            @endphp
                            <tr class="hover:bg-amber-50/20 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900">{{ $atleta->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $atleta->email }}</div>
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-700">
                                    {{ $atleta->cedula ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-800">
                                    {{ $membresiaActiva->membresia->nombre ?? 'Plan General' }}
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                    {{ \Carbon\Carbon::parse($membresiaActiva->fecha_fin)->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                        {{ max(0, $diasRestantes) }} días
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    No hay atletas con membresía próxima a vencer en los próximos 7 días.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @endif

    <!-- TAB 3: CONTROL DE ACCESO Y ASISTENCIA DEL PERSONAL -->
    @if($tab === 'asistencia')
    <div class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
                <span class="text-[11px] font-bold uppercase text-slate-400">Total Validaciones Kiosco</span>
                <p class="text-2xl font-black text-slate-900 mt-2">{{ $totalAccesos }}</p>
                <p class="text-xs text-slate-400 mt-1">Registradas por código de acceso / cédula</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
                <span class="text-[11px] font-bold uppercase text-slate-400">Accesos Permitidos</span>
                <p class="text-2xl font-black text-emerald-600 mt-2">{{ $accesosPermitidos }}</p>
                <p class="text-xs text-emerald-700 mt-1">Atletas con membresía vigente</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
                <span class="text-[11px] font-bold uppercase text-slate-400">Accesos Denegados (Morosos)</span>
                <p class="text-2xl font-black text-red-600 mt-2">{{ $accesosDenegadosMorosidad }}</p>
                <p class="text-xs text-red-700 mt-1">Bloqueo automático de torniquete</p>
            </div>
        </div>

        <!-- Asistencia de Entrenadores y Personal (RFC-04) -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Cumplimiento de Turnos del Personal de Entrenadores (RFC-04)</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Control de puntualidad, retardos y horas de turno en sala.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $asistenciasPuntuales }} Puntuales
                    </span>
                    <span class="px-2 py-1 rounded text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        {{ $asistenciasRetardos }} Retardos
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase text-slate-500">
                            <th class="py-3 px-4">Entrenador / Empleado</th>
                            <th class="py-3 px-4">Fecha</th>
                            <th class="py-3 px-4">Hora Entrada</th>
                            <th class="py-3 px-4">Hora Salida</th>
                            <th class="py-3 px-4">Estado</th>
                            <th class="py-3 px-4">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($asistenciasPersonal as $asistencia)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-4 font-bold text-slate-900">
                                    {{ $asistencia->user->name ?? 'Entrenador' }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4 font-mono font-semibold text-slate-800">
                                    {{ $asistencia->entrada ? \Carbon\Carbon::parse($asistencia->entrada)->format('h:i A') : 'N/A' }}
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-600">
                                    {{ $asistencia->salida ? \Carbon\Carbon::parse($asistencia->salida)->format('h:i A') : 'En turno' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if(strtolower($asistencia->estatus) === 'puntual' || strtolower($asistencia->estatus) === 'turno cumplido')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            {{ $asistencia->estatus }}
                                        </span>
                                    @elseif(strtolower($asistencia->estatus) === 'retardo')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                            Retardo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800">
                                            {{ $asistencia->estatus ?? 'Inasistencia' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-500">
                                    {{ $asistencia->observaciones ?? 'Sin incidencias' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    No hay registros de marcaje de asistencia de personal para este período.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @endif

    <!-- TAB 4: REPORTE DE ENTRENADORES Y ATLETAS (RFC-03) -->
    @if($tab === 'entrenadores')
    <div class="space-y-6">

        <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Distribución de Carga Técnica y Atletas por Entrenador</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Control de balance y asignación de atletas.</p>
                </div>
                <span class="text-xs font-bold text-slate-700 font-mono">{{ $entrenadores->count() }} entrenadores en nómina</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($entrenadores as $coach)
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-900 text-white font-bold flex items-center justify-center text-sm">
                                    {{ strtoupper(substr($coach->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">{{ $coach->name }}</h4>
                                    <p class="text-xs text-slate-400">{{ $coach->email }} • {{ $coach->especialidad ?? 'Entrenador Integral' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-800">
                                    {{ $coach->atletas_count }} atletas asignados
                                </span>
                                @if($coach->en_turno)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        En Turno Ahora
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Lista de Atletas a su Cargo -->
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Atletas en seguimiento:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @forelse($coach->atletas as $atleta)
                                    <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-200/70 text-xs">
                                        <div>
                                            <span class="font-bold text-slate-800">{{ $atleta->name }}</span>
                                            <span class="block text-[10px] text-slate-400">{{ $atleta->cedula ?? $atleta->email }}</span>
                                        </div>
                                        @if($atleta->isMoroso())
                                            <span class="text-[10px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded">Moroso</span>
                                        @else
                                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">Activo</span>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic">No tiene atletas asignados en este momento.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        No hay entrenadores registrados en el sistema.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
    @endif

</div>
@endsection
