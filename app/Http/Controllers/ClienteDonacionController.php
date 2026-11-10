<?php

namespace App\Http\Controllers;

use App\Models\CuentaAcceso;
use App\Models\Donacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteDonacionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        $donaciones = Donacion::where('id_usuario', $cuenta->id_usuario)
            ->with('detalles.alimento')
            ->latest('fecha_donacion')
            ->paginate(5);

        return response()->json(['donaciones' => $donaciones], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validado = $request->validate([
            'fecha_limite' => 'required|date|after_or_equal:today',
            'ubicacion' => 'required|string|max:200',
            'observaciones' => 'required|string|max:200',
            'id_alimento' => 'required|exists:alimentos,id_alimento',
            'cantidad' => 'required|numeric|min:0.01|max:99999999',
        ]);

        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        $donacion = Donacion::create([
            'id_usuario' => $cuenta->id_usuario,
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
            'donacion' => $donacion->load('detalles.alimento'),
        ], 201);
    }
}
