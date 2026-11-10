<?php

namespace App\Http\Middleware;

use App\Models\CuentaAcceso;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEsCliente
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() instanceof CuentaAcceso) {
            return response()->json(['message' => 'Acceso restringido al área de cliente'], 403);
        }

        return $next($request);
    }
}
