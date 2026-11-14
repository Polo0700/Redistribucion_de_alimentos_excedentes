<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolRequest;
use App\Http\Resources\RolResource;
use App\Models\Rol;
use Illuminate\Http\JsonResponse;

class RolController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return RolResource::collection(Rol::paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVO ROL
    |--------------------------------------------------------------------------
    */
    public function store(RolRequest $request): JsonResponse
    {
        $rol = Rol::create($request->validated());

        return (new RolResource($rol))
            ->additional(['message' => 'Rol creado correctamente'])
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
        return (new RolResource(Rol::findOrFail($id)))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR ROL
    |--------------------------------------------------------------------------
    */
    public function update(RolRequest $request, $id): JsonResponse
    {
        $rol = Rol::findOrFail($id);
        $rol->update($request->validated());

        return (new RolResource($rol))
            ->additional(['message' => 'Rol actualizado correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id): JsonResponse
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();

        return response()->json([
            'message' => 'Rol eliminado correctamente.',
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        return RolResource::collection(Rol::onlyTrashed()->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id): JsonResponse
    {
        $rol = Rol::onlyTrashed()->findOrFail($id);
        $rol->restore();

        return (new RolResource($rol))
            ->additional(['message' => 'Rol restaurado correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id): JsonResponse
    {
        $rol = Rol::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $rol->trashed()) {
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.',
            ], 422);
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($rol->usuarios()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: el rol tiene usuarios asignados.',
            ], 422);
        }

        $rol->forceDelete();

        return response()->json([
            'message' => 'Rol eliminado definitivamente.',
        ], 200);
    }
}
