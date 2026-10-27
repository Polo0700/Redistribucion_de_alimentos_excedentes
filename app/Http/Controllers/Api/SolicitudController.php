<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    // listado paginado de solicitudes con su usuario
    public function index()
    {
        $solicitudes = Solicitud::with('usuario')->paginate(5);

        return response()->json(['solicitudes' => $solicitudes], 200);
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
            'fecha_solicitud' => 'required|date',
            'estado' => 'required|string|max:30',
            'direccion_entrega' => 'required|string|max:200',
            'observaciones' => 'nullable|string|max:250',
        ]);

        Solicitud::create($validado);

        return response()->json(['message' => 'Solicitud creada correctamente'], 201);
    }

    // datos de la solicitud y solo usuarios activos para el formulario de edición
    public function edit($id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $usuarios = Usuario::where('estado', true)->get();

        return response()->json(['solicitud' => $solicitud, 'usuarios' => $usuarios], 200);
    }

    public function update(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'fecha_solicitud' => 'required|date',
            'estado' => 'required|max:30',
            'direccion_entrega' => 'required|max:200',
            'observaciones' => 'nullable|max:250',
        ]);

        $solicitud->update([
            'id_usuario' => $request->id_usuario,
            'fecha_solicitud' => $request->fecha_solicitud,
            'estado' => $request->estado,
            'direccion_entrega' => $request->direccion_entrega,
            'observaciones' => $request->observaciones,
        ]);

        return response()->json(['message' => 'Solicitud actualizada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $solicitud = Solicitud::with('usuario')->findOrFail($id);

        return response()->json(['solicitud' => $solicitud], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();

        return response()->json(['message' => 'Solicitud eliminada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $solicitudes = Solicitud::onlyTrashed()->paginate(5);

        return response()->json(['solicitudes' => $solicitudes], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $solicitud = Solicitud::onlyTrashed()->findOrFail($id);
        $solicitud->restore();

        return response()->json(['message' => 'Solicitud restaurada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $solicitud = Solicitud::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $solicitud->trashed()) {
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // validación de relaciones: si otra tabla la usa, se cancela
        if ($solicitud->detalles()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar: la solicitud tiene detalles registrados.'], 409);
        }

        if ($solicitud->entrega()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar: la solicitud tiene una entrega asociada.'], 409);
        }

        $solicitud->forceDelete();

        return response()->json(['message' => 'Solicitud eliminada definitivamente.'], 200);
    }
}
