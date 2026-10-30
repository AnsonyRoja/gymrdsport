@extends('layouts.admin')

@section('title', 'Servicios y Horarios')
@section('page-title', 'Planificación de Servicios')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Encabezado con Botón de Creación -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Planificación de Servicios y Clases</h1>
            <p class="text-xs text-slate-500 mt-1">Configuración de disciplinas (Crossfit, Spinning, Musculación, etc.), horarios de inicio y fin, aforo de cupos y estatus.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium bg-white border border-slate-200 text-slate-600 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-slate-900"></span>
                Total: {{ $services->count() }} servicios
            </span>

            <button type="button" onclick="openCreateModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold tracking-wide transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Nuevo Servicio / Clase</span>
            </button>
        </div>
    </div>

    <!-- Tabla Corporativa Minimalista de Servicios -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/70 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Servicio / Clase</th>
                        <th class="py-3.5 px-6">Descripción</th>
                        <th class="py-3.5 px-6">Horario (Inicio - Fin)</th>
                        <th class="py-3.5 px-6">Cupos Disponibles</th>
                        <th class="py-3.5 px-6">Estatus</th>
                        <th class="py-3.5 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse ($services as $service)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Nombre -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                        {{ strtoupper(substr($service->nombre_servicio, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm">{{ $service->nombre_servicio }}</div>
                                        <div class="text-[11px] text-slate-400">ID: #{{ $service->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Descripción -->
                            <td class="py-4 px-6 text-slate-500 max-w-xs">
                                <p class="truncate" title="{{ $service->descripcion }}">
                                    {{ $service->descripcion ?: 'Sin descripción detallada' }}
                                </p>
                            </td>

                            <!-- Horario -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200/60">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($service->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($service->hora_fin)->format('h:i A') }}
                                </span>
                            </td>

                            <!-- Cupos -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $service->cupos_disponibles }} plazas
                                </span>
                            </td>

                            <!-- Insignia Visual de Estatus (Activo / Inactivo) -->
                            <td class="py-4 px-6">
                                @if($service->estatus === 'Activo')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones (Editar y Eliminar) -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Botón Editar -->
                                    <button type="button" 
                                            onclick="openEditModal({{ $service->id }}, '{{ addslashes($service->nombre_servicio) }}', '{{ addslashes($service->descripcion ?? '') }}', '{{ substr($service->hora_inicio, 0, 5) }}', '{{ substr($service->hora_fin, 0, 5) }}', {{ $service->cupos_disponibles }}, '{{ $service->estatus }}')"
                                            title="Editar Clase / Servicio"
                                            class="inline-flex items-center justify-center p-1.5 rounded-lg bg-white border border-slate-200 hover:border-slate-800 text-slate-600 hover:text-slate-900 transition-all shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Botón Eliminar -->
                                    <button type="button" 
                                            onclick="confirmDeleteService({{ $service->id }}, '{{ addslashes($service->nombre_servicio) }}')"
                                            title="Eliminar Servicio"
                                            class="inline-flex items-center justify-center p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition-all border border-rose-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="font-medium text-slate-700 text-xs">No hay servicios ni clases planificadas aún.</p>
                                    <p class="text-[11px] text-slate-400 mt-1">Haz clic en "Nuevo Servicio / Clase" para configurar el primero.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal 1: Registro de Nuevo Servicio / Clase -->
