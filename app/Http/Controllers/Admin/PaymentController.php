<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membresia;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\PagoMembresiaServicio;
use App\Services\TasaBcvServicio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PagoMembresiaServicio $pagoServicio,
        protected TasaBcvServicio $tasaBcvServicio
    ) {}

    /**
     * Muestra el panel de control de pagos, órdenes y facturación multimoneda (RFC-02 / TRX-01).
     */
    public function index(Request $request): View
    {
        $tasaBcv = $this->tasaBcvServicio->obtenerTasaUsd();

        $query = Order::with(['usuario', 'membresia'])->latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('metodo')) {
            $query->where('metodo_pago', 'like', "%{$request->metodo}%");
        }

        if ($request->filled('tipo_comprobante')) {
            $query->where('tipo_comprobante', $request->tipo_comprobante);
        }

        if ($request->filled('search')) {
            $busqueda = $request->search;
            $query->whereHas('usuario', function ($q) use ($busqueda) {
                $q->where('name', 'like', "%{$busqueda}%")
                  ->orWhere('email', 'like', "%{$busqueda}%")
                  ->orWhere('cedula', 'like', "%{$busqueda}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        // Métricas resumidas
        $totalRecaudadoUsd = Order::where('estado', 'Pagada')->sum('monto_pagado_usd');
        $totalRecaudadoBs = Order::where('estado', 'Pagada')->sum('monto_pagado_bs');
        $ordenesPagadas = Order::where('estado', 'Pagada')->count();
        $ordenesParciales = Order::where('estado', 'Parcial')->count();

        // Datos para modal/formulario de cobro
        $clientes = User::where('rol', 'cliente')->orderBy('name')->get(['id', 'name', 'email', 'cedula']);
        $membresias = Membresia::where('activo', true)->orderBy('costo_base')->get();
        $metodosPago = PaymentMethod::active()->ordered()->get();

        return view('admin.payments.index', compact(
            'orders',
            'tasaBcv',
            'totalRecaudadoUsd',
            'totalRecaudadoBs',
            'ordenesPagadas',
            'ordenesParciales',
            'clientes',
            'membresias',
            'metodosPago'
        ));
    }

    /**
     * TRX-01: Procesa la generación y pago de una orden de membresía a la tasa del día.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'membresia_id' => 'required|exists:membresias,id',
            'monto_pagado_usd' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|max:100',
            'tipo_comprobante' => 'required|in:comprobante,factura',
            'referencia_pago' => 'nullable|string|max:100',
            'descuento_usd' => 'nullable|numeric|min:0',
            'notas' => 'nullable|string|max:500',
        ], [
            'usuario_id.required' => 'Debe seleccionar un cliente.',
            'membresia_id.required' => 'Debe seleccionar un plan de membresía.',
            'monto_pagado_usd.required' => 'Indique el monto pagado.',
            'tipo_comprobante.required' => 'Seleccione el tipo de documento (Factura o Comprobante).',
        ]);

        try {
            $resultado = $this->pagoServicio->generarYProcesarOrden(
                (int) $validated['usuario_id'],
                (int) $validated['membresia_id'],
                (float) $validated['monto_pagado_usd'],
                $validated['metodo_pago'],
                $validated['referencia_pago'] ?? null,
                (float) ($validated['descuento_usd'] ?? 0.0),
                $validated['notas'] ?? null,
                $validated['tipo_comprobante'] ?? 'comprobante'
            );

            $tipoDoc = ($validated['tipo_comprobante'] ?? 'comprobante') === 'factura' ? 'Factura Fiscal' : 'Comprobante de Pago';
            $numDoc = $resultado['orden']->documento_codigo;

            return redirect()->route('admin.payments.index')->with('success', "{$tipoDoc} ({$numDoc}) registrada con éxito. Membresía extendida hasta el {$resultado['fecha_fin']} (Tasa BCV: Bs. {$resultado['tasa_bcv']}/USD).");
        } catch (\Throwable $e) {
            return redirect()->route('admin.payments.index')->withErrors(['payment_error' => 'Error al procesar la orden: ' . $e->getMessage()]);
        }
    }

    /**
     * Muestra la vista imprimible del Comprobante o Factura Fiscal.
     */
    public function showInvoice(Order $order): View
    {
        $order->load(['usuario', 'membresia']);

        return view('admin.payments.invoice', compact('order'));
    }
}
