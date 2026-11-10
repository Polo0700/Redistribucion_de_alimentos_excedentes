<?php

namespace App\Http\Middleware;

use App\Models\Administrador;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() instanceof Administrador) {
            return response()->json(['message' => 'Acceso restringido al área administrativa'], 403);
        }

        return $next($request);
    }
}
