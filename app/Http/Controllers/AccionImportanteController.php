<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccionImportanteRequest;
use App\Http\Resources\AccionImportanteResource;
use App\Models\AccionImportante;
use Illuminate\Http\JsonResponse;

class AccionImportanteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return AccionImportanteResource::collection(AccionImportante::with('usuario')->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVA ACCIÓN IMPORTANTE
    |--------------------------------------------------------------------------
    */
    public function store(AccionImportanteRequest $request): JsonResponse
    {
        $accion = AccionImportante::create($request->validated());

        return (new AccionImportanteResource($accion))
            ->additional(['message' => 'Accion importante creada correctamente'])
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
        return (new AccionImportanteResource(AccionImportante::with('usuario')->findOrFail($id)))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR ACCIÓN IMPORTANTE
    |--------------------------------------------------------------------------
    */
    public function update(AccionImportanteRequest $request, $id): JsonResponse
    {
        $accion = AccionImportante::findOrFail($id);
        $accion->update($request->validated());

        return (new AccionImportanteResource($accion))
            ->additional(['message' => 'Acción actualizada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id): JsonResponse
    {
        $accion = AccionImportante::findOrFail($id);
        $accion->delete();

        return response()->json([
            'message' => 'Acción importante eliminada correctamente.',
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        return AccionImportanteResource::collection(AccionImportante::onlyTrashed()->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id): JsonResponse
    {
        $accion = AccionImportante::onlyTrashed()->findOrFail($id);
        $accion->restore();

        return (new AccionImportanteResource($accion))
            ->additional(['message' => 'Acción importante restaurada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id): JsonResponse
    {
        $accion = AccionImportante::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $accion->trashed()) {
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.',
            ], 422);
        }

        // sin tablas dependientes: se elimina directamente
        $accion->forceDelete();

        return response()->json([
            'message' => 'Acción importante eliminada definitivamente.',
        ], 200);
    }
}
