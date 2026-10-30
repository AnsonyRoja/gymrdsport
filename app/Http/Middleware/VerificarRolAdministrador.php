<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRolAdministrador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Verificar si el usuario está autenticado y si tiene el rol 'admin'
        if (!$user || $user->rol !== 'admin') {
            abort(Response::HTTP_FORBIDDEN, 'Acceso denegado: Se requieren permisos de administrador.');
        }

        return $next($request);
    }
}
