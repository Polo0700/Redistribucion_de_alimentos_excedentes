<?php

namespace App\Http\Controllers;

use App\Models\CuentaAcceso;
use App\Models\Solicitud;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteSolicitudController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        $solicitudes = Solicitud::where('id_usuario', $cuenta->id_usuario)
            ->with('detalles.alimento')
            ->latest('fecha_solicitud')
            ->paginate(5);

        return response()->json(['solicitudes' => $solicitudes], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validado = $request->validate([
            'direccion_entrega' => 'required|string|max:200',
            'observaciones' => 'nullable|string|max:250',
            'id_alimento' => 'required|exists:alimentos,id_alimento',
            'cantidad' => 'required|numeric|min:0.01|max:99999999',
        ]);

        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        $solicitud = Solicitud::create([
            'id_usuario' => $cuenta->id_usuario,
            'direccion_entrega' => $validado['direccion_entrega'],
            'observaciones' => $validado['observaciones'] ?? null,
            'fecha_solicitud' => now(),
            'estado' => 'pendiente',
        ]);

        $solicitud->detalles()->create([
            'id_alimento' => $validado['id_alimento'],
            'cantidad' => $validado['cantidad'],
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Solicitud registrada correctamente.',
            'solicitud' => $solicitud->load('detalles.alimento'),
        ], 201);
    }
}
