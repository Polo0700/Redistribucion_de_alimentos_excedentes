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
        return view('entregas.index', compact('entregas'));
    }

    // obtiene las solicitudes y las organiza por id y las mete a solicitudes
    public function create()
    {
        $solicitudes = Solicitud::orderBy('id_solicitud')->get();

        // retorna la vista de creacion de entregas
        return view('entregas.create', compact('solicitudes'));
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
        return redirect()->route('entregas.index')->with('success', 'Entrega creada correctamente');
    }

    public function edit($id)
{
    $entrega = Entrega::findOrFail($id);

    $solicitudes = Solicitud::with('usuario')->get();

    return view(
        'entregas.edit',
        compact('entrega', 'solicitudes')
    );
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

    return redirect()
        ->route('entregas.index')
        ->with('success', 'Entrega actualizada correctamente.');
}
}