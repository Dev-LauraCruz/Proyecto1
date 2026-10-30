<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
{
    /**
     * Uso en rutas: RolMiddleware::class.':admin'  o  RolMiddleware::class.':cliente'
     */
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        if (! $request->user() || $request->user()->rol !== $rol) {
            abort(403, 'No tienes permiso para entrar a esta sección.');
        }

        return $next($request);
    }
}
