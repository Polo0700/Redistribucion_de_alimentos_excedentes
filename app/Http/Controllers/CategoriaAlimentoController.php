<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaAlimentoRequest;
use App\Http\Resources\CategoriaAlimentoResource;
use App\Models\CategoriaAlimento;
use Illuminate\Http\JsonResponse;

class CategoriaAlimentoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return CategoriaAlimentoResource::collection(CategoriaAlimento::paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVA CATEGORÍA
    |--------------------------------------------------------------------------
    */
    public function store(CategoriaAlimentoRequest $request): JsonResponse
    {
        $categoria = CategoriaAlimento::create($request->validated());

        return (new CategoriaAlimentoResource($categoria))
            ->additional(['message' => 'Categoria creada correctamente'])
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
        return (new CategoriaAlimentoResource(CategoriaAlimento::findOrFail($id)))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR CATEGORÍA
    |--------------------------------------------------------------------------
    */
    public function update(CategoriaAlimentoRequest $request, $id): JsonResponse
    {
        $categoria = CategoriaAlimento::findOrFail($id);
        $categoria->update($request->validated());

        return (new CategoriaAlimentoResource($categoria))
            ->additional(['message' => 'Categoría actualizada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id): JsonResponse
    {
        $categoria = CategoriaAlimento::findOrFail($id);
        $categoria->delete();

        return response()->json(['message' => 'Categoría eliminada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        return CategoriaAlimentoResource::collection(CategoriaAlimento::onlyTrashed()->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id): JsonResponse
    {
        $categoria = CategoriaAlimento::onlyTrashed()->findOrFail($id);
        $categoria->restore();

        return (new CategoriaAlimentoResource($categoria))
            ->additional(['message' => 'Categoría restaurada correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id): JsonResponse
    {
        $categoria = CategoriaAlimento::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $categoria->trashed()) {
            return response()->json(['message' => 'Primero debe aplicar el borrado lógico al registro.'], 422);
        }

        // validación de relaciones: si otra tabla la usa, se cancela
        if ($categoria->alimentos()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: la categoría tiene alimentos registrados.',
            ], 422);
        }

        $categoria->forceDelete();

        return response()->json(['message' => 'Categoría eliminada definitivamente.'], 200);
    }
}
