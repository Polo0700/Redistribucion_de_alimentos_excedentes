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

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $solicitud = Solicitud::with('usuario')->findOrFail($id);

        return view('solicitudes.show', compact('solicitud'));
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

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud eliminada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $solicitudes = Solicitud::onlyTrashed()->paginate(5);

        return view('solicitudes.trashed', compact('solicitudes'));
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

        return redirect()->route('solicitudes.trashed')->with('success', 'Solicitud restaurada correctamente.');
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
        if (!$solicitud->trashed()) {
            return redirect()->route('solicitudes.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // validación de relaciones: si otra tabla la usa, se cancela
        if ($solicitud->detalles()->count() > 0) {
            return redirect()->route('solicitudes.trashed')->with('error', 'No se puede eliminar: la solicitud tiene detalles registrados.');
        }

        if ($solicitud->entrega()->count() > 0) {
            return redirect()->route('solicitudes.trashed')->with('error', 'No se puede eliminar: la solicitud tiene una entrega asociada.');
        }

        $solicitud->forceDelete();

        return redirect()->route('solicitudes.trashed')->with('success', 'Solicitud eliminada definitivamente.');
    }
}