<?php

namespace App\Http\Controllers;

use App\Models\Donacion;
use App\Models\Usuario;
use Illuminate\Http\Request;

class DonacionController extends Controller
{
    // trae las donaciones de la base de datos con su usuario de 5 en 5
    public function index()
    {
        $donaciones = Donacion::with('usuario')->paginate(5);

        // retorna la pagina del index de donaciones
        return view('donaciones.index', compact('donaciones'));
    }

    // obtiene los usuarios y los ordena por nombre y los mete a usuarios
    public function create()
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        // retorna la vista de creacion de donaciones
        return view('donaciones.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros y que las fechas esten en orden
        $validado = $request->validate([
            'id_usuario'     => 'required|exists:usuarios,id',
            'fecha_donacion' => 'required|date',
            'fecha_limite'   => 'required|date|after:fecha_donacion',
            'ubicacion'      => 'required|string|max:200',
            'estado'         => 'required|string|max:30',
            'observaciones'  => 'required|string|max:250',
        ]);

        // crea una donacion
        Donacion::create($validado);

        // redirige al index y un mensaje de correcto
        return redirect()->route('donaciones.index')->with('success', 'Donacion creada correctamente');
    }
}