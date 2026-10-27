<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donacion;
use App\Models\Usuario;
use Illuminate\Http\Request;

class DonacionController extends Controller
{
    // listado paginado de donaciones con su usuario
    public function index()
    {
        $donaciones = Donacion::with('usuario')->paginate(5);

        return response()->json(['donaciones' => $donaciones], 200);
    }

    // catálogo de usuarios para el formulario de creación
    public function create()
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        return response()->json(['usuarios' => $usuarios], 200);
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'fecha_donacion' => 'required|date',
            'fecha_limite' => 'required|date|after:fecha_donacion',
            'ubicacion' => 'required|string|max:200',
            'estado' => 'required|string|max:30',
            'observaciones' => 'required|string|max:250',
        ]);

        Donacion::create($validado);

        return response()->json(['message' => 'Donacion creada correctamente'], 201);
    }

    // datos de la donación y solo usuarios activos para el formulario de edición
    public function edit($id)
    {
        $donacion = Donacion::findOrFail($id);

        $usuarios = Usuario::where('estado', true)->get();

        return response()->json(['donacion' => $donacion, 'usuarios' => $usuarios], 200);
    }

    public function update(Request $request, $id)
    {
        $donacion = Donacion::findOrFail($id);

        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'fecha_donacion' => 'required|date',
            'fecha_limite' => 'required|date',
            'ubicacion' => 'required|max:200',
            'estado' => 'required|max:30',
            'observaciones' => 'nullable|max:250',
        ]);

        $donacion->update([
            'id_usuario' => $request->id_usuario,
            'fecha_donacion' => $request->fecha_donacion,
            'fecha_limite' => $request->fecha_limite,
            'ubicacion' => $request->ubicacion,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        return response()->json(['message' => 'Donación actualizada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $donacion = Donacion::with('usuario')->findOrFail($id);

        return response()->json(['donacion' => $donacion], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $donacion = Donacion::findOrFail($id);
        $donacion->delete();

        return response()->json(['message' => 'Donación eliminada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $donaciones = Donacion::onlyTrashed()->paginate(5);

        return response()->json(['donaciones' => $donaciones], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $donacion = Donacion::onlyTrashed()->findOrFail($id);
        $donacion->restore();

        return response()->json(['message' => 'Donación restaurada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $donacion = Donacion::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $donacion->trashed()) {
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // validación de relaciones: si otra tabla la usa, se cancela
        if ($donacion->detalles()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar: la donación tiene detalles registrados.'], 409);
        }

        $donacion->forceDelete();

        return response()->json(['message' => 'Donación eliminada definitivamente.'], 200);
    }
}
