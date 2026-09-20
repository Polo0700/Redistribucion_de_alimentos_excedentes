<?php

namespace App\Http\Controllers;

use App\Models\AccionImportante;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AccionImportanteController extends Controller
{
    // trae las acciones importantes de la base de datos con su usuario de 5 en 5
    public function index()
    {
        $acciones = AccionImportante::with('usuario')->paginate(5);

        // retorna la pagina del index de acciones
        return view('acciones-importantes.index', compact('acciones'));
    }

    // obtiene los usuarios y los ordena por nombre y los mete a usuarios
    public function create()
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        // retorna la vista de creacion de acciones
        return view('acciones-importantes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_usuario'    => 'nullable|exists:usuarios,id',
            'accion'        => 'required|string|max:100',
            'descripcion'   => 'nullable|string|max:250',
            'tabla_afectada'=> 'required|string|max:80',
            'fecha_hora'    => 'required|date',
            'ip_origen'     => 'required|ip|max:45',
        ]);

        // crea una accion importante
        AccionImportante::create($validado);

        // redirige al index y un mensaje de correcto
        return redirect()->route('acciones.index')->with('success', 'Accion importante creada correctamente');
    }
}