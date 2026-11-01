@extends('layouts.admin')

@section('title', 'Panel de Entrenador')
@section('page-title', 'Módulo de Entrenadores')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Encabezado con Check-in / Check-out Rápido de Jornada (RFC-04) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Panel Operacional de Entrenador</h1>
            <p class="text-xs text-slate-500 mt-1">Supervisión de atletas asignados, sesiones del día, planes de rutina y alertas de vencimiento (Módulo C / RFC-04).</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('trainer.toggle-shift') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm
                        {{ $asistenciaHoy && !$asistenciaHoy->salida ? 'bg-amber-500 hover:bg-amber-600 text-slate-950' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>
                        @if($asistenciaHoy && !$asistenciaHoy->salida)
                            Marcar Salida de Turno (Check-out)
                        @else
                            Registrar Entrada de Turno (Check-in)
                        @endif
                    </span>
                </button>
            </form>
        </div>
    </div>

    <!-- Rejilla de Resumen Operacional -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Tarjeta 1: Atletas Asignados -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Atletas Asignados</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $atletas->count() }}</h3>
                <p class="text-xs text-slate-500 mt-1">Clientes a tu cargo</p>
                <p class="text-[11px] text-slate-400 mt-1">Asignados por la administración</p>
            </div>
        </div>

        <!-- Tarjeta 2: Alertas de Vencimiento de Membresía -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Alertas de Vencimiento</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-amber-600">{{ $alertasVencimiento->count() }}</h3>
                <p class="text-xs text-slate-500 mt-1">Vencen en &le; 5 días o morosos</p>
                <p class="text-[11px] text-slate-400 mt-1">Requieren aviso en recepción</p>
            </div>
        </div>

        <!-- Tarjeta 3: Rutinas Activas Asignadas -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Rutinas Creadas</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $rutinas->count() }}</h3>
                <p class="text-xs text-slate-500 mt-1">Planes de entrenamiento</p>
                <p class="text-[11px] text-slate-400 mt-1">Asociadas a tus atletas</p>
            </div>
        </div>

        <!-- Tarjeta 4: Estado de Jornada Hoy -->
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Mi Turno Hoy</span>
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-sm font-extrabold text-slate-900">
                    @if($asistenciaHoy)
                        {{ $asistenciaHoy->estatus }}
                    @else
                        Sin Marcaje Hoy
                    @endif
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Entrada: {{ $asistenciaHoy->entrada ?? '--:--' }} | Salida: {{ $asistenciaHoy->salida ?? '--:--' }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">Cumplimiento de horario RFC-04</p>
            </div>
        </div>
    </div>

    <!-- Listado de Atletas Asignados a su Cargo -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Mis Atletas Asignados</h2>
            <span class="text-xs text-slate-400">{{ $atletas->count() }} atletas activos</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-4">Atleta</th>
                        <th class="py-3 px-4">Contacto / Cédula</th>
                        <th class="py-3 px-4">Membresía</th>
                        <th class="py-3 px-4">Estado Pago</th>
                        <th class="py-3 px-4">Rutinas</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($atletas as $atleta)
                        @php
                            $membresia = $atleta->membresiaActiva();
                            $esMoroso = $atleta->isMoroso();
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $atleta->name }}</div>
                                <div class="text-[11px] text-slate-400">Registrado: {{ $atleta->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-slate-800">{{ $atleta->email }}</div>
                                <div class="text-[11px] text-slate-400">{{ $atleta->cedula ?? 'Sin Cédula' }} | {{ $atleta->telefono ?? 'Sin Teléfono' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($membresia)
                                    <div class="font-semibold text-slate-800">{{ $membresia->membresia->nombre }}</div>
                                    <div class="text-[11px] text-slate-400">Vence: {{ $membresia->fecha_fin }}</div>
                                @else
                                    <span class="text-slate-400">Sin membresía</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($esMoroso)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                        Moroso / Vencido
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Al Día
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800">{{ $atleta->rutinas->count() }} rutina(s)</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('trainer.athletes.show', $atleta) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                                    <span>Ver Perfil y Rutinas</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                No tienes atletas asignados actualmente. El administrador puede asignarte atletas desde el módulo de usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
