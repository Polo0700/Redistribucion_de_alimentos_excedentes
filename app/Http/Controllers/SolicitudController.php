<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitudRequest;
use App\Http\Resources\SolicitudResource;
use App\Models\Solicitud;
use Illuminate\Http\JsonResponse;

class SolicitudController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return SolicitudResource::collection(Solicitud::with('usuario')->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVA SOLICITUD
    |--------------------------------------------------------------------------
    */
    public function store(SolicitudRequest $request): JsonResponse
    {
        $solicitud = Solicitud::create($request->validated());

        return (new SolicitudResource($solicitud))
            ->additional(['message' => 'Solicitud creada correctamente'])
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
        return (new SolicitudResource(Solicitud::with('usuario')->findOrFail($id)))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR SOLICITUD
    |--------------------------------------------------------------------------
    */
    public function update(SolicitudRequest $request, $id): JsonResponse
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->update($request->validated());

        return (new SolicitudResource($solicitud))
            ->additional(['message' => 'Solicitud actualizada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id): JsonResponse
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();

        return response()->json([
            'message' => 'Solicitud eliminada correctamente.',
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        return SolicitudResource::collection(Solicitud::onlyTrashed()->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id): JsonResponse
    {
        $solicitud = Solicitud::onlyTrashed()->findOrFail($id);
        $solicitud->restore();

        return (new SolicitudResource($solicitud))
            ->additional(['message' => 'Solicitud restaurada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id): JsonResponse
    {
        $solicitud = Solicitud::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $solicitud->trashed()) {
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.',
            ], 422);
        }

        // validación de relaciones: si otra tabla la usa, se cancela
        if ($solicitud->detalles()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: la solicitud tiene detalles registrados.',
            ], 422);
        }

        if ($solicitud->entrega()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: la solicitud tiene una entrega asociada.',
            ], 422);
        }

        $solicitud->forceDelete();

        return response()->json([
            'message' => 'Solicitud eliminada definitivamente.',
        ], 200);
    }
}
