@extends('layouts.admin')

@section('title', 'Control de Asistencia')
@section('page-title', 'Control de Asistencia')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Encabezado con Botón de Marcaje -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Control de Asistencia del Personal</h1>
            <p class="text-xs text-slate-500 mt-1">Registro de check-in / check-out y evaluación automática de puntualidad según el turno.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium bg-white border border-slate-200 text-slate-600 shadow-sm">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Hoy: {{ now()->translatedFormat('d M, Y') }}
            </span>

            <button type="button" onclick="openCheckinModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold tracking-wide transition-all shadow-sm">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Registrar Marcaje</span>
            </button>
        </div>
    </div>

    <!-- Tabla Corporativa Minimalista de Asistencia -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/70 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Personal</th>
                        <th class="py-3.5 px-6">Fecha</th>
                        <th class="py-3.5 px-6">Turno Pactado</th>
                        <th class="py-3.5 px-6">Entrada (Check-in)</th>
                        <th class="py-3.5 px-6">Salida (Check-out)</th>
                        <th class="py-3.5 px-6 text-right">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse ($attendances as $attendance)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Nombre -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200/80 flex items-center justify-center font-bold text-slate-800 text-xs shadow-xs">
                                        {{ strtoupper(substr($attendance->user->name ?? 'NA', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $attendance->user->name ?? 'Usuario Inactivo' }}</div>
                                        <div class="text-[11px] text-slate-400 capitalize">{{ $attendance->user->rol ?? 'personal' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Fecha -->
                            <td class="py-4 px-6 font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($attendance->fecha)->format('d/m/Y') }}
                            </td>

                            <!-- Turno Pactado -->
                            <td class="py-4 px-6 text-slate-500">
                                @if($attendance->hora_turno_inicio)
                                    <span class="inline-flex items-center gap-1 font-medium text-slate-600">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ \Carbon\Carbon::parse($attendance->hora_turno_inicio)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">08:00 AM</span>
                                @endif
                            </td>

                            <!-- Entrada -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ \Carbon\Carbon::parse($attendance->entrada)->format('h:i A') }}
                                </span>
                            </td>

                            <!-- Salida -->
                            <td class="py-4 px-6">
                                @if($attendance->salida)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200/60">
                                        <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($attendance->salida)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        En turno
                                    </span>
                                @endif
                            </td>

                            <!-- Estatus Automático -->
                            <td class="py-4 px-6 text-right">
                                @php
                                    $statusClean = strtolower($attendance->estatus);
                                    $badge = match(true) {
                                        str_contains($statusClean, 'tiempo') || str_contains($statusClean, 'puntual') => 'bg-emerald-50 text-emerald-700 border-emerald-200/70',
                                        str_contains($statusClean, 'retardo') => 'bg-amber-50 text-amber-700 border-amber-200/70',
                                        str_contains($statusClean, 'inasistencia') || str_contains($statusClean, 'ausencia') => 'bg-rose-50 text-rose-700 border-rose-200/70',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $badge }}">
                                    {{ $attendance->estatus }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <p class="font-medium text-slate-700 text-xs">No hay registros de asistencia en la base de datos.</p>
                                    <p class="text-[11px] text-slate-400 mt-1">Usa el botón "Registrar Marcaje" para iniciar el primer turno.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal: Registro de Marcaje (Entrada / Salida) -->
<div id="checkinModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xl w-full max-w-md p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-150">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900">Registrar Marcaje de Asistencia</h3>
                <p class="text-xs text-slate-400 mt-0.5">Control horario de entrada y salida del staff</p>
            </div>
            <button type="button" onclick="closeCheckinModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.attendance.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Tipo de Marcaje (Entrada / Salida) -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Tipo de Marcaje
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-colors has-[:checked]:border-slate-900 has-[:checked]:bg-slate-900 has-[:checked]:text-white">
                        <input type="radio" name="tipo_marcaje" value="entrada" checked onchange="toggleMarcajeFields(this.value)" class="hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="text-xs font-bold">Entrada (Check-in)</span>
                    </label>

                    <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-colors has-[:checked]:border-slate-900 has-[:checked]:bg-slate-900 has-[:checked]:text-white">
                        <input type="radio" name="tipo_marcaje" value="salida" onchange="toggleMarcajeFields(this.value)" class="hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="text-xs font-bold">Salida (Check-out)</span>
                    </label>
                </div>
            </div>

            <!-- Seleccionar Personal -->
            <div>
                <label for="user_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Miembro del Personal
                </label>
                <select id="user_id" name="user_id" required class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                    <option value="">-- Elige a un integrante del staff --</option>
                    @foreach ($staffMembers as $member)
                        <option value="{{ $member->id }}">
                            {{ $member->name }} ({{ ucfirst($member->rol) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bloque de Horarios (Solo para Entrada) -->
            <div id="turnoFields" class="grid grid-cols-2 gap-3">
                <div>
                    <label for="hora_turno" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Hora Turno
                    </label>
                    <input type="time" id="hora_turno" name="hora_turno" value="08:00"
                           class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                    <span class="text-[10px] text-slate-400 mt-1 block">Tolerancia: 10 min</span>
                </div>

                <div>
                    <label for="hora_marcaje" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Hora Marcaje
                    </label>
                    <input type="time" id="hora_marcaje" name="hora_marcaje" value="{{ now()->format('H:i') }}"
                           class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
                    <span class="text-[10px] text-slate-400 mt-1 block">Hora real de llegada</span>
                </div>
            </div>

            <!-- Observaciones -->
            <div>
                <label for="observaciones" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Observaciones (Opcional)
                </label>
                <input type="text" id="observaciones" name="observaciones" placeholder="Ej. Retardo justificado por tráfico o reunión"
                       class="w-full px-3.5 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors">
            </div>

            <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeCheckinModal()" 
                        class="px-4 py-2.5 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Guardar Marcaje
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openCheckinModal() {
        document.getElementById('checkinModal').classList.remove('hidden');
    }
    function closeCheckinModal() {
        document.getElementById('checkinModal').classList.add('hidden');
    }

    function toggleMarcajeFields(tipo) {
        const turnoFields = document.getElementById('turnoFields');
        if (tipo === 'salida') {
            turnoFields.classList.add('hidden');
        } else {
            turnoFields.classList.remove('hidden');
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCheckinModal();
        }
    });
</script>
@endsection
