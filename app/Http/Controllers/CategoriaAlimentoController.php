<?php

namespace App\Http\Controllers;

use App\Models\CategoriaAlimento;
use Illuminate\Http\Request;

class CategoriaAlimentoController extends Controller
{
    // trae las categorias de la base de datos de 5 en 5
    public function index(Request $request)
    {
        $categorias = CategoriaAlimento::paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['categorias' => $categorias], 200);
        }

        // retorna la pagina del index de categorias
        return view('categorias.index', compact('categorias'));
    }

    // genera un formulario en blanco para la pagina
    public function create(Request $request)
    {
        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Formulario de creación de categorías'], 200);
        }

        return view('categorias.create');
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
        ]);

        // crea una categoria
        CategoriaAlimento::create($validado);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Categoria creada correctamente'], 201);
        }

        // redirige al index y un mensaje de correcto
        return redirect()->route('categorias.index')->with('success', 'Categoria creada correctamente');
    }

    public function edit(Request $request, $id)
    {
        $categoria = CategoriaAlimento::findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['categoria' => $categoria], 200);
        }

        return view('categorias.edit', compact('categoria'));
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

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Categoría actualizada correctamente.'], 200);
        }

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    // retorna la vista show de la categoria
    public function show(Request $request, $id)
    {
        $categoria = CategoriaAlimento::findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['categoria' => $categoria], 200);
        }

        return view('categorias.show', compact('categoria'));
    }

    // borra la categoria logicamente
    public function destroy(Request $request, $id)
    {
        $categoria = CategoriaAlimento::findOrFail($id);
        $categoria->delete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Categoría eliminada correctamente.'], 200);
        }

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente.');
    }

    // trae los eliminados logicamente de 5 en 5
    public function trashed(Request $request)
    {
        $categorias = CategoriaAlimento::onlyTrashed()->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['categorias' => $categorias], 200);
        }

        return view('categorias.trashed', compact('categorias'));
    }

    // regresa la categoria a la tabla
    public function restore(Request $request, $id)
    {
        $categoria = CategoriaAlimento::onlyTrashed()->findOrFail($id);
        $categoria->restore();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Categoría restaurada correctamente.'], 200);
        }

        return redirect()->route('categorias.trashed')->with('success', 'Categoría restaurada correctamente.');
    }

    // borra el registro de la tabla por completo
    public function forceDestroy(Request $request, $id)
    {
        $categoria = CategoriaAlimento::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $categoria->trashed()) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
            }

            return redirect()->route('categorias.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // validacion de relaciones: si otra tabla la usa, se cancela
        if ($categoria->alimentos()->count() > 0) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No se puede eliminar: la categoría tiene alimentos registrados.'], 409);
            }

            return redirect()->route('categorias.trashed')->with('error', 'No se puede eliminar: la categoría tiene alimentos registrados.');
        }

        $categoria->forceDelete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Categoría eliminada definitivamente.'], 200);
        }

        return redirect()->route('categorias.trashed')->with('success', 'Categoría eliminada definitivamente.');
    }
}
