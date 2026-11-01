@extends('layouts.admin')

@section('title', "Perfil de {$athlete->name}")
@section('page-title', 'Gestión de Atleta y Rutinas')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Navegación y Encabezado -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
        <div class="flex items-center gap-3">
            <a href="{{ route('trainer.dashboard') }}" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $athlete->name }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Expediente deportivo, historial físico y prescripción de rutinas personalizadas.</p>
            </div>
        </div>
        <div>
            <button type="button" 
                    onclick="document.getElementById('modalNuevaRutina').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all shadow-sm">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Crear Plan de Rutina</span>
            </button>
        </div>
    </div>

    <!-- Rejilla de Información del Atleta (Física, Médica y Membresía) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tarjeta 1: Datos Personales y Membresía -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold tracking-wider uppercase text-slate-400">Datos del Atleta</h3>
            
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-slate-900 text-white font-bold flex items-center justify-center text-lg">
                    {{ strtoupper(substr($athlete->name, 0, 2)) }}
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ $athlete->name }}</h4>
                    <p class="text-xs text-slate-400">{{ $athlete->email }}</p>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Cédula:</span>
                    <span class="font-semibold text-slate-800">{{ $athlete->cedula ?? 'No especificada' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Teléfono:</span>
                    <span class="font-semibold text-slate-800">{{ $athlete->telefono ?? 'No especificado' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Estado de Cuenta:</span>
                    <span class="capitalize font-semibold {{ $athlete->isMoroso() ? 'text-red-600' : 'text-emerald-600' }}">
                        {{ $athlete->isMoroso() ? 'Moroso / Vencido' : 'Activo' }}
                    </span>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100">
                <span class="text-[11px] font-bold uppercase text-slate-400 block mb-1">Membresía Actual</span>
                @if($membresia)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                        <div class="font-bold text-slate-900">{{ $membresia->membresia->nombre }}</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">Vigente hasta: <strong>{{ $membresia->fecha_fin }}</strong></div>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Sin membresía activa.</p>
                @endif
            </div>
        </div>

        <!-- Tarjeta 2: Historial Físico y Médico -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold tracking-wider uppercase text-slate-400">Condición Física y Salud</h3>

            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Peso Actual</span>
                    <h4 class="text-xl font-extrabold text-slate-900 mt-1">{{ $athlete->peso ? $athlete->peso . ' kg' : '--' }}</h4>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Estatura</span>
                    <h4 class="text-xl font-extrabold text-slate-900 mt-1">{{ $athlete->altura ? $athlete->altura . ' cm' : '--' }}</h4>
                </div>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="font-bold text-slate-700 block mb-1">Historial Médico / Lesiones:</span>
                    <p class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 leading-relaxed">
                        {{ $athlete->historial_medico ?? 'Sin antecedentes médicos o lesiones reportadas.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3: Objetivos Deportivos -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold tracking-wider uppercase text-slate-400">Metas y Objetivos Deportivos</h3>

            <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-200 text-xs text-emerald-900 leading-relaxed">
                <div class="font-bold mb-1 flex items-center gap-1.5 text-emerald-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Meta Principal del Atleta:
                </div>
                {{ $athlete->objetivos ?? 'El atleta aún no ha especificado sus objetivos deportivos principales.' }}
            </div>

            <div class="text-xs text-slate-500">
                <p>Las rutinas que crees en este panel estarán disponibles automáticamente en el dashboard personal del atleta.</p>
            </div>
        </div>
    </div>

    <!-- Planes de Rutinas Asignados al Atleta -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-slate-900">Planes de Rutina Asignados</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($rutinas as $rutina)
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-900 text-white mb-1">
                                    {{ $rutina->dia_semana }}
                                </span>
                                <h3 class="text-sm font-bold text-slate-900">{{ $rutina->titulo }}</h3>
                            </div>
                            <form action="{{ route('trainer.routines.destroy', $rutina) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta rutina?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold p-1.5 rounded-lg hover:bg-red-50 transition-colors">
                                    Eliminar
                                </button>
                            </form>
                        </div>

                        <div class="p-6 space-y-4">
                            @if($rutina->observaciones)
                                <p class="text-xs text-slate-500 italic bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <strong>Indicaciones:</strong> {{ $rutina->observaciones }}
                                </p>
                            @endif

                            <div class="space-y-2">
                                <span class="text-[11px] font-bold uppercase text-slate-400 tracking-wider">Ejercicios:</span>
                                <div class="divide-y divide-slate-100 text-xs">
                                    @foreach($rutina->ejercicios as $ej)
                                        <div class="py-2.5 flex items-center justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="font-bold text-slate-800">{{ $ej['nombre'] }}</div>
                                                @if(!empty($ej['notas']))
                                                    <div class="text-[11px] text-slate-400">{{ $ej['notas'] }}</div>
                                                @endif
                                            </div>
                                            <div class="text-right text-slate-600 font-mono text-[11px]">
                                                <div><strong>{{ $ej['series'] }}</strong> series &times; <strong>{{ $ej['repeticiones'] }}</strong></div>
                                                @if(!empty($ej['descanso']))
                                                    <div class="text-[10px] text-slate-400">Descanso: {{ $ej['descanso'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white rounded-2xl border border-dashed border-slate-300 p-8 text-center text-slate-400">
                    <p class="text-sm">Este atleta aún no tiene rutinas asignadas.</p>
                    <button type="button" 
                            onclick="document.getElementById('modalNuevaRutina').classList.remove('hidden')"
                            class="mt-3 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800">
                        + Crear Primera Rutina
                    </button>
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Modal: Crear Plan de Rutina Personalizado -->
<div id="modalNuevaRutina" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full shadow-xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold">Crear Plan de Rutina para {{ $athlete->name }}</h3>
                <p class="text-[11px] text-slate-300">Asigna ejercicios, series, repeticiones e instrucciones</p>
            </div>
            <button type="button" onclick="document.getElementById('modalNuevaRutina').classList.add('hidden')" class="text-slate-400 hover:text-white text-lg font-bold">
                ✕
            </button>
        </div>

        <form action="{{ route('trainer.routines.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="cliente_id" value="{{ $athlete->id }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Título de la Rutina *</label>
                    <input type="text" name="titulo" required placeholder="Ej: Hipertrofia Tren Superior" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Día Asignado *</label>
                    <select name="dia_semana" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Observaciones / Indicaciones</label>
                <input type="text" name="observaciones" placeholder="Ej: Calentar 5 min en elíptica, cuidar cadencia..." class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
            </div>

            <!-- Lista Dinámica de Ejercicios -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-700">Ejercicios de la Rutina *</label>
                    <button type="button" onclick="agregarEjercicio()" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                        + Añadir Ejercicio
                    </button>
                </div>

                <div id="contenedorEjercicios" class="space-y-3">
                    <div class="ejercicio-item bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-2">
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-6">
                                <input type="text" name="ejercicios[0][nombre]" required placeholder="Nombre del ejercicio (ej. Press Plano)" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900">
                            </div>
                            <div class="col-span-2">
                                <input type="number" name="ejercicios[0][series]" required placeholder="Series" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900">
                            </div>
                            <div class="col-span-2">
                                <input type="text" name="ejercicios[0][repeticiones]" required placeholder="Reps (10-12)" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900">
                            </div>
                            <div class="col-span-2">
                                <input type="text" name="ejercicios[0][descanso]" placeholder="Descanso (60s)" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900">
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-11">
                                <input type="text" name="ejercicios[0][notas]" placeholder="Notas adicionales / técnica..." class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs">
                            </div>
                            <div class="col-span-1 flex items-center justify-center">
                                <button type="button" onclick="eliminarEjercicio(this)" class="text-red-400 hover:text-red-600 text-sm font-bold">✕</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalNuevaRutina').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Guardar y Asignar Rutina
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let indiceEjercicio = 1;

    function agregarEjercicio() {
        const contenedor = document.getElementById('contenedorEjercicios');
        const item = document.createElement('div');
        item.className = 'ejercicio-item bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-2';
        item.innerHTML = `
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-6">
                    <input type="text" name="ejercicios[${indiceEjercicio}][nombre]" required placeholder="Nombre del ejercicio" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900">
                </div>
                <div class="col-span-2">
                    <input type="number" name="ejercicios[${indiceEjercicio}][series]" required placeholder="Series" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900">
                </div>
                <div class="col-span-2">
                    <input type="text" name="ejercicios[${indiceEjercicio}][repeticiones]" required placeholder="Reps (10-12)" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900">
                </div>
                <div class="col-span-2">
                    <input type="text" name="ejercicios[${indiceEjercicio}][descanso]" placeholder="Descanso (60s)" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900">
                </div>
            </div>
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-11">
                    <input type="text" name="ejercicios[${indiceEjercicio}][notas]" placeholder="Notas adicionales / técnica..." class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs">
                </div>
                <div class="col-span-1 flex items-center justify-center">
                    <button type="button" onclick="eliminarEjercicio(this)" class="text-red-400 hover:text-red-600 text-sm font-bold">✕</button>
                </div>
            </div>
        `;
        contenedor.appendChild(item);
        indiceEjercicio++;
    }

    function eliminarEjercicio(btn) {
        const contenedor = document.getElementById('contenedorEjercicios');
        if (contenedor.children.length > 1) {
            btn.closest('.ejercicio-item').remove();
        } else {
            alert('La rutina debe contener al menos 1 ejercicio.');
        }
    }
</script>
@endsection
