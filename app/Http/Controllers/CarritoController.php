<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Usuario;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    // trae los carritos de la base de datos con su usuario de 5 en 5
    public function index()
    {
        $carritos = Carrito::with('usuario')->paginate(5);

        // retorna la pagina del index de carritos
        return view('carritos.index', compact('carritos'));
    }

    // obtiene los usuarios y los ordena por nombre y los mete a usuarios
    public function create()
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        // retorna la vista de creacion de carritos
        return view('carritos.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_usuario'     => 'required|exists:usuarios,id',
            'fecha_creacion' => 'required|date',
            'estado'         => 'required|string|max:30',
        ]);

        // crea un carrito
        Carrito::create($validado);

        // redirige al index y un mensaje de correcto
        return redirect()->route('carritos.index')->with('success', 'Carrito creado correctamente');
    }

    public function edit($id)
{
    $carrito = Carrito::findOrFail($id);

    $usuarios = Usuario::where('estado', true)->get();

    return view(
        'carritos.edit',
        compact('carrito', 'usuarios')
    );
}

public function update(Request $request, $id)
{
    $carrito = Carrito::findOrFail($id);

    $request->validate([
        'id_usuario' => 'required|exists:usuarios,id',
        'fecha_creacion' => 'required|date',
        'estado' => 'required|max:30',
    ]);

    $carrito->update([
        'id_usuario' => $request->id_usuario,
        'fecha_creacion' => $request->fecha_creacion,
        'estado' => $request->estado,
    ]);

    return redirect()
        ->route('carritos.index')
        ->with('success', 'Carrito actualizado correctamente.');
}
}