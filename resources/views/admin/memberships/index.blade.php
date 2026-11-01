@extends('layouts.admin')

@section('title', 'Gestión de Membresías')
@section('page-title', 'Catálogo y Configuración de Planes de Membresía')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Encabezado con Botón de Creación y Tasa BCV -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Gestión de Planes de Membresía</h1>
            <p class="text-xs text-slate-500 mt-1">Configura planes por duración (1 mes, quincenal, trimestral), categoría VIP o estándar y precios en divisas a tasa BCV.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Tasa Oficial BCV: Bs. {{ number_format($tasaBcv, 2) }} / USD
            </span>

            <button type="button" 
                    onclick="abrirModalCrear()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all shadow-sm">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Crear Plan de Membresía</span>
            </button>
        </div>
    </div>

    <!-- Rejilla de Métricas Rápidas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Total Planes</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">#</span>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $totalPlanes }}</h3>
                <p class="text-xs text-slate-500 mt-1">Planes registrados en catálogo</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-amber-200/90 p-5 shadow-xs bg-gradient-to-br from-white via-amber-50/20 to-amber-50/40">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-amber-700">Membresías VIP</span>
                <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-amber-900">{{ $planesVip }}</h3>
                <p class="text-xs text-amber-700 mt-1">Planes con beneficios exclusivos</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Planes Activos</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">✓</span>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $planesActivos }}</h3>
                <p class="text-xs text-emerald-700 mt-1">Disponibles para venta en caja</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Atletas Suscritos</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $totalAtletasSuscritos }}</h3>
                <p class="text-xs text-slate-500 mt-1">Membresías activas en el gimnasio</p>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="bg-white rounded-xl border border-slate-200/90 p-4 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.memberships.index') }}" class="w-full flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Buscar plan por nombre o descripción..." 
                       class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="w-full sm:w-44">
                <select name="tipo" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="">Todas las Categorías</option>
                    <option value="vip" {{ request('tipo') === 'vip' ? 'selected' : '' }}>⭐ Planes VIP</option>
                    <option value="estandar" {{ request('tipo') === 'estandar' ? 'selected' : '' }}>Estándar / General</option>
                    <option value="estudiante" {{ request('tipo') === 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                    <option value="promocional" {{ request('tipo') === 'promocional' ? 'selected' : '' }}>Promocional</option>
                </select>
            </div>

            <div class="w-full sm:w-40">
                <select name="estado" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="">Todos los Estados</option>
                    <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Solo Activos</option>
                    <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Solo Inactivos</option>
                </select>
            </div>

            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                Filtrar
            </button>

            @if(request()->hasAny(['search', 'tipo', 'estado']))
                <a href="{{ route('admin.memberships.index') }}" class="text-xs text-slate-400 hover:text-slate-600 underline">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Rejilla de Tarjetas de Planes de Membresía -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($memberships as $plan)
            <div class="bg-white rounded-2xl border {{ $plan->isVip() ? 'border-amber-300 ring-1 ring-amber-300 shadow-md' : 'border-slate-200/90 shadow-xs' }} p-6 flex flex-col justify-between hover:shadow-lg transition-all relative overflow-hidden">
                
                @if($plan->isVip())
                    <div class="absolute -right-12 top-6 bg-gradient-to-r from-amber-500 to-yellow-500 text-white font-extrabold text-[9px] uppercase tracking-wider py-1 px-12 rotate-45 shadow-xs">
                        PLAN VIP
                    </div>
                @endif

                <div>
                    <!-- Encabezado de la Tarjeta -->
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">{{ $plan->nombre }}</h3>
                                @if($plan->isVip())
                                    <span class="inline-flex items-center text-amber-500" title="Beneficios VIP Exclusivos">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                                    ⏱️ {{ $plan->duracion_texto }}
                                </span>
                                @if($plan->activo)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Activo en Caja
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        Pausado
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Precio Multimoneda a Tasa BCV -->
                    <div class="mt-5 p-4 rounded-xl {{ $plan->isVip() ? 'bg-amber-50/50 border border-amber-100' : 'bg-slate-50 border border-slate-100' }}">
                        <div class="flex items-baseline justify-between">
                            <span class="text-xs font-semibold text-slate-500">Costo Base:</span>
                            <div class="text-right">
                                <span class="text-2xl font-black text-slate-900 font-mono">${{ number_format($plan->costo_base, 2) }}</span>
                                <span class="text-xs text-slate-400">USD</span>
                            </div>
                        </div>
                        <div class="flex items-baseline justify-between mt-1 pt-1 border-t border-slate-200/60">
                            <span class="text-[11px] font-medium text-slate-500">Equivalente BCV:</span>
                            <span class="text-xs font-bold text-emerald-700 font-mono">
                                Bs. {{ number_format($plan->costo_base * $tasaBcv, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Descripción y Beneficios -->
                    <div class="mt-4">
                        <p class="text-xs text-slate-600 line-clamp-3">
                            {{ $plan->descripcion ?: 'Acceso estándar a instalaciones de entrenamiento físico, pesas y cardio.' }}
                        </p>
                    </div>
                </div>

                <!-- Pie de Tarjeta y Acciones -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400 font-medium">
                        👥 {{ $plan->atletas_inscritos_count }} atletas activos
                    </span>

                    <div class="flex items-center gap-1.5">
                        <!-- Toggle de Estado -->
                        <form action="{{ route('admin.memberships.toggle', $plan) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 transition-colors" 
                                    title="{{ $plan->activo ? 'Pausar disponibilidad en caja' : 'Activar disponibilidad en caja' }}">
                                @if($plan->activo)
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </button>
                        </form>

                        <!-- Editar -->
                        <button type="button" 
                                onclick='abrirModalEditar(@json($plan))'
                                class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 transition-colors" 
                                title="Editar plan">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>

                        <!-- Eliminar / Desactivar -->
                        <form action="{{ route('admin.memberships.destroy', $plan) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar o retirar este plan?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-1.5 rounded-lg border border-slate-200 hover:bg-red-50 text-red-600 transition-colors" 
                                    title="Eliminar o deshabilitar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-slate-200 p-8 text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="text-sm font-bold text-slate-700">No hay planes registrados</h3>
                <p class="text-xs text-slate-500 mt-1">Crea el primer plan de membresía para habilitar la cobranza a tus atletas.</p>
                <button onclick="abrirModalCrear()" class="mt-4 px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800">
                    + Crear Plan
                </button>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="pt-2">
        {{ $memberships->links() }}
    </div>

</div>

<!-- Modal: Crear Plan de Membresía -->
<div id="modalCrearMembresia" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold">Crear Plan de Membresía</h3>
                <p class="text-[11px] text-slate-300">Configura duración, costo en USD y estatus VIP según requerimiento del cliente</p>
            </div>
            <button type="button" onclick="cerrarModalCrear()" class="text-slate-400 hover:text-white text-lg font-bold">
                ✕
            </button>
        </div>

        <form action="{{ route('admin.memberships.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Nombre -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nombre del Plan *</label>
                <input type="text" name="nombre" required placeholder="Ej: Plan Mensual VIP Gold, Plan General 1 Mes..." class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
            </div>

            <!-- Duración y Presets -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Duración en Días *</label>
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <button type="button" onclick="setDuracion(30, 'modalCrearMembresia')" class="px-2 py-1.5 text-[11px] font-bold rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-700">
                        1 Mes (30d)
                    </button>
                    <button type="button" onclick="setDuracion(15, 'modalCrearMembresia')" class="px-2 py-1.5 text-[11px] font-bold rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-700">
                        15 Días
                    </button>
                    <button type="button" onclick="setDuracion(90, 'modalCrearMembresia')" class="px-2 py-1.5 text-[11px] font-bold rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-700">
                        3 Meses (90d)
                    </button>
                </div>
                <input type="number" name="duracion_dias" id="inputDuracionCrear" required min="1" max="1000" value="30" placeholder="Ej: 30" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
            </div>

            <!-- Costo Base USD y Conversión BCV -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Costo Base (USD) *</label>
                    <input type="number" step="0.01" min="0" name="costo_base" id="inputCostoCrear" required oninput="calcularBsModal(this.value, 'previewBsCrear')" placeholder="30.00" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Equivalente BCV</label>
                    <div id="previewBsCrear" class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs font-bold text-emerald-700">
                        Bs. 0.00
                    </div>
                </div>
            </div>

            <!-- Categoría y Condición VIP -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Categoría del Plan *</label>
                <select name="tipo" id="selectTipoCrear" onchange="toggleVipCheckbox(this.value, 'checkVipCrear')" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="estandar">Estándar / General</option>
                    <option value="vip">⭐ VIP (Beneficios Exclusivos)</option>
                    <option value="estudiante">Estudiante / Juvenil</option>
                    <option value="promocional">Promocional / Especial</option>
                </select>
            </div>

            <!-- Casilla de Verificación VIP -->
            <div class="p-3 rounded-xl bg-amber-50/60 border border-amber-200">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="es_vip" value="1" id="checkVipCrear" class="rounded text-amber-600 focus:ring-amber-500 w-4 h-4">
                    <div>
                        <span class="text-xs font-bold text-amber-950">Marcar como Membresía VIP</span>
                        <p class="text-[10px] text-amber-800">Otorga distintivo especial y acceso a servicios prémium (sauna, casillero personal, asesoría).</p>
                    </div>
                </label>
            </div>

            <!-- Descripción y Beneficios -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Descripción / Beneficios Incluidos</label>
                <textarea name="descripcion" rows="2" placeholder="Ej: Acceso a sala de pesas, cardio, casillero VIP y toalla de entrenamiento..." class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none"></textarea>
            </div>

            <!-- Botones -->
            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="cerrarModalCrear()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Guardar Plan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Plan de Membresía -->
<div id="modalEditarMembresia" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold">Editar Plan de Membresía</h3>
                <p class="text-[11px] text-slate-300">Modifica los parámetros comerciales y categoría VIP</p>
            </div>
            <button type="button" onclick="cerrarModalEditar()" class="text-slate-400 hover:text-white text-lg font-bold">
                ✕
            </button>
        </div>

        <form id="formEditarMembresia" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nombre del Plan *</label>
                <input type="text" name="nombre" id="editNombre" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
            </div>

            <!-- Duración en Días -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Duración en Días *</label>
                <input type="number" name="duracion_dias" id="editDuracion" required min="1" max="1000" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
            </div>

            <!-- Costo Base USD -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Costo Base (USD) *</label>
                    <input type="number" step="0.01" min="0" name="costo_base" id="editCosto" required oninput="calcularBsModal(this.value, 'previewBsEditar')" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Equivalente BCV</label>
                    <div id="previewBsEditar" class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs font-bold text-emerald-700">
                        Bs. 0.00
                    </div>
                </div>
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Categoría del Plan *</label>
                <select name="tipo" id="editTipo" onchange="toggleVipCheckbox(this.value, 'editCheckVip')" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="estandar">Estándar / General</option>
                    <option value="vip">⭐ VIP (Beneficios Exclusivos)</option>
                    <option value="estudiante">Estudiante / Juvenil</option>
                    <option value="promocional">Promocional / Especial</option>
                </select>
            </div>

            <!-- Casilla VIP -->
            <div class="p-3 rounded-xl bg-amber-50/60 border border-amber-200">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="es_vip" value="1" id="editCheckVip" class="rounded text-amber-600 focus:ring-amber-500 w-4 h-4">
                    <div>
                        <span class="text-xs font-bold text-amber-950">Membresía VIP</span>
                        <p class="text-[10px] text-amber-800">Servicio prémium con casillero y áreas VIP.</p>
                    </div>
                </label>
            </div>

            <!-- Estado Activo -->
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="activo" value="1" id="editActivo" class="rounded text-slate-900 focus:ring-slate-900 w-4 h-4">
                    <span class="text-xs font-bold text-slate-700">Disponible para la venta en caja</span>
                </label>
            </div>

            <!-- Descripción -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Descripción / Beneficios</label>
                <textarea name="descripcion" id="editDescripcion" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none"></textarea>
            </div>

            <!-- Botones -->
            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="cerrarModalEditar()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const tasaBcvMembresias = {{ $tasaBcv }};

    function calcularBsModal(montoUsd, elementId) {
        const usd = parseFloat(montoUsd) || 0;
        const bs = (usd * tasaBcvMembresias).toFixed(2);
        document.getElementById(elementId).textContent = `Bs. ${Number(bs).toLocaleString('es-VE', {minimumFractionDigits: 2})}`;
    }

    function setDuracion(dias, modalId) {
        document.getElementById('inputDuracionCrear').value = dias;
    }

    function toggleVipCheckbox(tipoValue, checkId) {
        const checkbox = document.getElementById(checkId);
        if (tipoValue === 'vip') {
            checkbox.checked = true;
        }
    }

    function abrirModalCrear() {
        document.getElementById('modalCrearMembresia').classList.remove('hidden');
    }

    function cerrarModalCrear() {
        document.getElementById('modalCrearMembresia').classList.add('hidden');
    }

    function abrirModalEditar(plan) {
        const form = document.getElementById('formEditarMembresia');
        form.action = `/admin/memberships/${plan.id}`;

        document.getElementById('editNombre').value = plan.nombre || '';
        document.getElementById('editDuracion').value = plan.duracion_dias || 30;
        document.getElementById('editCosto').value = parseFloat(plan.costo_base).toFixed(2);
        calcularBsModal(plan.costo_base, 'previewBsEditar');

        document.getElementById('editTipo').value = plan.tipo || 'estandar';
        document.getElementById('editCheckVip').checked = Boolean(plan.es_vip);
        document.getElementById('editActivo').checked = Boolean(plan.activo);
        document.getElementById('editDescripcion').value = plan.descripcion || '';

        document.getElementById('modalEditarMembresia').classList.remove('hidden');
    }

    function cerrarModalEditar() {
        document.getElementById('modalEditarMembresia').classList.add('hidden');
    }
</script>
@endsection
