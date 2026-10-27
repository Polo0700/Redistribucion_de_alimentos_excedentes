<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccionImportante;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AccionImportanteController extends Controller
{
    // listado paginado de acciones importantes con su usuario
    public function index()
    {
        $acciones = AccionImportante::with('usuario')->paginate(5);

        return response()->json(['acciones' => $acciones], 200);
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
            'id_usuario' => 'nullable|exists:usuarios,id',
            'accion' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:250',
            'tabla_afectada' => 'required|string|max:80',
            'fecha_hora' => 'required|date',
            'ip_origen' => 'required|ip|max:45',
        ]);

        AccionImportante::create($validado);

        return response()->json(['message' => 'Accion importante creada correctamente'], 201);
    }

    // datos de la acción y solo usuarios activos para el formulario de edición
    public function edit($id)
    {
        $accion = AccionImportante::findOrFail($id);

        $usuarios = Usuario::where('estado', true)->get();

        return response()->json(['accion' => $accion, 'usuarios' => $usuarios], 200);
    }

    public function update(Request $request, $id)
    {
        $accion = AccionImportante::findOrFail($id);

        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'accion' => 'required|max:100',
            'tabla_afectada' => 'required|max:80',
            'descripcion' => 'nullable|max:250',
            'fecha_hora' => 'required|date',
            'ip_origen' => 'nullable|ip',
        ]);

        $accion->update([
            'id_usuario' => $request->id_usuario,
            'accion' => $request->accion,
            'tabla_afectada' => $request->tabla_afectada,
            'descripcion' => $request->descripcion,
            'fecha_hora' => $request->fecha_hora,
            'ip_origen' => $request->ip_origen,
        ]);

        return response()->json(['message' => 'Acción actualizada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $accion = AccionImportante::with('usuario')->findOrFail($id);

        return response()->json(['accion' => $accion], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $accion = AccionImportante::findOrFail($id);
        $accion->delete();

        return response()->json(['message' => 'Acción importante eliminada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $acciones = AccionImportante::onlyTrashed()->paginate(5);

        return response()->json(['acciones' => $acciones], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $accion = AccionImportante::onlyTrashed()->findOrFail($id);
        $accion->restore();

        return response()->json(['message' => 'Acción importante restaurada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $accion = AccionImportante::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $accion->trashed()) {
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // sin tablas dependientes: se elimina directamente
        $accion->forceDelete();

        return response()->json(['message' => 'Acción importante eliminada definitivamente.'], 200);
    }
}
