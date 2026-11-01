@extends('layouts.admin')

@section('title', 'Mi Panel')
@section('page-title', 'Dashboard de Usuario')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Notificaciones Pendientes (TRX-02) -->
    @if($notificaciones->where('leida', false)->count() > 0)
        <div class="space-y-2">
            @foreach($notificaciones->where('leida', false) as $notif)
                <div class="p-4 rounded-xl bg-slate-900 text-white flex items-center justify-between gap-4 shadow-sm border border-slate-800">
                    <div class="flex items-center gap-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shrink-0"></span>
                        <div>
                            <h4 class="text-xs font-bold">{{ $notif->titulo }}</h4>
                            <p class="text-xs text-slate-300">{{ $notif->mensaje }}</p>
                        </div>
                    </div>
                    <form action="{{ route('notifications.read', $notif) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-slate-400 hover:text-white underline">
                            Marcar como leída
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Acceso Exclusivo para Administrador -->
    @if(auth()->check() && auth()->user()->isAdmin())
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border border-slate-700/60">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/10">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold tracking-tight">Acceso Administrativo Habilitado</h2>
                    <p class="text-xs text-slate-300 mt-0.5">Tienes privilegios de administración global, reportes financieros y supervisión operativa.</p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white text-slate-900 text-xs font-bold hover:bg-slate-100 transition-all shadow-sm shrink-0">
                <span>Ir al Panel de Administración</span>
                <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    @endif

    <!-- Alerta de Morosidad e Inhabilitación (RFC-01) - SOLO PARA CLIENTES CON DEUDA -->
    @if($esMoroso && $user->isCliente())
        <div class="p-5 rounded-2xl bg-red-50 border-2 border-red-200 text-red-900 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-600 text-white flex items-center justify-center font-bold text-xl shrink-0">
                    ✕
                </div>
                <div>
                    <h3 class="text-sm font-bold">Atención: Membresía en Estado Moroso</h3>
                    <p class="text-xs text-red-700 mt-0.5 leading-relaxed">
                        Tu período de vigencia ha expirado. De acuerdo al reglamento interno (RFC-01), el acceso al gimnasio y los servicios de entrenamiento se encuentran suspendidos hasta registrar tu pago.
                    </p>
                </div>
            </div>
            <span class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-xl shrink-0">
                Regularizar en Recepción
            </span>
        </div>
    @endif

    <!-- Tarjeta Principal de Bienvenida -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                <div class="w-16 h-16 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-xl font-bold shadow-sm shrink-0">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="text-center sm:text-left space-y-1">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">¡Bienvenido(a), {{ $user->name }}!</h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 capitalize">
                            <span class="w-1.5 h-1.5 rounded-full {{ $esMoroso && $user->isCliente() ? 'bg-red-500' : 'bg-emerald-500' }}"></span>
                            Rol: {{ $user->rol ?? 'Cliente' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 max-w-xl">
                        Te damos la bienvenida al sistema integral de <strong class="text-slate-800">GYM RD SPORT</strong>. Aquí puedes supervisar el estatus de tu membresía, rutinas asignadas e historial financiero.
                    </p>
                </div>
            </div>

            <div class="text-right shrink-0">
                <button type="button" 
                        onclick="document.getElementById('modalPerfilFisico').classList.remove('hidden')"
                        class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Editar Perfil y Medidas</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Rejilla de Módulos Clave: Membresía, Entrenador y Estado Físico -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- 1. Tarjeta de Membresía / Acceso -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">
                    {{ $user->isAdmin() ? 'Credencial' : 'Membresía Activa' }}
                </span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold 
                    {{ $esMoroso && $user->isCliente() ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                    {{ $estadoMembresia }}
                </span>
            </div>

            @if($user->isAdmin())
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900">Personal Directivo</h3>
                    <p class="text-xs text-emerald-700 mt-1 font-semibold">Acceso Libre a Instalaciones y Torniquete</p>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 leading-relaxed">
                    Como administrador del gimnasio, cuentas con credenciales de acceso total sin requerir suscripción mensual de cliente.
                </div>
            @elseif($user->isEntrenador())
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900">Personal Técnico</h3>
                    <p class="text-xs text-teal-700 mt-1 font-semibold">Entrenador Certificado</p>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 leading-relaxed">
                    Acceso laboral vinculado a tu horario de turnos y atletas asignados.
                </div>
            @elseif($membresiaUsuario)
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900">{{ $membresiaUsuario->membresia->nombre }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Costo: ${{ number_format($membresiaUsuario->membresia->costo_base, 2) }} / mes</p>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Fecha de Inicio:</span>
                        <span class="font-semibold text-slate-800">{{ $membresiaUsuario->fecha_inicio }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Fecha de Vencimiento:</span>
                        <span class="font-semibold text-slate-800">{{ $membresiaUsuario->fecha_fin }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Días de Gracia:</span>
                        <span class="font-semibold text-slate-800">{{ $user->dias_gracia ?? 3 }} días adicionales</span>
                    </div>
                    <div class="flex justify-between pt-1 border-t border-slate-200">
                        <span class="text-slate-500">Días Restantes:</span>
                        <span class="font-extrabold {{ $diasRestantes >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                            {{ $diasRestantes >= 0 ? "{$diasRestantes} días de vigencia" : "Vencida hace " . abs($diasRestantes) . " días" }}
                        </span>
                    </div>
                </div>
            @else
                <div class="py-6 text-center text-slate-400 text-xs">
                    <p>No tienes una membresía activa registrada actualmente.</p>
                </div>
            @endif

            <div class="text-[11px] text-slate-400">
                Tasa BCV del día: <strong>Bs. {{ number_format($tasaBcv, 2) }} / USD</strong>
            </div>
        </div>

            <div class="text-[11px] text-slate-400">
                Tasa BCV del día: <strong>Bs. {{ number_format($tasaBcv, 2) }} / USD</strong>
            </div>
        </div>

        <!-- 2. Tarjeta de Entrenador Asignado (Módulo B / RFC-03) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm space-y-4">
            <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400 block">Mi Entrenador Asignado</span>

            @if($entrenadorAsignado)
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-700 text-white font-bold flex items-center justify-center text-lg shadow-sm">
                        {{ strtoupper(substr($entrenadorAsignado->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">{{ $entrenadorAsignado->name }}</h3>
                        <p class="text-xs text-slate-400">{{ $entrenadorAsignado->email }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 text-[10px] font-semibold">
                            Entrenador Certificado
                        </span>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Teléfono:</span>
                        <span class="font-semibold">{{ $entrenadorAsignado->telefono ?? 'Disponible en sala' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Estado:</span>
                        <span class="font-semibold text-emerald-600">Asignación Activa</span>
                    </div>
                </div>
            @else
                <div class="py-6 text-center text-slate-400 text-xs">
                    <p>Aún no tienes un entrenador asignado.</p>
                    <p class="text-[11px] text-slate-400 mt-1">El equipo administrativo te asignará un entrenador en tu primera sesión.</p>
                </div>
            @endif
        </div>

        <!-- 3. Tarjeta de Expediente Físico y Metas -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm space-y-4">
            <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400 block">Expediente y Metas</span>

            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Peso</span>
                    <h4 class="text-lg font-extrabold text-slate-900 mt-1">{{ $user->peso ? $user->peso . ' kg' : '--' }}</h4>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Estatura</span>
                    <h4 class="text-lg font-extrabold text-slate-900 mt-1">{{ $user->altura ? $user->altura . ' cm' : '--' }}</h4>
                </div>
            </div>

            <div class="text-xs space-y-2">
                <div>
                    <span class="font-bold text-slate-700 block">Objetivo Deportivo:</span>
                    <p class="text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-100 text-[11px]">
                        {{ $user->objetivos ?? 'No has registrado tus objetivos.' }}
                    </p>
                </div>
                <div>
                    <span class="font-bold text-slate-700 block">Historial Médico:</span>
                    <p class="text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-100 text-[11px]">
                        {{ $user->historial_medico ?? 'Sin condiciones o lesiones registradas.' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- Sección de Rutinas Asignadas -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Mis Rutinas de Entrenamiento</h2>
            <span class="text-xs text-slate-400">{{ $rutinas->count() }} rutina(s) activa(s)</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($rutinas as $rutina)
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-900 text-white">
                                {{ $rutina->dia_semana }}
                            </span>
                            <h3 class="text-sm font-bold text-slate-900 mt-1">{{ $rutina->titulo }}</h3>
                        </div>
                        <span class="text-[11px] text-slate-400">Por: {{ $rutina->entrenador->name ?? 'Entrenador' }}</span>
                    </div>

                    @if($rutina->observaciones)
                        <p class="text-xs text-slate-500 italic bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                            {{ $rutina->observaciones }}
                        </p>
                    @endif

                    <div class="space-y-2 text-xs">
                        <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Ejercicios:</span>
                        <div class="divide-y divide-slate-100">
                            @foreach($rutina->ejercicios as $ej)
                                <div class="py-2 flex items-center justify-between gap-3">
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $ej['nombre'] }}</div>
                                        @if(!empty($ej['notas']))
                                            <div class="text-[10px] text-slate-400">{{ $ej['notas'] }}</div>
                                        @endif
                                    </div>
                                    <div class="text-right text-slate-600 font-mono text-[11px]">
                                        <div><strong>{{ $ej['series'] }}</strong> &times; <strong>{{ $ej['repeticiones'] }}</strong></div>
                                        @if(!empty($ej['descanso']))
                                            <div class="text-[10px] text-slate-400">{{ $ej['descanso'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white rounded-2xl border border-dashed border-slate-300 p-8 text-center text-slate-400 text-xs">
                    <p>Aún no tienes rutinas de entrenamiento asignadas.</p>
                    <p class="mt-1 text-slate-400">Tu entrenador preparará tu plan de ejercicios en tu próxima sesión.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Historial de Pagos y Facturación (Módulo B / D) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Historial de Pagos y Órdenes</h2>
            <span class="text-xs text-slate-400">{{ $historialPagos->count() }} órdenes recientes</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-4">Orden</th>
                        <th class="py-3 px-4">Membresía</th>
                        <th class="py-3 px-4">Monto USD / Bs</th>
                        <th class="py-3 px-4">Método</th>
                        <th class="py-3 px-4">Estado</th>
                        <th class="py-3 px-4">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($historialPagos as $pago)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                #{{ str_pad($pago->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-800">
                                {{ $pago->membresia->nombre ?? 'Plan General' }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900">${{ number_format($pago->monto_usd, 2) }}</span>
                                <span class="text-[11px] text-slate-400">/ Bs. {{ number_format($pago->monto_bs, 2) }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $pago->metodo_pago }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                    {{ $pago->estado === 'Pagada' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $pago->estado }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500">
                                {{ \Carbon\Carbon::parse($pago->fecha_pago)->translatedFormat('d M, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">
                                No se registran pagos previos para esta cuenta.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal: Editar Perfil Físico y Médico (Módulo B) -->
<div id="modalPerfilFisico" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold">Ficha Física y Médica</h3>
                <p class="text-[11px] text-slate-300">Actualiza tus medidas corporales, objetivos y antecedentes</p>
            </div>
            <button type="button" onclick="document.getElementById('modalPerfilFisico').classList.add('hidden')" class="text-slate-400 hover:text-white text-lg font-bold">
                ✕
            </button>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Cédula de Identidad</label>
                    <input type="text" name="cedula" value="{{ $user->cedula }}" placeholder="V-12345678" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ $user->telefono }}" placeholder="+58 412..." class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Peso (kg)</label>
                    <input type="number" step="0.1" name="peso" value="{{ $user->peso }}" placeholder="Ej: 75.5" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Estatura (cm)</label>
                    <input type="number" step="0.1" name="altura" value="{{ $user->altura }}" placeholder="Ej: 175" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Objetivos Deportivos</label>
                <textarea name="objetivos" rows="2" placeholder="Ej: Pérdida de grasa, hipertrofia muscular, resistencia..." class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">{{ $user->objetivos }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Historial Médico / Lesiones</label>
                <textarea name="historial_medico" rows="2" placeholder="Ej: Cirugías previas, problemas articulares, hipertensión..." class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">{{ $user->historial_medico }}</textarea>
            </div>

            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalPerfilFisico').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Guardar Ficha
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
