<?php

namespace App\Http\Controllers;

use App\Models\CategoriaAlimento;
use Illuminate\Http\Request;

class CategoriaAlimentoController extends Controller
{
    // trae las categorias de la base de datos de 5 en 5
    public function index()
    {
        $categorias = CategoriaAlimento::paginate(5);

        return response()->json(['categorias' => $categorias], 200);
    }

    
    public function create()
{
    return response()->json([
        'message' => 'Endpoint no utilizado en API'
    ]);
}

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
        ]);

       $categoria = CategoriaAlimento::create($validado);
              return response()->json([
      'message' => 'Categoria creada correctamente',
      'data' => $categoria
      ], 201);
    }

    public function edit($id)
    {
        $categoria = CategoriaAlimento::findOrFail($id);

       return response()->json([
    'categoria' => $categoria
]);
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

           return response()->json([
    'message' => 'Categoría actualizada correctamente.','data' => $categoria], 200);
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
            return response()->json(['message' => 'Primero debe aplicar el borrado lógico al registro.'], 422);
        }

        // validación de relaciones: si otra tabla la usa, se cancela
        if ($categoria->alimentos()->count() > 0) {
          return response()->json([
    'message' => 'Categoría eliminada definitivamente.'], 200);
        }

        $categoria->forceDelete();

        return redirect()->route('categorias.trashed')->with('success', 'Categoría eliminada definitivamente.');
    }
}
