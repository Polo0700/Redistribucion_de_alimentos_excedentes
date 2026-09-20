<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // trae los usuarios de la base de datos con su rol de 5 en 5
    public function index()
    {
        $usuarios = Usuario::with('rol')->paginate(5);

        // retorna la pagina del index de usuarios
        return view('usuarios.index', compact('usuarios'));
    }

    // obtiene los roles y los ordena por nombre y los mete a roles
    public function create()
    {
        $roles = Rol::orderBy('nombre')->get();

        // retorna la vista de creacion de usuarios
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_rol'         => 'required|exists:roles,id',
            'nombre'         => 'required|string|max:100',
            'apellido'       => 'required|string|max:100',
            'correo'         => 'required|email|max:150',
            'telefono'       => 'required|string|max:100',
            'direccion'      => 'required|string|max:200',
            'fecha_registro' => 'required|date',
            'estado'         => 'required|boolean',
        ]);

        // crea un usuario
        Usuario::create($validado);

        // redirige al index y un mensaje de correcto
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }
}