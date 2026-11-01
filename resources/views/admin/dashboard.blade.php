@extends('layouts.admin')

@section('title', 'Dashboard General')
@section('page-title', 'Métricas Principales')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Encabezado de Sección con Estilo Corporativo Minimalista -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Métricas Globales de Operación</h1>
            <p class="text-xs text-slate-500 mt-1">Supervisión estratégica de ingresos en divisas y bolívares (BCV), usuarios activos, morosos y asistencia.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Tasa Oficial BCV: Bs. {{ number_format($tasaBcv, 2) }} / USD
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-white border border-slate-200 text-slate-600 shadow-sm">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Actualizado: {{ now()->translatedFormat('d M, Y - H:i') }}
            </span>
        </div>
    </div>

    <!-- Rejilla de las 5 Métricas Globales Exigidas en el Análisis Técnico -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-stretch">
        
        <!-- Métrica 1: Ingresos Globales (USD + Equivalente BCV en Bs.) -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Ingresos Globales</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">${{ number_format($totalIngresosUsd, 2) }}</h3>
                <div class="mt-1 text-xs font-semibold text-emerald-700 bg-emerald-50/70 rounded-md px-2 py-0.5 inline-block">
                    Bs. {{ number_format($totalIngresosBs, 2) }}
                </div>
                <div class="mt-2 text-[11px] text-slate-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Membresías activas
                </div>
            </div>
        </div>

        <!-- Métrica 2: Tasa de Conversión de Pagos / Divisas -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Tasa Divisa BCV</span>
                <div class="w-8 h-8 rounded-lg bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1">
                    <h3 class="text-2xl font-extrabold tracking-tight text-teal-800">{{ number_format($tasaBcv, 2) }}</h3>
                    <span class="text-xs font-medium text-slate-400">Bs/USD</span>
                </div>
                <div class="mt-1 text-xs font-medium text-slate-600">
                    Tasa Oficial BCV
                </div>
                <div class="mt-2 text-[11px] text-slate-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                    Referencia cambiaria legal
                </div>
            </div>
        </div>

        <!-- Métrica 3: Asistencia General -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Asistencia Total</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ number_format($conteoAsistenciaGeneral) }}</h3>
                <div class="mt-1 text-xs font-medium text-slate-600">
                    Entradas registradas
                </div>
                <div class="mt-2 text-[11px] text-slate-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                    Control de torniquete
                </div>
            </div>
        </div>

        <!-- Métrica 4: Usuarios Activos -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Usuarios Activos</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-indigo-600">{{ number_format($usuariosActivos) }}</h3>
                <div class="mt-1 text-xs font-medium text-slate-600">
                    {{ $conteoMembresiasActivas }} membresías activas
                </div>
                <div class="mt-2 text-[11px] text-slate-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    Suscripciones al día
                </div>
            </div>
        </div>

        <!-- Métrica 5: Usuarios Morosos -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Usuarios Morosos</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-rose-600">{{ number_format($usuariosMorosos) }}</h3>
                <div class="mt-1 text-xs font-medium text-rose-600">
                    {{ $conteoMembresiasMorosas }} cuotas pendientes
                </div>
                <div class="mt-2 text-[11px] text-slate-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Membresía morosa
                </div>
            </div>
        </div>

    </div>

    <!-- Sección Complementaria: Desglose y Acceso a Gestión -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Tarjeta Rendimiento de Conversión Detallada -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h4 class="text-sm font-bold text-slate-900 tracking-tight">Conversión de Clientes</h4>
                <p class="text-xs text-slate-500 mt-1">Porcentaje de usuarios registrados que han adquirido un plan.</p>

                <div class="mt-6 flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ $tasaConversion }}%</span>
                    <span class="text-xs text-slate-400">conversión</span>
                </div>

                <div class="w-full bg-slate-100 rounded-full h-2 mt-4 overflow-hidden">
                    <div class="bg-slate-900 h-2 rounded-full transition-all duration-500" style="width: {{ min($tasaConversion, 100) }}%"></div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between text-xs text-slate-500">
                <span>{{ $usuariosConMembresia }} con membresía</span>
                <span class="font-medium text-slate-700">{{ $totalUsuarios }} registrados</span>
            </div>
        </div>

        <!-- Tarjeta Estatus Global de Membresías -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h4 class="text-sm font-bold text-slate-900 tracking-tight">Estado de Membresías</h4>
                <p class="text-xs text-slate-500 mt-1">Distribución del estado de pagos en la base de datos.</p>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-slate-600">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Activas
                        </span>
                        <span class="font-semibold text-slate-900">{{ $conteoMembresiasActivas }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-slate-600">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Morosas
                        </span>
                        <span class="font-semibold text-slate-900">{{ $conteoMembresiasMorosas }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-slate-600">
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span> Inactivas
                        </span>
                        <span class="font-semibold text-slate-900">{{ $conteoMembresiasInactivas }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center text-xs">
                <span class="text-slate-400">Total suscripciones:</span>
                <span class="font-bold text-slate-800">{{ $conteoMembresiasActivas + $conteoMembresiasMorosas + $conteoMembresiasInactivas }}</span>
            </div>
        </div>

        <!-- Tarjeta de Acceso Centralizado a Gestión de Clientes -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">Operaciones Técnicas</span>
                </div>
                <h4 class="text-sm font-bold text-slate-900 tracking-tight">Asignación de Entrenadores</h4>
                <p class="text-xs text-slate-500 mt-1">La reasignación dinámica está centralizada exclusivamente en la sección de Clientes para garantizar integridad de datos.</p>

                <div class="mt-5 p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-600 space-y-1.5">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Historial de asignaciones preservado</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Selector dinámico en tiempo real</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" 
                   class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold tracking-wide transition-colors shadow-sm">
                    <span>Ir a Gestión de Clientes</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
