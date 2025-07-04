<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  // Permite pasar múltiples roles como argumentos
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // No autenticado, redirige al login
        }

        $user = Auth::user();

        // Es CRUCIAL verificar si $user->role es null.
        // Si un usuario no tiene un rol_id asignado, $user->role será null.
        if (!$user->role) {
            abort(403, 'Acceso denegado: El usuario no tiene un rol asignado.');
        }

        // Verifica si el nombre del rol del usuario coincide con alguno de los roles permitidos
        foreach ($roles as $role) {
            if ($user->role->nombre === $role) { // <--- ¡ESTA ES LA LÍNEA CLAVE!
                return $next($request); // Permite el acceso
            }
        }


        abort(403, 'Acceso no autorizado para este rol.');
    }



}