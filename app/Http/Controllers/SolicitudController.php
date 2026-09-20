<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    // trae las solicitudes de la base de datos con su usuario de 5 en 5
    public function index()
    {
        $solicitudes = Solicitud::with('usuario')->paginate(5);

        // retorna la pagina del index de solicitudes
        return view('solicitudes.index', compact('solicitudes'));
    }

    // obtiene los usuarios y los ordena por nombre y los mete a usuarios
    public function create()
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        // retorna la vista de creacion de solicitudes
        return view('solicitudes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_usuario'       => 'required|exists:usuarios,id',
            'fecha_solicitud'  => 'required|date',
            'estado'           => 'required|string|max:30',
            'direccion_entrega'=> 'required|string|max:200',
            'observaciones'    => 'nullable|string|max:250',
        ]);

        // crea una solicitud
        Solicitud::create($validado);

        // redirige al index y un mensaje de correcto
        return redirect()->route('solicitudes.index')->with('success', 'Solicitud creada correctamente');
    }

    public function edit($id)
{
    $solicitud = Solicitud::findOrFail($id);

    $usuarios = Usuario::where('estado', true)->get();

    return view(
        'solicitudes.edit',
        compact('solicitud', 'usuarios')
    );
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

    return redirect()
        ->route('solicitudes.index')
        ->with('success', 'Solicitud actualizada correctamente.');
}
}