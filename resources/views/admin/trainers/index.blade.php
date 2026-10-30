@extends('layouts.admin')

@section('title', 'Entrenadores')
@section('page-title', 'Gestión de Entrenadores')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Encabezado de Sección Minimalista con Botón de Registro -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Personal Técnico y Entrenadores</h1>
            <p class="text-xs text-slate-500 mt-1">Supervisa al staff técnico, su estado operativo y consulta en tiempo real los atletas que tienen a su cargo.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium bg-white border border-slate-200 text-slate-600 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-slate-900"></span>
                Total: {{ $trainers->count() }} entrenadores
            </span>

            <button type="button" onclick="openPromoteModal()" 
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold tracking-wide transition-all shadow-sm">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                <span>Ascender Cliente</span>
            </button>

            <button type="button" onclick="openTrainerModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold tracking-wide transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Registrar Entrenador</span>
            </button>
        </div>
    </div>

    <!-- Mensajes de Estado Flash (Las alertas globales se muestran arriba del título en el layout principal) -->

    <!-- Tabla Corporativa Minimalista de Entrenadores -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/70 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Entrenador</th>
                        <th class="py-3.5 px-6">Correo Electrónico</th>
                        <th class="py-3.5 px-6">Atletas Asignados</th>
                        <th class="py-3.5 px-6">Estatus</th>
                        <th class="py-3.5 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse ($trainers as $trainer)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Nombre -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200/80 flex items-center justify-center font-bold text-slate-800 text-xs shadow-xs">
                                        {{ strtoupper(substr($trainer->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $trainer->name }}</div>
                                        <div class="text-[11px] text-slate-400">ID: #{{ $trainer->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Correo -->
                            <td class="py-4 px-6 font-medium text-slate-700">
                                {{ $trainer->email }}
                            </td>

                            <!-- Atletas Asignados (Tiempo Real) -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $trainer->total_atletas }} {{ $trainer->total_atletas === 1 ? 'atleta' : 'atletas' }}
                                    </span>
                                    @if($trainer->total_atletas > 0)
                                        <button type="button" 
                                                onclick="showAthletesModal('{{ addslashes($trainer->name) }}', {{ json_encode($trainer->clientesActivos) }})" 
                                                class="inline-flex items-center gap-1 text-[11px] text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                                            <span>Ver atletas</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>

                            <!-- Estatus -->
                            <td class="py-4 px-6">
                                @if(strtolower($trainer->estado) === 'activo')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones del Entrenador -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Botón Editar Entrenador -->
                                    <button type="button" onclick="openEditModal({{ $trainer->id }}, '{{ addslashes($trainer->name) }}', '{{ addslashes($trainer->email) }}', '{{ $trainer->estado }}')"
                                            title="Editar Información"
                                            class="inline-flex items-center justify-center p-1.5 rounded-lg bg-white border border-slate-200 hover:border-slate-800 text-slate-600 hover:text-slate-900 transition-all shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Botón Eliminar Entrenador -->
                                    <button type="button" onclick="confirmDeleteTrainer({{ $trainer->id }}, '{{ addslashes($trainer->name) }}', {{ $trainer->total_atletas }})"
                                            title="Eliminar Entrenador"
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
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="font-medium text-slate-700 text-xs">No hay entrenadores registrados en el sistema.</p>
                                    <p class="text-[11px] text-slate-400 mt-1">Haz clic en "Registrar Entrenador" para inscribir personal técnico.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal 1: Registrar Nuevo Entrenador -->
<div id="trainerModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xl w-full max-w-md p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-150">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900">Registrar Entrenador</h3>
                <p class="text-xs text-slate-400 mt-0.5">Alta de personal técnico con rol de 'entrenador'</p>
            </div>
            <button type="button" onclick="closeTrainerModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.trainers.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nombre Completo
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       placeholder="Ej. Roberto Gómez"
                       class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Correo Electrónico
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       placeholder="roberto.gomez@gymrdsport.com"
                       class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Contraseña
                </label>
                <input type="password" id="password" name="password" required
                       placeholder="Mínimo 8 caracteres"
                       class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Confirmar Contraseña
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       placeholder="Repite la contraseña"
                       class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
            </div>

            <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeTrainerModal()" 
                        class="px-4 py-2.5 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Guardar Entrenador
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal 2: Editar Entrenador Seleccionado -->
<div id="editTrainerModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xl w-full max-w-md p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-150">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900">Editar Entrenador</h3>
                <p class="text-xs text-slate-400 mt-0.5">Actualizar datos del perfil y estatus operativo</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="editTrainerForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nombre Completo
                </label>
                <input type="text" id="edit_name" name="name" required
                       class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
            </div>

            <div>
                <label for="edit_email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Correo Electrónico
                </label>
                <input type="email" id="edit_email" name="email" required
                       class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
            </div>

            <div>
                <label for="edit_estado" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Estatus
                </label>
                <select id="edit_estado" name="estado" class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
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

