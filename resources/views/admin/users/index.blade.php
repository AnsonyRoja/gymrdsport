@extends('layouts.admin')

@section('title', 'Usuarios y Clientes')
@section('page-title', 'Gestión de Clientes')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Encabezado de Sección Minimalista -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Gestión de Clientes y Atletas</h1>
            <p class="text-xs text-slate-500 mt-1">Supervisa los clientes registrados, su estatus y realiza la reasignación dinámica de entrenadores (RFC-03 / TRX-02).</p>
        </div>
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-white border border-slate-200 text-slate-600 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                Total Clientes: {{ $clients->total() }}
            </span>
        </div>
    </div>

    <!-- Tabla Corporativa Minimalista de Clientes -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/70 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Cliente / Atleta</th>
                        <th class="py-3.5 px-6">Correo Electrónico</th>
                        <th class="py-3.5 px-6">Entrenador Actual</th>
                        <th class="py-3.5 px-6">Estatus</th>
                        <th class="py-3.5 px-6 text-right">Reasignación Dinámica</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse ($clients as $client)
                        @php
                            $entrenadorActual = $client->entrenadorActivo->first();
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Nombre Cliente -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200/80 flex items-center justify-center font-bold text-slate-800 text-xs shadow-xs">
                                        {{ strtoupper(substr($client->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $client->name }}</div>
                                        <div class="text-[11px] text-slate-400">ID: #{{ $client->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Correo -->
                            <td class="py-4 px-6 font-medium text-slate-700">
                                {{ $client->email }}
                            </td>

                            <!-- Entrenador Asignado Actual -->
                            <td class="py-4 px-6">
                                @if($entrenadorActual)
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $entrenadorActual->name }}
                                        </span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Sin asignar
                                    </span>
                                @endif
                            </td>

                            <!-- Estatus del Cliente -->
                            <td class="py-4 px-6">
                                @if($client->estado === 'activo')
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

                            <!-- Acción: Selector / Modal de Reasignación -->
                            <td class="py-4 px-6 text-right">
                                <button type="button" 
                                        onclick="openReassignModal({{ $client->id }}, '{{ addslashes($client->name) }}', {{ $entrenadorActual ? $entrenadorActual->id : 'null' }}, '{{ $entrenadorActual ? addslashes($entrenadorActual->name) : 'Ninguno' }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:border-slate-800 text-slate-700 hover:text-slate-900 text-xs font-semibold transition-all shadow-xs">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    <span>Reasignar Entrenador</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <p class="font-medium text-slate-700 text-xs">No hay clientes registrados en el sistema.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $clients->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal: Reasignar Entrenador Dinámicamente (RFC-03 / TRX-02) -->
<div id="reassignModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xl w-full max-w-md p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-150">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900">Reasignar Entrenador</h3>
                <p id="reassignClientLabel" class="text-xs text-slate-500 mt-0.5">Cliente seleccionado</p>
            </div>
            <button type="button" onclick="closeReassignModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.users.reassign-trainer') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" id="modal_cliente_id" name="cliente_id" value="">
            <input type="hidden" id="modal_entrenador_origen_id" name="entrenador_origen_id" value="">

            <!-- Entrenador Actual -->
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                <span class="text-slate-400 block text-[11px] uppercase tracking-wider font-semibold mb-0.5">Entrenador Actual</span>
                <span id="currentTrainerName" class="font-bold text-slate-800">Cargando...</span>
            </div>

            <!-- Nuevo Entrenador Destino -->
            <div>
                <label for="entrenador_destino_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Seleccionar Nuevo Entrenador
                </label>
                <select id="entrenador_destino_id" name="entrenador_destino_id" 
                        class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                    <option value="">-- Sin Entrenador (Desvincular) --</option>
                    @foreach ($trainers as $trainer)
                        <option value="{{ $trainer->id }}">
                            {{ $trainer->name }} ({{ $trainer->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1.5">Selecciona un entrenador para asignarlo o elige "Sin Entrenador" para desvincular al cliente.</p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeReassignModal()" 
                        class="px-4 py-2.5 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Confirmar Reasignación
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openReassignModal(clientId, clientName, currentTrainerId, currentTrainerName) {
        document.getElementById('modal_cliente_id').value = clientId;
        document.getElementById('modal_entrenador_origen_id').value = currentTrainerId !== null ? currentTrainerId : '';
        document.getElementById('reassignClientLabel').textContent = 'Cliente: ' + clientName;
        document.getElementById('currentTrainerName').textContent = currentTrainerName;

        // Resetear y deshabilitar en el selector la opción del entrenador actual si existe
        const selectDestino = document.getElementById('entrenador_destino_id');
        selectDestino.value = currentTrainerId !== null ? '' : '';
        for (let i = 0; i < selectDestino.options.length; i++) {
            const opt = selectDestino.options[i];
            if (!opt.value) {
                // Opción "Sin Entrenador"
                if (currentTrainerId === null) {
                    opt.textContent = '-- Sin Entrenador (Actual) --';
                } else {
                    opt.textContent = '-- Sin Entrenador (Desvincular) --';
                }
                continue;
            }

            if (currentTrainerId !== null && opt.value == currentTrainerId) {
                opt.disabled = true;
                opt.textContent = opt.textContent.replace(' (Actual)', '') + ' (Actual)';
            } else {
                opt.disabled = false;
                opt.textContent = opt.textContent.replace(' (Actual)', '');
            }
        }

        document.getElementById('reassignModal').classList.remove('hidden');
    }

    function closeReassignModal() {
        document.getElementById('reassignModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeReassignModal();
        }
    });
</script>
@endsection

