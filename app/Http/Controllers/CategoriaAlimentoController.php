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

        // retorna la pagina del index de categorias
        // return view('categorias.index', compact('categorias'));
        return response()->json(['categorias' => $categorias], 200);
    }

    // genera un formulario en blanco para la pagina
    public function create()
    {
        // return view('categorias.create');
        return response()->json(['message' => 'Formulario de creación de categorías'], 200);
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'nombre'      => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado'      => 'required|boolean',
        ]);

        // crea una categoria
        CategoriaAlimento::create($validado);

        // redirige al index y un mensaje de correcto
        // return redirect()->route('categorias.index')->with('success', 'Categoria creada correctamente');
        return response()->json(['message' => 'Categoria creada correctamente'], 201);
    }

    public function edit($id)
{
    $categoria = CategoriaAlimento::findOrFail($id);

    // return view('categorias.edit', compact('categoria'));
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

    // return redirect()
    //     ->route('categorias.index')
    //     ->with('success', 'Categoría actualizada correctamente.');
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

        // return view('categorias.show', compact('categoria'));
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

        // return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente.');
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

        // return view('categorias.trashed', compact('categorias'));
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

        // return redirect()->route('categorias.trashed')->with('success', 'Categoría restaurada correctamente.');
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
        if (!$categoria->trashed()) {
            // return redirect()->route('categorias.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // validación de relaciones: si otra tabla la usa, se cancela
        if ($categoria->alimentos()->count() > 0) {
            // return redirect()->route('categorias.trashed')->with('error', 'No se puede eliminar: la categoría tiene alimentos registrados.');
            return response()->json(['error' => 'No se puede eliminar: la categoría tiene alimentos registrados.'], 409);
        }

        $categoria->forceDelete();

        // return redirect()->route('categorias.trashed')->with('success', 'Categoría eliminada definitivamente.');
        return response()->json(['message' => 'Categoría eliminada definitivamente.'], 200);
    }
}