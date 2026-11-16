<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteDonacionRequest;
use App\Http\Resources\DonacionResource;
use App\Models\CuentaAcceso;
use App\Models\Donacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Donaciones del cliente autenticado (Actividad 4.4).
 * Listado filtrado por su id_usuario en la base de datos y
 * creación con la propiedad asignada automáticamente.
 */
class ClienteDonacionController extends Controller
{
    /**
     * GET /api/v1/cliente/donaciones
     * Lista EXCLUSIVA de donaciones del cliente autenticado (scope BD).
     */
    public function index(Request $request): JsonResponse
    {
        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        $donaciones = Donacion::where('id_usuario', $cuenta->id_usuario)
            ->with('detalles.alimento')
            ->latest('fecha_donacion')
            ->paginate(5);

        return response()->json([
            'donaciones' => DonacionResource::collection($donaciones)->response()->getData(true),
        ], 200);
    }

    /**
     * POST /api/v1/cliente/donaciones
     * Crea una donación vinculada automáticamente al id_usuario del token.
     * El cliente NO puede inyectar el id del propietario en el payload.
     */
    public function store(ClienteDonacionRequest $request): JsonResponse
    {
        $validado = $request->validated();

        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        $donacion = Donacion::create([
            'id_usuario' => $cuenta->id_usuario, // asignación automática (auth)
            'fecha_donacion' => now(),
            'fecha_limite' => $validado['fecha_limite'],
            'ubicacion' => $validado['ubicacion'],
            'estado' => 'Disponible',
            'observaciones' => $validado['observaciones'],
        ]);

        $donacion->detalles()->create([
            'id_alimento' => $validado['id_alimento'],
            'cantidad' => $validado['cantidad'],
            'observaciones' => $validado['observaciones'],
        ]);

        return response()->json([
            'message' => 'Donación registrada correctamente.',
            'donacion' => new DonacionResource($donacion->load('detalles.alimento')),
        ], 201);
    }
}
