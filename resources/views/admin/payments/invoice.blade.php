<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $order->tipo_comprobante === 'factura' ? 'Factura Fiscal' : 'Comprobante de Pago' }} - {{ $order->numero_comprobante ?? ('#'.$order->id) }} | GYM RD SPORT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .invoice-box {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased p-4 sm:p-8">

    <!-- Barra de Control Superior (No imprimible) -->
    <div class="max-w-3xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Regresar a Pagos</span>
        </a>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-sm transition-all">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Imprimir / Descargar PDF</span>
            </button>
        </div>
    </div>

    <!-- Contenedor del Documento -->
    <div class="invoice-box max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200/80 p-8 sm:p-10">

        <!-- Cabecera -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-6 pb-6 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-slate-900 text-emerald-400 font-extrabold flex items-center justify-center text-sm tracking-wider">
                        RD
                    </div>
                    <span class="text-xl font-black tracking-tight text-slate-900">GYM RD SPORT</span>
                </div>
                <div class="mt-2 text-xs text-slate-500 space-y-0.5">
                    <p class="font-bold text-slate-700">GYM RD SPORT, C.A.</p>
                    <p>RIF: J-50123894-0</p>
                    <p>Av. Principal, Edificio RD Sport, Piso 1</p>
                    <p>Maturín, Edo. Monagas - Venezuela</p>
                    <p>Tel: +58 412-1234567 | info@gymrdsport.com</p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="inline-block px-3 py-1 rounded-md text-xs font-extrabold uppercase tracking-wider {{ $order->tipo_comprobante === 'factura' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                    {{ $order->tipo_comprobante === 'factura' ? 'Factura Fiscal' : 'Comprobante de Cobro' }}
                </span>
                <div class="mt-2 text-2xl font-black font-mono tracking-tight text-slate-900">
                    {{ $order->documento_codigo }}
                </div>
                <div class="text-xs text-slate-500 mt-1">
                    <p><span class="font-bold text-slate-700">Fecha:</span> {{ \Carbon\Carbon::parse($order->fecha_pago)->translatedFormat('d \d\e F, Y') }}</p>
                    @if($order->tipo_comprobante === 'factura' && $order->numero_control)
                        <p><span class="font-bold text-indigo-700">Nº Control Fiscal:</span> <span class="font-mono font-bold">{{ $order->numero_control }}</span></p>
                    @endif
                    <p><span class="font-bold text-slate-700">Control Interno:</span> #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        <!-- Información del Cliente y Pago -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-slate-200 text-xs">
            <div>
                <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Datos del Cliente</h4>
                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-100 space-y-1">
                    <p class="font-bold text-slate-900 text-sm">{{ $order->usuario->name ?? 'Consumidor Final' }}</p>
                    <p class="text-slate-600"><span class="font-semibold text-slate-700">C.I. / RIF:</span> {{ $order->usuario->cedula ?? 'No registrada' }}</p>
                    <p class="text-slate-600"><span class="font-semibold text-slate-700">Email:</span> {{ $order->usuario->email ?? 'N/A' }}</p>
                    <p class="text-slate-600"><span class="font-semibold text-slate-700">Teléfono:</span> {{ $order->usuario->telefono ?? 'N/A' }}</p>
                </div>
            </div>

            <div>
                <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Detalles de la Transacción</h4>
                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-100 space-y-1">
                    <p class="text-slate-600"><span class="font-semibold text-slate-700">Método de Pago:</span> <span class="font-bold text-slate-900">{{ $order->metodo_pago }}</span></p>
                    @if($order->referencia_pago)
                        <p class="text-slate-600 font-mono text-[11px]"><span class="font-semibold text-slate-700 font-sans">Referencia / TxID:</span> {{ $order->referencia_pago }}</p>
                    @endif
                    <p class="text-slate-600"><span class="font-semibold text-slate-700">Tasa Oficial BCV:</span> <span class="font-bold text-emerald-700 font-mono">Bs. {{ number_format($order->tasa_bcv, 2) }} / USD</span></p>
                    <p class="text-slate-600"><span class="font-semibold text-slate-700">Estado:</span> 
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ $order->estado === 'Pagada' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $order->estado }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabla de Conceptos Facturados -->
        <div class="py-6">
            <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-3">Detalle del Servicio</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-[11px] font-bold uppercase text-slate-500">
                            <th class="py-2.5 px-3">Descripción</th>
                            <th class="py-2.5 px-3 text-center">Duración</th>
                            <th class="py-2.5 px-3 text-right">Precio USD</th>
                            <th class="py-2.5 px-3 text-right">Monto Bs (BCV)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-3 px-3">
                                <div class="font-bold text-slate-900">{{ $order->membresia->nombre ?? 'Membresía Gimnasio' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $order->membresia->descripcion ?? 'Acceso a instalaciones deportivas y asesoría de entrenamiento.' }}</div>
                                @if($order->notas)
                                    <div class="text-[10px] text-slate-400 mt-0.5">Nota: {{ $order->notas }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center font-medium text-slate-700">
                                {{ $order->membresia->duracion_dias ?? 30 }} días
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-slate-900">
                                ${{ number_format($order->monto_usd, 2) }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-slate-900">
                                Bs. {{ number_format($order->monto_bs, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Resumen y Totales -->
        <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start gap-6">
            <div class="text-xs text-slate-400 max-w-sm space-y-1">
                <p class="font-semibold text-slate-600">Normativa Cambiaria y Fiscal:</p>
                <p>Monto en bolívares calculado en cumplimiento con las regulaciones del Banco Central de Venezuela (BCV) a la fecha de la transacción.</p>
                <p class="text-[10px]">Documento generado automáticamente por el sistema de control GYM RD SPORT.</p>
            </div>

            <div class="w-full sm:w-64 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal USD:</span>
                    <span class="font-mono font-bold text-slate-800">${{ number_format($order->monto_usd, 2) }}</span>
                </div>
                @if($order->descuento_usd > 0)
                    <div class="flex justify-between text-emerald-700">
                        <span>Descuento aplicado:</span>
                        <span class="font-mono font-bold">-${{ number_format($order->descuento_usd, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-slate-600">
                    <span>Total USD Pagado:</span>
                    <span class="font-mono font-extrabold text-slate-900">${{ number_format($order->monto_pagado_usd, 2) }}</span>
                </div>
                <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                    <span class="font-bold text-slate-800 text-sm">Total Bolívares:</span>
                    <span class="font-mono font-black text-emerald-700 text-base">Bs. {{ number_format($order->monto_pagado_bs, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Pie de firma y sello -->
        <div class="mt-12 pt-8 border-t border-dashed border-slate-200 grid grid-cols-2 gap-8 text-center text-xs text-slate-400">
            <div>
                <div class="h-12 border-b border-slate-300 w-48 mx-auto"></div>
                <p class="mt-2 font-medium text-slate-600">Firma Autorizada / Caja</p>
                <p class="text-[10px] text-slate-400">GYM RD SPORT, C.A.</p>
            </div>
            <div>
                <div class="h-12 border-b border-slate-300 w-48 mx-auto"></div>
                <p class="mt-2 font-medium text-slate-600">Firma del Cliente</p>
                <p class="text-[10px] text-slate-400">{{ $order->usuario->name ?? 'Cliente' }}</p>
            </div>
        </div>

    </div>

</body>
</html>
