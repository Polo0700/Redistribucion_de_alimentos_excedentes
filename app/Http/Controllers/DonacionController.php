<?php

namespace App\Http\Controllers;

use App\Models\Donacion;
use App\Models\Usuario;
use Illuminate\Http\Request;

class DonacionController extends Controller
{
    // trae las donaciones de la base de datos con su usuario de 5 en 5
    public function index()
    {
        $donaciones = Donacion::with('usuario')->paginate(5);

        return response()->json([
            'donaciones' => $donaciones
        ], 200);
    }

    // obtiene los usuarios y los ordena por nombre y los mete a usuarios
    public function create()
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        return response()->json([
            'usuarios' => $usuarios
        ], 200);
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros y que las fechas esten en orden
        $validado = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'fecha_donacion' => 'required|date',
            'fecha_limite' => 'required|date|after:fecha_donacion',
            'ubicacion' => 'required|string|max:200',
            'estado' => 'required|string|max:30',
            'observaciones' => 'required|string|max:250',
        ]);

        // crea una donacion
        $donacion = Donacion::create($validado);

        return response()->json([
            'message' => 'Donacion creada correctamente',
            'data' => $donacion
        ], 201);
    }

    public function edit($id)
    {
        $donacion = Donacion::findOrFail($id);

        $usuarios = Usuario::where('estado', true)->get();

        return response()->json([
            'donacion' => $donacion,
            'usuarios' => $usuarios
        ], 200);
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

        return response()->json([
            'message' => 'Donación actualizada correctamente.',
            'data' => $donacion
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $donacion = Donacion::with('usuario')->findOrFail($id);

        return response()->json([
            'donacion' => $donacion
        ], 200);
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

        return response()->json([
            'message' => 'Donación eliminada correctamente.'
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $donaciones = Donacion::onlyTrashed()->paginate(5);

        return response()->json([
            'donaciones' => $donaciones
        ], 200);
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

        return response()->json([
            'message' => 'Donación restaurada correctamente.'
        ], 200);
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
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.'
            ], 422);
        }

        // validación de relaciones: si otra tabla la usa, se cancela
        if ($donacion->detalles()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: la donación tiene detalles registrados.'
            ], 422);
        }

        $donacion->forceDelete();

        return response()->json([
            'message' => 'Donación eliminada definitivamente.'
        ], 200);
    }
}