@extends('layouts.admin')

@section('title', 'Pagos y Facturación')
@section('page-title', 'Módulo de Pagos y Facturación Multimoneda')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Encabezado con Botón de Acción Principal y Tasa BCV -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Gestión de Cobros y Órdenes</h1>
            <p class="text-xs text-slate-500 mt-1">Facturación a la tasa oficial del día del BCV, control de pagos parciales y extensiones de vigencia.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Tasa Oficial BCV: Bs. {{ number_format($tasaBcv, 2) }} / USD
            </span>

            <a href="{{ route('admin.payment-methods.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold transition-all shadow-2xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span>Métodos de Pago</span>
            </a>

            <button type="button" 
                    onclick="document.getElementById('modalNuevoCobro').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all shadow-sm">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Registrar Cobro de Membresía</span>
            </button>
        </div>
    </div>

    <!-- Rejilla de Métricas Financieras -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Tarjeta 1: Total Recaudado USD -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Total Recaudado</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">$</div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">${{ number_format($totalRecaudadoUsd, 2) }}</h3>
                <p class="text-xs font-semibold text-emerald-700 mt-1">Bs. {{ number_format($totalRecaudadoBs, 2) }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Ingresos brutos percibidos</p>
            </div>
        </div>

        <!-- Tarjeta 2: Tasa BCV Vigente -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Tasa de Cambio</span>
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ number_format($tasaBcv, 2) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Bolívares por Dólar (BCV)</p>
                <p class="text-[11px] text-slate-400 mt-1">Cálculo legal obligatorio</p>
            </div>
        </div>

        <!-- Tarjeta 3: Órdenes Pagadas -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Órdenes Pagadas</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $ordenesPagadas }}</h3>
                <p class="text-xs text-slate-500 mt-1">Cobros completados</p>
                <p class="text-[11px] text-slate-400 mt-1">Membresías activas extendidas</p>
            </div>
        </div>

        <!-- Tarjeta 4: Pagos Parciales -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Pagos Parciales</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $ordenesParciales }}</h3>
                <p class="text-xs text-slate-500 mt-1">Con saldo pendiente</p>
                <p class="text-[11px] text-slate-400 mt-1">Requieren finiquito</p>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="bg-white rounded-xl border border-slate-200/90 p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="w-full flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Buscar por cliente, email o cédula..." 
                       class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="w-full sm:w-44">
                <select name="estado" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="">Todos los Estados</option>
                    <option value="Pagada" {{ request('estado') === 'Pagada' ? 'selected' : '' }}>Pagadas</option>
                    <option value="Parcial" {{ request('estado') === 'Parcial' ? 'selected' : '' }}>Parciales</option>
                    <option value="Pendiente" {{ request('estado') === 'Pendiente' ? 'selected' : '' }}>Pendientes</option>
                </select>
            </div>

            <div class="w-full sm:w-40">
                <select name="tipo_comprobante" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="">Tipo Documento</option>
                    <option value="factura" {{ request('tipo_comprobante') === 'factura' ? 'selected' : '' }}>Factura Fiscal</option>
                    <option value="comprobante" {{ request('tipo_comprobante') === 'comprobante' ? 'selected' : '' }}>Comprobante</option>
                </select>
            </div>

            <div class="w-full sm:w-44">
                <select name="metodo" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="">Todos los Métodos</option>
                    @if(isset($metodosPago) && $metodosPago->count() > 0)
                        @foreach($metodosPago as $pm)
                            <option value="{{ $pm->nombre }}" {{ request('metodo') === $pm->nombre ? 'selected' : '' }}>
                                {{ $pm->nombre }}
                            </option>
                        @endforeach
                    @else
                        <option value="USDT" {{ request('metodo') === 'USDT' ? 'selected' : '' }}>USDT (Cripto)</option>
                        <option value="Pago Móvil" {{ request('metodo') === 'Pago Móvil' ? 'selected' : '' }}>Pago Móvil</option>
                        <option value="Efectivo USD" {{ request('metodo') === 'Efectivo USD' ? 'selected' : '' }}>Efectivo USD</option>
                        <option value="Efectivo Bs" {{ request('metodo') === 'Efectivo Bs' ? 'selected' : '' }}>Efectivo Bs</option>
                        <option value="Zelle" {{ request('metodo') === 'Zelle' ? 'selected' : '' }}>Zelle</option>
                        <option value="Transferencia" {{ request('metodo') === 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                        <option value="Punto de Venta" {{ request('metodo') === 'Punto de Venta' ? 'selected' : '' }}>Punto de Venta</option>
                    @endif
                </select>
            </div>

            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                Filtrar
            </button>

            @if(request()->hasAny(['search', 'estado', 'metodo', 'tipo_comprobante']))
                <a href="{{ route('admin.payments.index') }}" class="text-xs text-slate-400 hover:text-slate-600 underline">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Tabla de Órdenes y Facturas -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Historial de Transacciones Financieras</h2>
            <span class="text-xs text-slate-400">{{ $orders->total() }} registros encontrados</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-4">Documento</th>
                        <th class="py-3 px-4">Cliente</th>
                        <th class="py-3 px-4">Membresía</th>
                        <th class="py-3 px-4">Monto USD / Bs</th>
                        <th class="py-3 px-4">Pagado</th>
                        <th class="py-3 px-4">Método / Ref</th>
                        <th class="py-3 px-4">Estado</th>
                        <th class="py-3 px-4">Fecha</th>
                        <th class="py-3 px-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                                <div>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold {{ $order->tipo_comprobante === 'factura' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ $order->documento_codigo }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $order->usuario->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $order->usuario->cedula ?? $order->usuario->email }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800">{{ $order->membresia->nombre ?? 'Plan General' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">${{ number_format($order->monto_usd, 2) }}</div>
                                <div class="text-[11px] text-slate-500">Bs. {{ number_format($order->monto_bs, 2) }} <span class="text-slate-400 font-mono">({{ $order->tasa_bcv }})</span></div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-emerald-700">${{ number_format($order->monto_pagado_usd, 2) }}</div>
                                <div class="text-[11px] text-emerald-600">Bs. {{ number_format($order->monto_pagado_bs, 2) }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if(str_contains(strtoupper($order->metodo_pago), 'USDT'))
                                    <span class="inline-flex items-center gap-1 font-bold text-teal-800 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                        USDT (Cripto)
                                    </span>
                                @else
                                    <span class="font-medium text-slate-800">{{ $order->metodo_pago }}</span>
                                @endif
                                @if($order->referencia_pago)
                                    <div class="text-[10px] text-slate-400 font-mono truncate max-w-[140px]" title="{{ $order->referencia_pago }}">Ref: {{ $order->referencia_pago }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($order->estado === 'Pagada')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Pagada
                                    </span>
                                @elseif($order->estado === 'Parcial')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Parcial
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($order->fecha_pago)->translatedFormat('d M, Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.payments.invoice', $order) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-700 font-bold text-[11px] transition-all shadow-2xs" title="Ver / Imprimir {{ $order->tipo_comprobante === 'factura' ? 'Factura Fiscal' : 'Comprobante de Pago' }}">
                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    <span>{{ $order->tipo_comprobante === 'factura' ? 'Factura' : 'Recibo' }}</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                No se registran órdenes de cobro todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>

<!-- Modal: Registrar Cobro de Membresía (TRX-01 / RFC-02) -->
<div id="modalNuevoCobro" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold">Generación y Pago de Orden de Membresía</h3>
                <p class="text-[11px] text-slate-300">Conversión multimoneda a tasa oficial BCV y prórroga de vigencia</p>
            </div>
            <button type="button" onclick="document.getElementById('modalNuevoCobro').classList.add('hidden')" class="text-slate-400 hover:text-white text-lg font-bold">
                ✕
            </button>
        </div>

        <form action="{{ route('admin.payments.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Cliente -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Cliente / Atleta *</label>
                <select name="usuario_id" id="selectCliente" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="">-- Seleccionar cliente --</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">
                            {{ $cliente->name }} {{ $cliente->cedula ? "({$cliente->cedula})" : "- {$cliente->email}" }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Membresía -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Plan de Membresía *</label>
                <select name="membresia_id" id="selectMembresia" required onchange="actualizarCalculo()" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="">-- Seleccionar membresía --</option>
                    @foreach($membresias as $membresia)
                        <option value="{{ $membresia->id }}" data-costo="{{ $membresia->costo_base }}" data-dias="{{ $membresia->duracion_dias }}">
                            {{ $membresia->nombre }} - ${{ number_format($membresia->costo_base, 2) }} ({{ $membresia->duracion_dias }} días)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Resumen de Conversión Multimoneda (RFC-02) -->
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">Tasa Oficial BCV:</span>
                    <span class="font-bold text-slate-800" id="badgeTasaBcv">Bs. {{ number_format($tasaBcv, 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">Costo Base:</span>
                    <span class="font-bold text-slate-900" id="previewCostoUsd">$0.00</span>
                </div>
                <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-200 font-bold">
                    <span class="text-emerald-800">Equivalente en Bolívares (VES):</span>
                    <span class="text-emerald-700 font-extrabold text-sm" id="previewCostoBs">Bs. 0.00</span>
                </div>
            </div>

            <!-- Monto Pagado USD -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Monto Pagado (USD) *</label>
                    <input type="number" step="0.01" min="0" name="monto_pagado_usd" id="inputMontoPagado" required 
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none font-bold text-slate-900" 
                           placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Descuento (USD)</label>
                    <input type="number" step="0.01" min="0" name="descuento_usd" id="inputDescuento" value="0.00" oninput="actualizarCalculo()"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none" 
                           placeholder="0.00">
                </div>
            </div>

            <!-- Método de Pago y Referencia -->
            <div class="space-y-2">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700">Método de Pago *</label>
                            <a href="{{ route('admin.payment-methods.index') }}" target="_blank" class="text-[10px] text-slate-500 hover:text-slate-900 font-semibold underline">Gestionar</a>
                        </div>
                        <select name="metodo_pago" id="selectMetodoPago" onchange="actualizarMetodoInfo()" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none font-semibold text-slate-800">
                            @if(isset($metodosPago) && $metodosPago->count() > 0)
                                @foreach($metodosPago as $pm)
                                    <option value="{{ $pm->nombre }}" 
                                            data-requiere="{{ $pm->requiere_referencia ? '1' : '0' }}"
                                            data-instrucciones="{{ $pm->instrucciones }}">
                                        {{ $pm->nombre }} ({{ $pm->moneda }})
                                    </option>
                                @endforeach
                            @else
                                <option value="USDT (Binance / Cripto)">USDT (Binance / Cripto)</option>
                                <option value="Pago Móvil">Pago Móvil</option>
                                <option value="Transferencia Bancaria">Transferencia Bancaria</option>
                                <option value="Efectivo USD">Efectivo USD</option>
                                <option value="Efectivo Bs">Efectivo Bs</option>
                                <option value="Zelle">Zelle</option>
                                <option value="Punto de Venta / Tarjeta">Punto de Venta / Tarjeta</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1" id="labelReferencia">Nº Referencia / Hash Tx</label>
                        <input type="text" name="referencia_pago" id="inputReferenciaPago" placeholder="Ej: Hash / TxID / 849302" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    </div>
                </div>

                <!-- Caja de datos bancarios / wallet del método seleccionado -->
                <div id="cajaDatosMetodo" class="hidden p-2.5 rounded-lg bg-slate-50 border border-slate-200 text-[11px] text-slate-600">
                    <span class="font-bold text-slate-700 block">Datos de la Cuenta / Wallet del Gimnasio:</span>
                    <span id="textoDatosMetodo" class="font-mono text-[10px] text-slate-600"></span>
                </div>
            </div>

            <!-- Tipo de Documento Solicitado por el Cliente -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Documento Solicitado por el Cliente *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50">
                        <input type="radio" name="tipo_comprobante" value="comprobante" checked class="text-slate-900 focus:ring-slate-900">
                        <div>
                            <div class="text-xs font-bold text-slate-800">Comprobante de Pago</div>
                            <div class="text-[10px] text-slate-400">Recibo estándar / control interno</div>
                        </div>
                    </label>
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/40">
                        <input type="radio" name="tipo_comprobante" value="factura" class="text-indigo-600 focus:ring-indigo-600">
                        <div>
                            <div class="text-xs font-bold text-slate-800">Factura Fiscal</div>
                            <div class="text-[10px] text-slate-400">Numeración fiscal y membrete oficial</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Notas -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Observaciones / Notas</label>
                <input type="text" name="notas" placeholder="Ej: Pago adelantado de 2 meses..." class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
            </div>

            <!-- Botones de Acción -->
            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalNuevoCobro').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Procesar Pago y Extender Membresía
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const tasaBcv = {{ $tasaBcv }};

    function actualizarCalculo() {
        const select = document.getElementById('selectMembresia');
        const selectedOption = select.options[select.selectedIndex];
        const costoBase = parseFloat(selectedOption.getAttribute('data-costo')) || 0;
        const descuento = parseFloat(document.getElementById('inputDescuento').value) || 0;

        const costoFinal = Math.max(0, costoBase - descuento);
        const costoBs = (costoFinal * tasaBcv).toFixed(2);

        document.getElementById('previewCostoUsd').textContent = `$${costoFinal.toFixed(2)}`;
        document.getElementById('previewCostoBs').textContent = `Bs. ${Number(costoBs).toLocaleString('es-VE', {minimumFractionDigits: 2})}`;

        // Auto rellenar el monto pagado si está vacío
        const inputPago = document.getElementById('inputMontoPagado');
        if (!inputPago.value || parseFloat(inputPago.value) === 0) {
            inputPago.value = costoFinal.toFixed(2);
        }
    }

    function actualizarMetodoInfo() {
        const select = document.getElementById('selectMetodoPago');
        if (!select) return;
        const opt = select.options[select.selectedIndex];
        if (!opt) return;

        const requiere = opt.getAttribute('data-requiere') === '1';
        const instrucciones = opt.getAttribute('data-instrucciones');

        const labelRef = document.getElementById('labelReferencia');
        const inputRef = document.getElementById('inputReferenciaPago');
        if (labelRef && inputRef) {
            if (requiere) {
                labelRef.textContent = 'Nº Referencia / Hash Tx * (Obligatorio)';
                inputRef.placeholder = 'Indique número de comprobante o Hash';
            } else {
                labelRef.textContent = 'Nº Referencia (Opcional en efectivo)';
                inputRef.placeholder = 'Opcional para cobro en caja';
            }
        }

        const caja = document.getElementById('cajaDatosMetodo');
        const texto = document.getElementById('textoDatosMetodo');
        if (caja && texto) {
            if (instrucciones && instrucciones.trim().length > 0) {
                texto.textContent = instrucciones;
                caja.classList.remove('hidden');
            } else {
                caja.classList.add('hidden');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        actualizarMetodoInfo();
    });
</script>
@endsection
