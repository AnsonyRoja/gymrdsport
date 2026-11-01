@extends('layouts.admin')

@section('title', 'Gestión de Métodos de Pago | Gym RDSport')

@section('content')
<div class="space-y-6">

    <!-- Header & Acciones -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-900 text-white uppercase tracking-wider">
                    Configuración de Caja
                </span>
                <span class="text-xs font-semibold text-slate-400">RFC-02 & Transacciones</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">
                Métodos de Pago
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Administre las pasarelas, cuentas bancarias, criptoactivos (USDT) y medios en efectivo aceptados en caja.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 font-semibold text-xs transition-all shadow-2xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ir a Pagos y Facturación
            </a>

            <button type="button" onclick="abrirModalCrear()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm hover:shadow transition-all">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Método de Pago
            </button>
        </div>
    </div>

    <!-- Alertas Flash -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3 shadow-2xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold space-y-1 shadow-2xs">
            <div class="flex items-center gap-2 font-bold">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Revise los campos requeridos:
            </div>
            <ul class="list-disc list-inside text-[11px] text-red-700 pl-2">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200/90 p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Métodos</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalMetodos }}</p>
                <span class="text-[11px] text-slate-500 font-medium">Configurados en el sistema</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/90 p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Métodos Activos</p>
                <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $metodosActivos }}</p>
                <span class="text-[11px] text-emerald-700 font-medium">Habilitados en caja y cobro</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/90 p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">USD & Cripto (USDT)</p>
                <p class="text-2xl font-extrabold text-teal-600 mt-1">{{ $metodosUsdCripto }}</p>
                <span class="text-[11px] text-teal-700 font-medium">Divisas, Binance & Zelle</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/90 p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">En Bolívares (VES)</p>
                <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $metodosVes }}</p>
                <span class="text-[11px] text-blue-700 font-medium">Pago Móvil, Transferencias, POS</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="bg-white rounded-xl border border-slate-200/90 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.payment-methods.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="relative sm:col-span-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar método o cuenta..." 
                       class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div>
                <select name="tipo" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="">Todas las Categorías</option>
                    <option value="cripto" {{ request('tipo') === 'cripto' ? 'selected' : '' }}>Criptomonedas (USDT)</option>
                    <option value="bancario" {{ request('tipo') === 'bancario' ? 'selected' : '' }}>Bancario (Pago Móvil / Transferencia)</option>
                    <option value="efectivo" {{ request('tipo') === 'efectivo' ? 'selected' : '' }}>Efectivo (USD / Bs)</option>
                    <option value="digital" {{ request('tipo') === 'digital' ? 'selected' : '' }}>Digital (Zelle / Billeteras)</option>
                </select>
            </div>

            <div>
                <select name="moneda" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="">Todas las Monedas</option>
                    <option value="USDT" {{ request('moneda') === 'USDT' ? 'selected' : '' }}>USDT (Tether)</option>
                    <option value="USD" {{ request('moneda') === 'USD' ? 'selected' : '' }}>USD ($ Dólar)</option>
                    <option value="VES" {{ request('moneda') === 'VES' ? 'selected' : '' }}>VES (Bolívares)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <select name="estado" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                    <option value="">Todos los Estados</option>
                    <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                    <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                </select>
                <button type="submit" class="px-3.5 py-2 rounded-lg bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition-colors">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabla de Métodos de Pago -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/90 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Método / Canal</th>
                        <th class="py-3 px-4">Categoría</th>
                        <th class="py-3 px-4">Moneda</th>
                        <th class="py-3 px-4">Requiere Ref / TxID</th>
                        <th class="py-3 px-4">Datos de Pago / Cuenta</th>
                        <th class="py-3 px-4">Estado</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($paymentMethods as $pm)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    @if($pm->isCripto())
                                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                    @elseif($pm->moneda === 'USD')
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    @endif
                                    {{ $pm->nombre }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono">código: {{ $pm->codigo }}</div>
                            </td>

                            <td class="py-3.5 px-4 capitalize font-semibold text-slate-700">
                                @if($pm->tipo === 'cripto')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-800 border border-teal-200">
                                        Criptoactivo
                                    </span>
                                @elseif($pm->tipo === 'bancario')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                        Bancario
                                    </span>
                                @elseif($pm->tipo === 'efectivo')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        Efectivo en Caja
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-800 border border-purple-200">
                                        Digital / Wallet
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pm->badge_class }} border">
                                    {{ $pm->moneda }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                @if($pm->requiere_referencia)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-700">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Obligatorio
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] text-slate-400">
                                        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                        Opcional / Directo
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="text-[11px] text-slate-600 truncate" title="{{ $pm->instrucciones }}">
                                    {{ $pm->instrucciones ?: 'Sin datos específicos configurados.' }}
                                </p>
                            </td>

                            <td class="py-3.5 px-4">
                                <form action="{{ route('admin.payment-methods.toggle', $pm) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-bold transition-all {{ $pm->activo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $pm->activo ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $pm->activo ? 'Activo' : 'Pausado' }}
                                    </button>
                                </form>
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button" 
                                            onclick='abrirModalEditar(@json($pm))'
                                            class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition-colors">
                                        Editar
                                    </button>

                                    <form action="{{ route('admin.payment-methods.destroy', $pm) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar o desactivar este método de pago?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-[11px] transition-colors">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                No se encontraron métodos de pago registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Crear Nuevo Método de Pago -->
