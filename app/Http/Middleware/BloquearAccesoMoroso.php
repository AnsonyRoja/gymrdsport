<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BloquearAccesoMoroso
{
    /**
     * RFC-01: Bloqueo automático de acceso a servicios y rutinas para usuarios con pagos pendientes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isCliente() && $user->isMoroso()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'codigo' => 'CLIENTE_MOROSO',
                    'message' => 'Acceso inhabilitado por morosidad en el pago de membresía. Por favor regularice su pago.',
                ], 403);
            }

            return redirect()->route('dashboard')->with('moroso_alerta', 'Tu membresía se encuentra vencida o en estado moroso. Por favor regulariza tu pago en recepción o mediante el módulo de pagos para desbloquear todos los servicios.');
        }

        return $next($request);
    }
}
