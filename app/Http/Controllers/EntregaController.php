<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntregaRequest;
use App\Http\Resources\EntregaResource;
use App\Models\Entrega;
use Illuminate\Http\JsonResponse;

class EntregaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return EntregaResource::collection(Entrega::with('solicitud')->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVA ENTREGA
    |--------------------------------------------------------------------------
    */
    public function store(EntregaRequest $request): JsonResponse
    {
        $entrega = Entrega::create($request->validated());

        return (new EntregaResource($entrega))
            ->additional(['message' => 'Entrega creada correctamente'])
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
        return (new EntregaResource(Entrega::with('solicitud')->findOrFail($id)))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR ENTREGA
    |--------------------------------------------------------------------------
    */
    public function update(EntregaRequest $request, $id): JsonResponse
    {
        $entrega = Entrega::findOrFail($id);
        $entrega->update($request->validated());

        return (new EntregaResource($entrega))
            ->additional(['message' => 'Entrega actualizada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id): JsonResponse
    {
        $entrega = Entrega::findOrFail($id);
        $entrega->delete();

        return response()->json([
            'message' => 'Entrega eliminada correctamente.',
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        return EntregaResource::collection(Entrega::onlyTrashed()->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id): JsonResponse
    {
        $entrega = Entrega::onlyTrashed()->findOrFail($id);
        $entrega->restore();

        return (new EntregaResource($entrega))
            ->additional(['message' => 'Entrega restaurada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id): JsonResponse
    {
        $entrega = Entrega::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $entrega->trashed()) {
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.',
            ], 422);
        }

        // sin tablas dependientes: se elimina directamente
        $entrega->forceDelete();

        return response()->json([
            'message' => 'Entrega eliminada definitivamente.',
        ], 200);
    }
}
