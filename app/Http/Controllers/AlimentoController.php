<?php

namespace App\Http\Controllers;

use App\Models\Alimento;
use App\Models\CategoriaAlimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlimentoController extends Controller
{
    // listado de alimentos
    public function index(Request $request)
    {
        // trae los alimentos de la base de datos con su categoria de 5 en 5
        $alimentos = Alimento::with('categoria')->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['alimentos' => $alimentos], 200);
        }

        // retorna la vista index de alimentos
        return view('alimentos.index', compact('alimentos'));
    }

    // formulario para crear un alimento
    public function create(Request $request)
    {
        // obtiene las categorias ordenadas por nombre
        $categorias = CategoriaAlimento::orderBy('nombre')->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['categorias' => $categorias], 200);
        }

        // retorna la vista de creacion de alimentos
        return view('alimentos.create', compact('categorias'));
    }

    // guarda el alimento nuevo
    public function store(Request $request)
    {
        // validacion
        $validado = $request->validate([
            'id_categoria' => 'required|exists:categorias_alimento,id_categoria',
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // crear alimento
        $alimento = Alimento::create($validado);

        // guardar imagen si existe
        if ($request->hasFile('imagen')) {
            $extension = $request->file('imagen')->extension();
            $nombreImagen = 'Alimento_'.$alimento->id_alimento.'_1.'.$extension;
            $ruta = $request->file('imagen')->storeAs('alimentos', $nombreImagen, 'public');
            $alimento->update(['imagen' => $ruta]);
        }

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Alimento creado correctamente.'], 201);
        }

        return redirect()->route('alimentos.index')->with('success', 'Alimento creado correctamente.');
    }

    // formulario para editar un alimento
    public function edit(Request $request, $id)
    {
        $alimento = Alimento::findOrFail($id);
        $categorias = CategoriaAlimento::orderBy('nombre')->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['alimento' => $alimento, 'categorias' => $categorias], 200);
        }

        return view('alimentos.edit', compact('alimento', 'categorias'));
    }

    // actualiza el alimento
    public function update(Request $request, $id)
    {
        $alimento = Alimento::findOrFail($id);
        $imagenAnterior = $alimento->imagen;

        // validacion
        $validado = $request->validate([
            'id_categoria' => 'required|exists:categorias_alimento,id_categoria',
            'nombre' => 'required|string|max:80',
            'descripcion' => 'nullable|string|max:200',
            'estado' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // actualizar datos
        $alimento->update([
            'id_categoria' => $validado['id_categoria'],
            'nombre' => $validado['nombre'],
            'descripcion' => $validado['descripcion'],
            'estado' => $validado['estado'],
        ]);

        // si hay nueva imagen
        if ($request->hasFile('imagen')) {
            $extension = $request->file('imagen')->extension();
            $nombreImagen = 'Alimento_'.$alimento->id_alimento.'_1.'.$extension;
            $rutaNueva = $request->file('imagen')->storeAs('alimentos', $nombreImagen, 'public');

            $alimento->update(['imagen' => $rutaNueva]);

            // eliminar imagen anterior si existe y es distinta
            if ($imagenAnterior && $imagenAnterior !== $rutaNueva) {
                Storage::disk('public')->delete($imagenAnterior);
            }
        }

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Alimento actualizado correctamente.'], 200);
        }

        return redirect()->route('alimentos.index')->with('success', 'Alimento actualizado correctamente.');
    }

    // retorna la vista show del alimento
    public function show(Request $request, $id)
    {
        $alimento = Alimento::with('categoria')->findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['alimento' => $alimento], 200);
        }

        return view('alimentos.show', compact('alimento'));
    }

    // borra el alimento logicamente
    public function destroy(Request $request, $id)
    {
        $alimento = Alimento::findOrFail($id);
        $alimento->delete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Alimento eliminado correctamente.'], 200);
        }

        return redirect()->route('alimentos.index')->with('success', 'Alimento eliminado correctamente.');
    }

    // trae los eliminados logicamente de 5 en 5
    public function trashed(Request $request)
    {
        $alimentos = Alimento::onlyTrashed()->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['alimentos' => $alimentos], 200);
        }

        return view('alimentos.trashed', compact('alimentos'));
    }

    // regresa el alimento a la tabla
    public function restore(Request $request, $id)
    {
        $alimento = Alimento::onlyTrashed()->findOrFail($id);
        $alimento->restore();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Alimento restaurado correctamente.'], 200);
        }

        return redirect()->route('alimentos.trashed')->with('success', 'Alimento restaurado correctamente.');
    }

    // borra el registro de la tabla por completo
    public function forceDestroy(Request $request, $id)
    {
        $alimento = Alimento::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $alimento->trashed()) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
            }

            return redirect()->route('alimentos.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // validacion de relaciones: si otra tabla lo usa, se cancela
        if ($alimento->detallesDonacion()->count() > 0) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No se puede eliminar: el alimento está registrado en Detalles de Donación.'], 409);
            }

            return redirect()->route('alimentos.trashed')->with('error', 'No se puede eliminar: el alimento está registrado en Detalles de Donación.');
        }

        

    
        // eliminar la imagen asociada del almacenamiento
        if ($alimento->imagen && Storage::disk('public')->exists($alimento->imagen)) {
            Storage::disk('public')->delete($alimento->imagen);
        }

        // eliminar el registro de la base de datos
        $alimento->forceDelete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Alimento eliminado físicamente junto con su imagen.'], 200);
        }

        return redirect()->route('alimentos.trashed')->with('success', 'Alimento eliminado físicamente junto con su imagen.');
    }
}
