<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonacionRequest;
use App\Http\Resources\DonacionResource;
use App\Models\Donacion;
use Illuminate\Http\JsonResponse;

class DonacionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return DonacionResource::collection(Donacion::with('usuario')->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVA DONACIÓN
    |--------------------------------------------------------------------------
    */
    public function store(DonacionRequest $request): JsonResponse
    {
        $donacion = Donacion::create($request->validated());

        return (new DonacionResource($donacion))
            ->additional(['message' => 'Donacion creada correctamente'])
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
        return (new DonacionResource(Donacion::with('usuario')->findOrFail($id)))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR DONACIÓN
    |--------------------------------------------------------------------------
    */
    public function update(DonacionRequest $request, $id): JsonResponse
    {
        $donacion = Donacion::findOrFail($id);
        $donacion->update($request->validated());

        return (new DonacionResource($donacion))
            ->additional(['message' => 'Donación actualizada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id): JsonResponse
    {
        $donacion = Donacion::findOrFail($id);
        $donacion->delete();

        return response()->json([
            'message' => 'Donación eliminada correctamente.',
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        return DonacionResource::collection(Donacion::onlyTrashed()->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id): JsonResponse
    {
        $donacion = Donacion::onlyTrashed()->findOrFail($id);
        $donacion->restore();

        return (new DonacionResource($donacion))
            ->additional(['message' => 'Donación restaurada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id): JsonResponse
    {
        $donacion = Donacion::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $donacion->trashed()) {
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.',
            ], 422);
        }

        // validación de relaciones: si otra tabla la usa, se cancela
        if ($donacion->detalles()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: la donación tiene detalles registrados.',
            ], 422);
        }

        $donacion->forceDelete();

        return response()->json([
            'message' => 'Donación eliminada definitivamente.',
        ], 200);
    }
}
