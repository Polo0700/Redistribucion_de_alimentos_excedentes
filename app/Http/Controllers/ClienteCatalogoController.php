<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlimentoResource;
use App\Models\Alimento;
use Illuminate\Http\JsonResponse;

/**
 * Catálogo público del cliente (Actividad 4.4).
 * Consulta abierta de los alimentos disponibles: no requiere token
 * y usa AlimentoResource para la salida estandarizada.
 */
class ClienteCatalogoController extends Controller
{
    /**
     * GET /api/v1/cliente/catalogo
     * Catálogo público de alimentos activos.
     */
    public function index(): JsonResponse
    {
        $alimentos = Alimento::with('categoria')
            ->where('estado', 1)
            ->paginate(10);

        return response()->json([
            'catalogo' => AlimentoResource::collection($alimentos)->response()->getData(true),
        ], 200);
    }

    /**
     * GET /api/v1/cliente/catalogo/{id}
     * Detalle público de un alimento del catálogo.
     */
    public function show(int $id): JsonResponse
    {
        $alimento = Alimento::with('categoria')
            ->where('estado', 1)
            ->findOrFail($id);

        return response()->json([
            'alimento' => new AlimentoResource($alimento),
        ], 200);
    }
}
