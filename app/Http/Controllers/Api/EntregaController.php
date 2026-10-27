<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entrega;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class EntregaController extends Controller
{
    // listado paginado de entregas con su solicitud
    public function index()
    {
        $entregas = Entrega::with('solicitud')->paginate(5);

        return response()->json(['entregas' => $entregas], 200);
    }

    // catálogo de solicitudes para el formulario de creación
    public function create()
    {
        $solicitudes = Solicitud::orderBy('id_solicitud')->get();

        return response()->json(['solicitudes' => $solicitudes], 200);
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'id_solicitud' => 'required|exists:solicitudes,id_solicitud',
            'fecha_entrega' => 'required|date',
            'responsable' => 'required|string|max:100',
            'estado' => 'required|string|max:30',
            'observaciones' => 'nullable|string|max:250',
        ]);

        Entrega::create($validado);

        return response()->json(['message' => 'Entrega creada correctamente'], 201);
    }

    // datos de la entrega y sus solicitudes para el formulario de edición
    public function edit($id)
    {
        $entrega = Entrega::findOrFail($id);

        $solicitudes = Solicitud::with('usuario')->get();

        return response()->json(['entrega' => $entrega, 'solicitudes' => $solicitudes], 200);
    }

    public function update(Request $request, $id)
    {
        $entrega = Entrega::findOrFail($id);

        $request->validate([
            'id_solicitud' => 'required|exists:solicitudes,id_solicitud',
            'fecha_entrega' => 'required|date',
            'responsable' => 'required|max:100',
            'estado' => 'required|max:30',
            'observaciones' => 'nullable|max:250',
        ]);

        $entrega->update([
            'id_solicitud' => $request->id_solicitud,
            'fecha_entrega' => $request->fecha_entrega,
            'responsable' => $request->responsable,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        return response()->json(['message' => 'Entrega actualizada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $entrega = Entrega::with('solicitud')->findOrFail($id);

        return response()->json(['entrega' => $entrega], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $entrega = Entrega::findOrFail($id);
        $entrega->delete();

        return response()->json(['message' => 'Entrega eliminada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $entregas = Entrega::onlyTrashed()->paginate(5);

        return response()->json(['entregas' => $entregas], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $entrega = Entrega::onlyTrashed()->findOrFail($id);
        $entrega->restore();

        return response()->json(['message' => 'Entrega restaurada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $entrega = Entrega::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $entrega->trashed()) {
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // sin tablas dependientes: se elimina directamente
        $entrega->forceDelete();

        return response()->json(['message' => 'Entrega eliminada definitivamente.'], 200);
    }
}
