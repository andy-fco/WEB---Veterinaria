<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

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

        $hasRole = false;
        foreach ($roles as $role) {
            switch ($role) {
                case 'cliente':
                    if ($user->isClient()) {
                        $hasRole = true;
                    }
                    break;
                case 'empleado':
                    if ($user->isEmployee()) {
                        $hasRole = true;
                    }
                    break;
                case 'administrador':
                    if ($user->isAdmin()) {
                        $hasRole = true;
                    }
                    break;
                default:
                    break;
            }
            if ($hasRole) {
                break; 
            }
        }

        if ($hasRole) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder a esta sección.');
    }



}