<div id="serviceModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xl w-full max-w-md p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-150">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900">Nuevo Servicio o Clase</h3>
                <p class="text-xs text-slate-400 mt-0.5">Planificación horaria y aforo de la disciplina</p>
            </div>
            <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nombre del Servicio -->
            <div>
                <label for="nombre_servicio" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nombre del Servicio / Disciplina
                </label>
                <input type="text" id="nombre_servicio" name="nombre_servicio" value="{{ old('nombre_servicio') }}" required
                       placeholder="Ej. Crossfit, Spinning, Musculación libre, Yoga"
                       class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
            </div>

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Descripción (Opcional)
                </label>
                <textarea id="descripcion" name="descripcion" rows="2" placeholder="Breve detalle sobre la intensidad, requisitos o área asignada..."
                          class="w-full px-3.5 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">{{ old('descripcion') }}</textarea>
            </div>

            <!-- Horarios (Inicio y Fin) -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="hora_inicio" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Hora Inicio
                    </label>
                    <input type="time" id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio', '07:00') }}" required
                           class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                </div>

                <div>
                    <label for="hora_fin" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Hora Fin
                    </label>
                    <input type="time" id="hora_fin" name="hora_fin" value="{{ old('hora_fin', '08:30') }}" required
                           class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                </div>
            </div>

            <!-- Cupos y Estatus -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="cupos_disponibles" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Cupos Disponibles
                    </label>
                    <input type="number" id="cupos_disponibles" name="cupos_disponibles" min="1" max="500" value="{{ old('cupos_disponibles', 20) }}" required
                           class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                </div>

                <div>
                    <label for="estatus" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Estatus
                    </label>
                    <select id="estatus" name="estatus" class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                        <option value="Activo" selected>Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" 
                        class="px-4 py-2.5 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Guardar Servicio
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal 2: Editar Servicio / Clase Existente -->
<div id="editServiceModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xl w-full max-w-md p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-150">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900">Editar Servicio o Clase</h3>
                <p class="text-xs text-slate-400 mt-0.5">Modificar horario, aforo y cambiar estatus</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editServiceForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nombre del Servicio -->
            <div>
                <label for="edit_nombre_servicio" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nombre del Servicio / Disciplina
                </label>
                <input type="text" id="edit_nombre_servicio" name="nombre_servicio" required
                       class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
            </div>

            <!-- Descripción -->
            <div>
                <label for="edit_descripcion" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Descripción (Opcional)
                </label>
                <textarea id="edit_descripcion" name="descripcion" rows="2"
                          class="w-full px-3.5 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors"></textarea>
            </div>

            <!-- Horarios (Inicio y Fin) -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_hora_inicio" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Hora Inicio
                    </label>
                    <input type="time" id="edit_hora_inicio" name="hora_inicio" required
                           class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                </div>

                <div>
                    <label for="edit_hora_fin" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Hora Fin
                    </label>
                    <input type="time" id="edit_hora_fin" name="hora_fin" required
                           class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                </div>
            </div>

            <!-- Cupos y Estatus -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_cupos_disponibles" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Cupos Disponibles
                    </label>
                    <input type="number" id="edit_cupos_disponibles" name="cupos_disponibles" min="1" max="500" required
                           class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                </div>

                <div>
                    <label for="edit_estatus" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Estatus
                    </label>
                    <select id="edit_estatus" name="estatus" class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2.5 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Formulario Oculto para Eliminar Servicio -->
<form id="deleteServiceForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function openCreateModal() {
        document.getElementById('serviceModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('serviceModal').classList.add('hidden');
    }

    function openEditModal(id, nombre, descripcion, horaInicio, horaFin, cupos, estatus) {
        const form = document.getElementById('editServiceForm');
        form.action = `/admin/services/${id}`;
        document.getElementById('edit_nombre_servicio').value = nombre;
        document.getElementById('edit_descripcion').value = descripcion;
        document.getElementById('edit_hora_inicio').value = horaInicio;
        document.getElementById('edit_hora_fin').value = horaFin;
        document.getElementById('edit_cupos_disponibles').value = cupos;
        document.getElementById('edit_estatus').value = estatus;

        document.getElementById('editServiceModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editServiceModal').classList.add('hidden');
    }

    function confirmDeleteService(serviceId, serviceName) {
        if (confirm(`¿Estás seguro de que deseas eliminar la clase / servicio "${serviceName}"?`)) {
            const form = document.getElementById('deleteServiceForm');
            form.action = `/admin/services/${serviceId}`;
            form.submit();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });
</script>
@endsection
