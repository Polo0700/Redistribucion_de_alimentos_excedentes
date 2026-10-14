<?php

namespace App\Http\Controllers;

use App\Models\ListaDeseo;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ListaDeseoController extends Controller
{
    // trae las listas de deseos de la base de datos con su usuario de 5 en 5
    public function index()
    {
        $listas = ListaDeseo::with('usuario')->paginate(5);

        // retorna la pagina del index de listas de deseos
        // return view('listas-deseos.index', compact('listas'));
        return response()->json(['listas' => $listas], 200);
    }

    // obtiene los usuarios y los ordena por nombre y los mete a usuarios
    public function create()
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        // retorna la vista de creacion de listas de deseos
        // return view('listas-deseos.create', compact('usuarios'));
        return response()->json(['usuarios' => $usuarios], 200);
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_usuario'     => 'required|exists:usuarios,id',
            'nombre'         => 'required|string|max:100',
            'fecha_creacion' => 'required|date',
        ]);

        // crea una lista de deseos
        ListaDeseo::create($validado);

        // redirige al index y un mensaje de correcto
        // return redirect()->route('listas-deseos.index')->with('success', 'Lista de deseos creada correctamente');
        return response()->json(['message' => 'Lista de deseos creada correctamente'], 201);
    }

    public function edit($id)
{
    $lista = ListaDeseo::findOrFail($id);

    $usuarios = Usuario::where('estado', true)->get();

    // return view(
    //     'listas-deseos.edit',
    //     compact('lista', 'usuarios')
    // );
    return response()->json(['lista' => $lista, 'usuarios' => $usuarios], 200);
}

public function update(Request $request, $id)
{
    $lista = ListaDeseo::findOrFail($id);

    $request->validate([
        'id_usuario' => 'required|exists:usuarios,id',
        'nombre' => 'required|max:100',
        'fecha_creacion' => 'required|date',
    ]);

    $lista->update([
        'id_usuario' => $request->id_usuario,
        'nombre' => $request->nombre,
        'fecha_creacion' => $request->fecha_creacion,
    ]);

// return redirect()
//         ->route('listas-deseos.index')
//         ->with('success', 'Lista de deseos actualizada correctamente.');
return response()->json(['message' => 'Lista de deseos actualizada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $lista = ListaDeseo::with('usuario')->findOrFail($id);

        // return view('listas-deseos.show', compact('lista'));
        return response()->json(['lista' => $lista], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $lista = ListaDeseo::findOrFail($id);
        $lista->delete();

        // return redirect()->route('listas-deseos.index')->with('success', 'Lista de deseos eliminada correctamente.');
        return response()->json(['message' => 'Lista de deseos eliminada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $listas = ListaDeseo::onlyTrashed()->paginate(5);

        // return view('listas-deseos.trashed', compact('listas'));
        return response()->json(['listas' => $listas], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $lista = ListaDeseo::onlyTrashed()->findOrFail($id);
        $lista->restore();

        // return redirect()->route('listas-deseos.trashed')->with('success', 'Lista de deseos restaurada correctamente.');
        return response()->json(['message' => 'Lista de deseos restaurada correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $lista = ListaDeseo::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (!$lista->trashed()) {
            // return redirect()->route('listas-deseos.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($lista->deseos()->count() > 0) {
            // return redirect()->route('listas-deseos.trashed')->with('error', 'No se puede eliminar: la lista tiene deseos asociados.');
            return response()->json(['error' => 'No se puede eliminar: la lista tiene deseos asociados.'], 409);
        }

        $lista->forceDelete();

        // return redirect()->route('listas-deseos.trashed')->with('success', 'Lista de deseos eliminada definitivamente.');
        return response()->json(['message' => 'Lista de deseos eliminada definitivamente.'], 200);
    }
}