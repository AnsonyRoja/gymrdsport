<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\ControlAccesoServicio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccessKioskController extends Controller
{
    public function __construct(
        protected ControlAccesoServicio $controlAccesoServicio
    ) {}

    /**
     * Muestra la pantalla interactiva de Kiosco / Control de Acceso y Marcaje (TRX-03 / RFC-04).
     */
    public function index(): View
    {
        $hoy = now()->toDateString();

        // Registros de asistencia y accesos del día
        $accesosHoy = Attendance::with('user')
            ->where('fecha', $hoy)
            ->latest()
            ->paginate(20);

        $totalAutorizados = Attendance::where('fecha', $hoy)
            ->where('resultado_acceso', 'autorizado')
            ->count();

        $totalRechazados = Attendance::where('fecha', $hoy)
            ->where('resultado_acceso', 'rechazado_moroso')
            ->count();

        return view('admin.access.kiosk', compact('accesosHoy', 'totalAutorizados', 'totalRechazados'));
    }

    /**
     * TRX-03: Procesa el marcaje / escaneo de credencial o documento.
     */
    public function scan(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'identificador' => 'required|string|max:255',
            'hora_turno' => 'nullable|string|max:10',
        ]);

        $resultado = $this->controlAccesoServicio->procesarMarcaje(
            $request->input('identificador'),
            $request->input('hora_turno', '08:00')
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($resultado, $resultado['autorizado'] ? 200 : 422);
        }

        if ($resultado['autorizado']) {
            return redirect()->route('admin.access.kiosk')->with('success', $resultado['mensaje']);
        } else {
            return redirect()->route('admin.access.kiosk')->withErrors(['scan_error' => $resultado['mensaje']]);
        }
    }
}