<div id="modalCrear" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in duration-150">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Registrar Método de Pago</h3>
                <p class="text-xs text-slate-400">Habilite un nuevo canal de recepción para órdenes y cobranzas.</p>
            </div>
            <button type="button" onclick="cerrarModalCrear()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.payment-methods.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nombre Comercial del Método *</label>
                <input type="text" name="nombre" required placeholder="Ej: Binance Pay (USDT), Zinli, Pago Móvil Banesco" 
                       class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Categoría *</label>
                    <select name="tipo" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                        <option value="cripto">Criptoactivo (USDT/Binance)</option>
                        <option value="bancario">Bancario (Pago Móvil / Transf)</option>
                        <option value="efectivo">Efectivo Físico</option>
                        <option value="digital">Billetera Digital / Zelle</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Moneda Principal *</label>
                    <select name="moneda" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                        <option value="USDT">USDT (Tether Cripto)</option>
                        <option value="USD">USD (Dólares)</option>
                        <option value="VES">VES (Bolívares)</option>
                        <option value="MIXTO">MIXTO (Múltiples divisas)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Código Identificador (Opcional)</label>
                    <input type="text" name="codigo" placeholder="Ej: binance_usdt" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Prioridad / Orden en Caja</label>
                    <input type="number" name="orden" value="0" min="0" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Instrucciones / Datos de la Cuenta o Wallet</label>
                <textarea name="instrucciones" rows="3" placeholder="Ej: Datos de banco, RIF, teléfono de pago móvil, o dirección de wallet TRC20 / Pay ID..." 
                          class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none"></textarea>
            </div>

            <div class="space-y-2 pt-1 border-t border-slate-100">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="requiere_referencia" value="1" checked class="rounded text-slate-900 focus:ring-slate-900">
                    <span class="text-xs font-semibold text-slate-700">Exigir número de referencia / Hash de transacción</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="activo" value="1" checked class="rounded text-slate-900 focus:ring-slate-900">
                    <span class="text-xs font-semibold text-slate-700">Habilitar inmediatamente para registro de cobros</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="cerrarModalCrear()" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Guardar Método de Pago
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Método de Pago -->
<div id="modalEditar" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in duration-150">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Editar Método de Pago</h3>
                <p class="text-xs text-slate-400">Modifique los datos bancarios o parámetros de cobro.</p>
            </div>
            <button type="button" onclick="cerrarModalEditar()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formEditar" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nombre Comercial del Método *</label>
                <input type="text" name="nombre" id="editNombre" required 
                       class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Categoría *</label>
                    <select name="tipo" id="editTipo" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                        <option value="cripto">Criptoactivo (USDT/Binance)</option>
                        <option value="bancario">Bancario (Pago Móvil / Transf)</option>
                        <option value="efectivo">Efectivo Físico</option>
                        <option value="digital">Billetera Digital / Zelle</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Moneda Principal *</label>
                    <select name="moneda" id="editMoneda" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                        <option value="USDT">USDT (Tether Cripto)</option>
                        <option value="USD">USD (Dólares)</option>
                        <option value="VES">VES (Bolívares)</option>
                        <option value="MIXTO">MIXTO (Múltiples divisas)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Código Identificador *</label>
                    <input type="text" name="codigo" id="editCodigo" required 
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Prioridad / Orden en Caja</label>
                    <input type="number" name="orden" id="editOrden" min="0" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Instrucciones / Datos de la Cuenta o Wallet</label>
                <textarea name="instrucciones" id="editInstrucciones" rows="3" 
                          class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-slate-900 focus:outline-none"></textarea>
            </div>

            <div class="space-y-2 pt-1 border-t border-slate-100">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="requiere_referencia" id="editRequiereRef" value="1" class="rounded text-slate-900 focus:ring-slate-900">
                    <span class="text-xs font-semibold text-slate-700">Exigir número de referencia / Hash de transacción</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="activo" id="editActivo" value="1" class="rounded text-slate-900 focus:ring-slate-900">
                    <span class="text-xs font-semibold text-slate-700">Habilitar inmediatamente para registro de cobros</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="cerrarModalEditar()" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalCrear() {
        document.getElementById('modalCrear').classList.remove('hidden');
    }

    function cerrarModalCrear() {
        document.getElementById('modalCrear').classList.add('hidden');
    }

    function abrirModalEditar(pm) {
        document.getElementById('formEditar').action = `/admin/payment-methods/${pm.id}`;
        document.getElementById('editNombre').value = pm.nombre;
        document.getElementById('editCodigo').value = pm.codigo;
        document.getElementById('editTipo').value = pm.tipo;
        document.getElementById('editMoneda').value = pm.moneda;
        document.getElementById('editOrden').value = pm.orden ?? 0;
        document.getElementById('editInstrucciones').value = pm.instrucciones ?? '';
        document.getElementById('editRequiereRef').checked = Boolean(pm.requiere_referencia);
        document.getElementById('editActivo').checked = Boolean(pm.activo);

        document.getElementById('modalEditar').classList.remove('hidden');
    }

    function cerrarModalEditar() {
        document.getElementById('modalEditar').classList.add('hidden');
    }

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalCrear();
            cerrarModalEditar();
        }
    });
</script>
@endsection
