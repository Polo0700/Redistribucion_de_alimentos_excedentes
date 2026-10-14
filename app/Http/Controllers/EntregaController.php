<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class EntregaController extends Controller
{
    // trae las entregas de la base de datos con su solicitud de 5 en 5
    public function index()
    {
        $entregas = Entrega::with('solicitud')->paginate(5);

        // retorna la pagina del index de entregas
        // return view('entregas.index', compact('entregas'));
        return response()->json(['entregas' => $entregas], 200);
    }

    // obtiene las solicitudes y las organiza por id y las mete a solicitudes
    public function create()
    {
        $solicitudes = Solicitud::orderBy('id_solicitud')->get();

        // retorna la vista de creacion de entregas
        // return view('entregas.create', compact('solicitudes'));
        return response()->json(['solicitudes' => $solicitudes], 200);
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_solicitud'   => 'required|exists:solicitudes,id_solicitud',
            'fecha_entrega'  => 'required|date',
            'responsable'    => 'required|string|max:100',
            'estado'         => 'required|string|max:30',
            'observaciones'  => 'nullable|string|max:250',
        ]);

        // crea una entrega
        Entrega::create($validado);

        // redirige al index y un mensaje de correcto
        // return redirect()->route('entregas.index')->with('success', 'Entrega creada correctamente');
        return response()->json(['message' => 'Entrega creada correctamente'], 201);
    }

    public function edit($id)
{
    $entrega = Entrega::findOrFail($id);

    $solicitudes = Solicitud::with('usuario')->get();

    // return view(
    //     'entregas.edit',
    //     compact('entrega', 'solicitudes')
    // );
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

// return redirect()
//         ->route('entregas.index')
//         ->with('success', 'Entrega actualizada correctamente.');
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

        // return view('entregas.show', compact('entrega'));
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

        // return redirect()->route('entregas.index')->with('success', 'Entrega eliminada correctamente.');
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

        // return view('entregas.trashed', compact('entregas'));
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

        // return redirect()->route('entregas.trashed')->with('success', 'Entrega restaurada correctamente.');
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
        if (!$entrega->trashed()) {
            // return redirect()->route('entregas.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // sin tablas dependientes: se elimina directamente
        $entrega->forceDelete();

        // return redirect()->route('entregas.trashed')->with('success', 'Entrega eliminada definitivamente.');
        return response()->json(['message' => 'Entrega eliminada definitivamente.'], 200);
    }
}