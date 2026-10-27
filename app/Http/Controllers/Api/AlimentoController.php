<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alimento;
use App\Models\CategoriaAlimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlimentoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $alimentos = Alimento::with('categoria')->paginate(5);

        return response()->json(['alimentos' => $alimentos], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO DE CREACIÓN
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $categorias = CategoriaAlimento::orderBy('nombre')->get();

        return response()->json(['categorias' => $categorias], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVO ALIMENTO
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validado = $request->validate([
            'id_categoria' => 'required|exists:categorias_alimento,id_categoria',
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $alimento = Alimento::create($validado);

        // guardar imagen si existe
        if ($request->hasFile('imagen')) {
            $extension = $request->file('imagen')->extension();
            $nombreImagen = 'Alimento_'.$alimento->id_alimento.'_1.'.$extension;
            $ruta = $request->file('imagen')->storeAs('alimentos', $nombreImagen, 'public');
            $alimento->update(['imagen' => $ruta]);
        }

        return response()->json(['message' => 'Alimento creado correctamente.'], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO DE EDICIÓN
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $alimento = Alimento::findOrFail($id);
        $categorias = CategoriaAlimento::orderBy('nombre')->get();

        return response()->json(['alimento' => $alimento, 'categorias' => $categorias], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR ALIMENTO
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $alimento = Alimento::findOrFail($id);
        $imagenAnterior = $alimento->imagen;

        $validado = $request->validate([
            'id_categoria' => 'required|exists:categorias_alimento,id_categoria',
            'nombre' => 'required|string|max:80',
            'descripcion' => 'nullable|string|max:200',
            'estado' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

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

        return response()->json(['message' => 'Alimento actualizado correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $alimento = Alimento::with('categoria')->findOrFail($id);

        return response()->json(['alimento' => $alimento], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $alimento = Alimento::findOrFail($id);
        $alimento->delete();

        return response()->json(['message' => 'Alimento eliminado correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $alimentos = Alimento::onlyTrashed()->paginate(5);

        return response()->json(['alimentos' => $alimentos], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $alimento = Alimento::onlyTrashed()->findOrFail($id);
        $alimento->restore();

        return response()->json(['message' => 'Alimento restaurado correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $alimento = Alimento::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $alimento->trashed()) {
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($alimento->detallesDonacion()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar: el alimento está registrado en Detalles de Donación.'], 409);
        }

        // eliminar la imagen asociada del almacenamiento
        if ($alimento->imagen && Storage::disk('public')->exists($alimento->imagen)) {
            Storage::disk('public')->delete($alimento->imagen);
        }

        // eliminar el registro de la base de datos
        $alimento->forceDelete();

        return response()->json(['message' => 'Alimento eliminado físicamente junto con su imagen.'], 200);
    }
}
