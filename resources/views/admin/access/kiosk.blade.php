@extends('layouts.admin')

@section('title', 'Kiosco de Acceso y Marcaje')
@section('page-title', 'Control de Acceso y Verificación')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Encabezado con Indicador de Sistema de Torniquete -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Control de Acceso y Marcaje en Tiempo Real</h1>
            <p class="text-xs text-slate-500 mt-1">Verificación automática de membresías activas, inhabilitación por morosidad y registro de turnos de personal.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                Torniquete / Lector En Línea
            </span>
        </div>
    </div>

    <!-- Panel de Escaneo Rápido (Estilo Kiosco de Control de Acceso) -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-6 sm:p-10 text-white shadow-xl border border-slate-700/60">
        <div class="max-w-2xl mx-auto text-center space-y-6">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-slate-200 border border-white/10 mb-3">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Punto de Acceso Físico / Torniquete
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Escanear o Ingresar Identificador</h2>
                <p class="text-xs sm:text-sm text-slate-300 mt-2">
                    Ingresa el Número de Cédula, Correo Electrónico o ID del atleta o entrenador para validar su acceso.
                </p>
            </div>

            <form id="formKiosco" action="{{ route('admin.access.scan') }}" method="POST" class="space-y-4">
                @csrf
                <div class="relative max-w-lg mx-auto">
                    <input type="text" 
                           name="identificador" 
                           id="inputIdentificador" 
                           required 
                           autofocus
                           autocomplete="off"
                           placeholder="Ej: V-25678901 o test@example.com o ID 6..." 
                           class="w-full px-5 py-4 pl-12 rounded-2xl bg-white/10 border-2 border-white/20 text-white placeholder-slate-400 text-base font-semibold focus:outline-none focus:border-emerald-400 focus:bg-white/15 transition-all text-center tracking-wider">
                    <svg class="w-6 h-6 text-slate-400 absolute left-4 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>

                <div class="flex items-center justify-center gap-3">
                    <button type="submit" 
                            id="btnVerificar"
                            class="px-8 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-sm transition-all shadow-lg hover:shadow-emerald-500/20 flex items-center gap-2">
                        <span>Verificar y Marcar Acceso</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Tarjeta de Resultado Dinámico en Pantalla (Interactivo) -->
            <div id="pantallaResultado" class="hidden transition-all duration-300 rounded-2xl p-6 text-left max-w-lg mx-auto border">
                <div class="flex items-start gap-4">
                    <div id="iconoResultado" class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 font-bold"></div>
                    <div class="flex-1">
                        <h4 id="tituloResultado" class="text-base font-bold text-white"></h4>
                        <p id="mensajeResultado" class="text-xs text-slate-200 mt-1 leading-relaxed"></p>
                        <div id="metaResultado" class="mt-3 pt-3 border-t border-white/10 text-[11px] text-slate-300 flex flex-wrap gap-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas del Día -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-sm">
            <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Total Accesos Hoy</span>
            <h3 class="text-2xl font-extrabold text-slate-900 mt-2">{{ $accesosHoy->total() }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Registros de asistencia del día</p>
        </div>

        <div class="bg-white rounded-xl border border-emerald-200/90 p-5 shadow-sm bg-emerald-50/20">
            <span class="text-[11px] font-bold tracking-wider uppercase text-emerald-700">Accesos Autorizados</span>
            <h3 class="text-2xl font-extrabold text-emerald-800 mt-2">{{ $totalAutorizados }}</h3>
            <p class="text-[11px] text-emerald-600 mt-1">Membresías al día y personal puntual</p>
        </div>

        <div class="bg-white rounded-xl border border-red-200/90 p-5 shadow-sm bg-red-50/20">
            <span class="text-[11px] font-bold tracking-wider uppercase text-red-700">Rechazos por Morosidad</span>
            <h3 class="text-2xl font-extrabold text-red-800 mt-2">{{ $totalRechazados }}</h3>
            <p class="text-[11px] text-red-600 mt-1">Membresía expirada sin pago registrado</p>
        </div>
    </div>

    <!-- Registro en Vivo de Asistencias Hoy -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Bitácora de Accesos de Hoy (Log_Asistencia)</h2>
            <span class="text-xs text-slate-400">{{ now()->translatedFormat('d F, Y') }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-4">Hora</th>
                        <th class="py-3 px-4">Usuario</th>
                        <th class="py-3 px-4">Rol</th>
                        <th class="py-3 px-4">Tipo Registro</th>
                        <th class="py-3 px-4">Resultado Acceso</th>
                        <th class="py-3 px-4">Estatus</th>
                        <th class="py-3 px-4">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($accesosHoy as $acceso)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-slate-800">
                                {{ $acceso->entrada }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">{{ $acceso->user->name ?? 'Usuario Desconocido' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $acceso->user->cedula ?? $acceso->user->email }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="capitalize px-2 py-0.5 rounded-md font-semibold text-[10px] {{ $acceso->user && $acceso->user->isEntrenador() ? 'bg-purple-50 text-purple-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $acceso->user->rol ?? 'Cliente' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-slate-600 font-medium">
                                    {{ $acceso->tipo_registro === 'jornada_entrenador' ? 'Jornada Laboral' : 'Entrada Gimnasio' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($acceso->resultado_acceso === 'autorizado')
                                    <span class="inline-flex items-center gap-1 text-emerald-700 font-bold">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Autorizado
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-red-600 font-bold">
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Rechazado (Moroso)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                    {{ $acceso->estatus === 'Puntual' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                    {{ $acceso->estatus === 'Retardo' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ $acceso->estatus === 'Turno Cumplido' ? 'bg-blue-50 text-blue-700' : '' }}
                                    {{ $acceso->estatus === 'Ausencia' ? 'bg-red-50 text-red-700' : '' }}">
                                    {{ $acceso->estatus }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500">
                                {{ $acceso->observaciones ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                No hay accesos marcados el día de hoy todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $accesosHoy->links() }}
        </div>
    </div>

</div>

<!-- Script para Marcaje Asíncrono Interactivo en Tiempo Real -->
<script>
    document.getElementById('formKiosco').addEventListener('submit', async function(e) {
        e.preventDefault();
        const input = document.getElementById('inputIdentificador');
        const valor = input.value.trim();
        if (!valor) return;

        const pantalla = document.getElementById('pantallaResultado');
        const icono = document.getElementById('iconoResultado');
        const titulo = document.getElementById('tituloResultado');
        const mensaje = document.getElementById('mensajeResultado');
        const meta = document.getElementById('metaResultado');

        try {
            const response = await fetch("{{ route('admin.access.scan') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ identificador: valor })
            });

            const data = await response.json();
            pantalla.classList.remove('hidden');

            if (data.autorizado) {
                pantalla.className = 'transition-all duration-300 rounded-2xl p-6 text-left max-w-lg mx-auto border bg-emerald-950/80 border-emerald-500/50 text-white';
                icono.className = 'w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0 font-bold bg-emerald-500 text-slate-950';
                icono.textContent = '✓';
                titulo.textContent = data.titulo || '¡Acceso Autorizado!';
                mensaje.textContent = data.mensaje;
            } else {
                pantalla.className = 'transition-all duration-300 rounded-2xl p-6 text-left max-w-lg mx-auto border bg-red-950/80 border-red-500/50 text-white';
                icono.className = 'w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0 font-bold bg-red-600 text-white';
                icono.textContent = '✕';
                titulo.textContent = data.titulo || 'Acceso Denegado';
                mensaje.textContent = data.mensaje;
            }

            meta.innerHTML = `
                <span><strong>Hora:</strong> ${data.hora || new Date().toLocaleTimeString()}</span>
                <span><strong>Usuario:</strong> ${data.usuario ? data.usuario.name : 'N/A'}</span>
                <span><strong>Rol:</strong> ${data.usuario ? (data.usuario.rol || 'Cliente') : 'N/A'}</span>
            `;

            // Limpiar input y enfocar
            input.value = '';
            input.focus();

            // Recargar tabla tras 1.5 segundos si fue exitoso
            setTimeout(() => {
                location.reload();
            }, 1800);

        } catch (err) {
            console.error(err);
            pantalla.classList.remove('hidden');
            pantalla.className = 'transition-all duration-300 rounded-2xl p-6 text-left max-w-lg mx-auto border bg-red-950/80 border-red-500/50 text-white';
            icono.textContent = '!';
            titulo.textContent = 'Error de Conexión';
            mensaje.textContent = 'No se pudo conectar con el servicio de control de acceso.';
        }
    });
</script>
@endsection
