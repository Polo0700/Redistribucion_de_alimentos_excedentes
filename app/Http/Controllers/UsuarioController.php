<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return UsuarioResource::collection(Usuario::with('rol')->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVO USUARIO
    |--------------------------------------------------------------------------
    */
    public function store(UsuarioRequest $request): JsonResponse
    {
        $usuario = Usuario::create($request->validated());

        return (new UsuarioResource($usuario))
            ->additional(['message' => 'Usuario creado correctamente'])
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
        return (new UsuarioResource(Usuario::with('rol')->findOrFail($id)))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR USUARIO
    |--------------------------------------------------------------------------
    */
    public function update(UsuarioRequest $request, $id): JsonResponse
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->validated());

        return (new UsuarioResource($usuario))
            ->additional(['message' => 'Usuario actualizado correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id): JsonResponse
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente.',
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        return UsuarioResource::collection(Usuario::onlyTrashed()->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id): JsonResponse
    {
        $usuario = Usuario::onlyTrashed()->findOrFail($id);
        $usuario->restore();

        return (new UsuarioResource($usuario))
            ->additional(['message' => 'Usuario restaurado correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id): JsonResponse
    {
        $usuario = Usuario::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $usuario->trashed()) {
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.',
            ], 422);
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($usuario->cuentasAcceso()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: el usuario tiene cuentas de acceso registradas.',
            ], 422);
        }

        if ($usuario->donaciones()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: el usuario tiene donaciones registradas.',
            ], 422);
        }

        if ($usuario->accionesImportantes()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: el usuario tiene acciones importantes registradas.',
            ], 422);
        }

        $usuario->forceDelete();

        return response()->json([
            'message' => 'Usuario eliminado definitivamente.',
        ], 200);
    }
}