<!-- Modal: Ascender Cliente a Entrenador -->
<div id="promoteModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xl w-full max-w-md p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-150">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900">Ascender Cliente a Entrenador</h3>
                <p class="text-xs text-slate-400 mt-0.5">Asigna el rol de personal técnico sin alterar sus credenciales</p>
            </div>
            <button type="button" onclick="closePromoteModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.trainers.promote') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="promote_cliente_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Seleccionar Cliente
                </label>
                <select id="promote_cliente_id" name="cliente_id" required
                        class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                    <option value="" disabled selected>-- Elige un cliente registrado --</option>
                    @foreach ($clientes as $cli)
                        <option value="{{ $cli->id }}">
                            {{ $cli->name }} ({{ $cli->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1.5">
                    El usuario mantendrá su correo y contraseña intactos. Si tenía un entrenador asignado, quedará automáticamente desvinculado como atleta.
                </p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closePromoteModal()" 
                        class="px-4 py-2.5 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Confirmar Ascenso
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal 3: Lista de Atletas Vinculados al Entrenador (Solo Consulta en Tiempo Real) -->
<div id="athletesModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xl w-full max-w-lg p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Atletas Asignados</h3>
                <p id="athletesTrainerName" class="text-xs text-slate-500 mt-0.5">Personal técnico</p>
            </div>
            <button type="button" onclick="closeAthletesModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div id="athletesListContainer" class="max-h-72 overflow-y-auto space-y-2 pr-1">
            <!-- Rellenado dinámicamente con JS -->
        </div>

        <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between">
            <span class="text-[11px] text-slate-400">Para reasignar un cliente, ve a Gestión de Clientes.</span>
            <button type="button" onclick="closeAthletesModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<!-- Formulario Oculto para Eliminar Entrenador -->
<form id="deleteTrainerForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    // Modal Registrar
    function openTrainerModal() {
        document.getElementById('trainerModal').classList.remove('hidden');
    }
    function closeTrainerModal() {
        document.getElementById('trainerModal').classList.add('hidden');
    }

    // Modal Ascender Cliente
    function openPromoteModal() {
        document.getElementById('promoteModal').classList.remove('hidden');
    }
    function closePromoteModal() {
        document.getElementById('promoteModal').classList.add('hidden');
    }

    // Modal Editar
    function openEditModal(id, name, email, estado) {
        const form = document.getElementById('editTrainerForm');
        form.action = `/admin/trainers/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_estado').value = estado;
        document.getElementById('editTrainerModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editTrainerModal').classList.add('hidden');
    }

    // Modal Atletas (Consulta limpia en tiempo real)
    function showAthletesModal(trainerName, athletes) {
        document.getElementById('athletesTrainerName').textContent = 'Entrenador: ' + trainerName;
        const container = document.getElementById('athletesListContainer');
        container.innerHTML = '';

        if (!athletes || athletes.length === 0) {
            container.innerHTML = '<p class="text-xs text-slate-400 text-center py-6">Este entrenador no tiene atletas asignados en este momento.</p>';
        } else {
            athletes.forEach(athlete => {
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs';
                
                item.innerHTML = `
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center font-bold text-indigo-700 text-[10px]">
                            ${athlete.name.substring(0, 2).toUpperCase()}
                        </div>
                        <div>
                            <div class="font-bold text-slate-900">${athlete.name}</div>
                            <div class="text-slate-400 text-[11px]">${athlete.email}</div>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Asignado activo
                    </span>
                `;
                container.appendChild(item);
            });
        }

        document.getElementById('athletesModal').classList.remove('hidden');
    }

    // Confirmación y Eliminación de Entrenador
    function confirmDeleteTrainer(trainerId, trainerName, totalAthletes) {
        let msg = `¿Estás seguro de que deseas eliminar permanentemente al entrenador "${trainerName}"?`;
        if (totalAthletes > 0) {
            msg += `\n\nAtención: Tiene ${totalAthletes} atleta(s) asignado(s) que quedarán desvinculados automáticamente.`;
        }
        
        if (confirm(msg)) {
            const form = document.getElementById('deleteTrainerForm');
            form.action = `/admin/trainers/${trainerId}`;
            form.submit();
        }
    }

    function closeAthletesModal() {
        document.getElementById('athletesModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeTrainerModal();
            closePromoteModal();
            closeEditModal();
            closeAthletesModal();
        }
    });
</script>
@endsection
