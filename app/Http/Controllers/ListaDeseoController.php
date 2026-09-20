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
        return view('listas-deseos.index', compact('listas'));
    }

    // obtiene los usuarios y los ordena por nombre y los mete a usuarios
    public function create()
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        // retorna la vista de creacion de listas de deseos
        return view('listas-deseos.create', compact('usuarios'));
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
        return redirect()->route('listas-deseos.index')->with('success', 'Lista de deseos creada correctamente');
    }

    public function edit($id)
{
    $lista = ListaDeseo::findOrFail($id);

    $usuarios = Usuario::where('estado', true)->get();

    return view(
        'listas-deseos.edit',
        compact('lista', 'usuarios')
    );
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

    return redirect()
        ->route('listas-deseos.index')
        ->with('success', 'Lista de deseos actualizada correctamente.');
}
}