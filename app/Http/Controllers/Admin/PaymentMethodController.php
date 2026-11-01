<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    /**
     * Muestra la lista y estado de los métodos de pago configurados.
     */
    public function index(Request $request): View
    {
        $query = PaymentMethod::query()->orderBy('orden')->orderBy('nombre');

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('moneda')) {
            $query->where('moneda', $request->moneda);
        }

        if ($request->filled('estado')) {
            $query->where('activo', $request->estado === 'activo');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%")
                  ->orWhere('instrucciones', 'like', "%{$search}%");
            });
        }

        $paymentMethods = $query->get();

        // Métricas de control
        $totalMetodos = PaymentMethod::count();
        $metodosActivos = PaymentMethod::where('activo', true)->count();
        $metodosUsdCripto = PaymentMethod::whereIn('moneda', ['USD', 'USDT'])->count();
        $metodosVes = PaymentMethod::where('moneda', 'VES')->count();

        return view('admin.payment_methods.index', compact(
            'paymentMethods',
            'totalMetodos',
            'metodosActivos',
            'metodosUsdCripto',
            'metodosVes'
        ));
    }

    /**
     * Almacena un nuevo método de pago en el sistema.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'nullable|string|max:50|unique:payment_methods,codigo',
            'tipo' => 'required|in:efectivo,bancario,cripto,digital',
            'moneda' => 'required|in:USD,VES,USDT,MIXTO',
            'instrucciones' => 'nullable|string|max:1000',
            'requiere_referencia' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
            'orden' => 'nullable|integer|min:0',
        ], [
            'nombre.required' => 'El nombre del método de pago es obligatorio.',
            'codigo.unique' => 'Ya existe un método de pago con este código identificador.',
            'tipo.required' => 'Seleccione la categoría del método.',
            'moneda.required' => 'Indique la moneda principal de cobro.',
        ]);

        $codigo = !empty($validated['codigo']) 
            ? Str::slug($validated['codigo'], '_') 
            : Str::slug($validated['nombre'], '_');

        PaymentMethod::create([
            'nombre' => $validated['nombre'],
            'codigo' => $codigo,
            'tipo' => $validated['tipo'],
            'moneda' => $validated['moneda'],
            'instrucciones' => $validated['instrucciones'] ?? null,
            'requiere_referencia' => $request->boolean('requiere_referencia', true),
            'activo' => $request->boolean('activo', true),
            'orden' => (int) ($validated['orden'] ?? 0),
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Método de pago '{$validated['nombre']}' registrado exitosamente.");
    }

    /**
     * Actualiza la información de un método de pago existente.
     */
    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:50|unique:payment_methods,codigo,' . $paymentMethod->id,
            'tipo' => 'required|in:efectivo,bancario,cripto,digital',
            'moneda' => 'required|in:USD,VES,USDT,MIXTO',
            'instrucciones' => 'nullable|string|max:1000',
            'requiere_referencia' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
            'orden' => 'nullable|integer|min:0',
        ], [
            'nombre.required' => 'El nombre del método de pago es obligatorio.',
            'codigo.unique' => 'Este código ya está en uso por otro método de pago.',
        ]);

        $paymentMethod->update([
            'nombre' => $validated['nombre'],
            'codigo' => Str::slug($validated['codigo'], '_'),
            'tipo' => $validated['tipo'],
            'moneda' => $validated['moneda'],
            'instrucciones' => $validated['instrucciones'] ?? null,
            'requiere_referencia' => $request->boolean('requiere_referencia'),
            'activo' => $request->boolean('activo'),
            'orden' => (int) ($validated['orden'] ?? 0),
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Método de pago '{$paymentMethod->nombre}' actualizado con éxito.");
    }

    /**
     * Activa o pausa un método de pago rápidamente.
     */
    public function toggleStatus(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->activo = !$paymentMethod->activo;
        $paymentMethod->save();

        $estado = $paymentMethod->activo ? 'activado' : 'desactivado';
        return redirect()->route('admin.payment-methods.index')
            ->with('success', "El método de pago '{$paymentMethod->nombre}' fue {$estado} para cobros.");
    }

    /**
     * Elimina un método de pago si no tiene pagos históricos asociados.
     */
    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        // Verificar si existen órdenes emitidas con este método
        $ordenesAsociadas = Order::where('metodo_pago', 'like', "%{$paymentMethod->nombre}%")
            ->orWhere('metodo_pago', 'like', "%{$paymentMethod->codigo}%")
            ->count();

        if ($ordenesAsociadas > 0) {
            // Se desactiva para mantener la consistencia histórica de auditoría fiscal
            $paymentMethod->activo = false;
            $paymentMethod->save();

            return redirect()->route('admin.payment-methods.index')
                ->with('success', "El método '{$paymentMethod->nombre}' posee {$ordenesAsociadas} transacciones registradas. Se ha marcado como INACTIVO para proteger los registros contables.");
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Método de pago '{$paymentMethod->nombre}' eliminado permanentemente.");
    }
}
