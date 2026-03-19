<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if (!$request->user()->activo) {
            \Illuminate\Support\Facades\Auth::logout();
            return redirect()->route('login')->with('error', 'Tu cuenta está desactivada.');
        }

        return $next($request);
    }
}