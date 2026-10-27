<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoriaAlimento;
use Illuminate\Http\Request;

class CategoriaAlimentoController extends Controller
{
    // listado paginado de categorías
    public function index()
    {
        $categorias = CategoriaAlimento::paginate(5);

        return response()->json(['categorias' => $categorias], 200);
    }

    // datos del formulario de creación (categorías no requiere catálogos)
    public function create()
    {
        return response()->json(['message' => 'Formulario de creación de categorías'], 200);
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
        ]);

        CategoriaAlimento::create($validado);

        return response()->json(['message' => 'Categoria creada correctamente'], 201);
    }

    // datos del formulario de edición
    public function edit($id)
    {
        $categoria = CategoriaAlimento::findOrFail($id);

        return response()->json(['categoria' => $categoria], 200);
    }

    public function update(Request $request, $id)
    {
        $categoria = CategoriaAlimento::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:80',
            'descripcion' => 'nullable|max:200',
            'estado' => 'required|boolean',
        ]);

        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
        ]);

        return response()->json(['message' => 'Categoría actualizada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $categoria = CategoriaAlimento::findOrFail($id);

        return response()->json(['categoria' => $categoria], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
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
        $categorias = CategoriaAlimento::onlyTrashed()->paginate(5);

        return response()->json(['categorias' => $categorias], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $categoria = CategoriaAlimento::onlyTrashed()->findOrFail($id);
        $categoria->restore();

        return response()->json(['message' => 'Categoría restaurada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $categoria = CategoriaAlimento::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $categoria->trashed()) {
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // validación de relaciones: si otra tabla la usa, se cancela
        if ($categoria->alimentos()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar: la categoría tiene alimentos registrados.'], 409);
        }

        $categoria->forceDelete();

        return response()->json(['message' => 'Categoría eliminada definitivamente.'], 200);
    }
}
