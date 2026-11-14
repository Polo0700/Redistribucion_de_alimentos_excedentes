<?php

namespace App\Http\Controllers;

use App\Http\Requests\CuentaAccesoRequest;
use App\Http\Resources\CuentaAccesoResource;
use App\Models\CuentaAcceso;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class CuentaAccesoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return CuentaAccesoResource::collection(CuentaAcceso::with('usuario')->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVA CUENTA DE ACCESO
    |--------------------------------------------------------------------------
    */
    public function store(CuentaAccesoRequest $request): JsonResponse
    {
        $validado = $request->validated();

        // toma la contraseña y la convierte a hash antes de guardarla
        $validado['contrasena_hash'] = Hash::make($validado['contrasena']);

        // quita la contraseña sin hashear
        unset($validado['contrasena']);

        $cuenta = CuentaAcceso::create($validado);

        return (new CuentaAccesoResource($cuenta))
            ->additional(['message' => 'Cuenta de acceso creada correctamente'])
            ->response()
            ->setStatusCode(201);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id): JsonResponse
    {
        return (new CuentaAccesoResource(CuentaAcceso::with('usuario')->findOrFail($id)))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR CUENTA DE ACCESO
    |--------------------------------------------------------------------------
    */
    public function update(CuentaAccesoRequest $request, $id): JsonResponse
    {
        $cuenta = CuentaAcceso::findOrFail($id);

        $validado = $request->validated();

        // si se envía una nueva contraseña, se guarda hasheada
        if (! empty($validado['contrasena'])) {
            $validado['contrasena_hash'] = Hash::make($validado['contrasena']);
        }

        unset($validado['contrasena']);

        $cuenta->update($validado);

        return (new CuentaAccesoResource($cuenta))
            ->additional(['message' => 'Cuenta de acceso actualizada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id): JsonResponse
    {
        $cuenta = CuentaAcceso::findOrFail($id);
        $cuenta->delete();

        return response()->json([
            'message' => 'Cuenta de acceso eliminada correctamente.',
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        return CuentaAccesoResource::collection(CuentaAcceso::onlyTrashed()->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id): JsonResponse
    {
        $cuenta = CuentaAcceso::onlyTrashed()->findOrFail($id);
        $cuenta->restore();

        return (new CuentaAccesoResource($cuenta))
            ->additional(['message' => 'Cuenta de acceso restaurada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id): JsonResponse
    {
        $cuenta = CuentaAcceso::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $cuenta->trashed()) {
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.',
            ], 422);
        }

        // sin tablas dependientes: se elimina directamente
        $cuenta->forceDelete();

        return response()->json([
            'message' => 'Cuenta de acceso eliminada definitivamente.',
        ], 200);
    }
}
