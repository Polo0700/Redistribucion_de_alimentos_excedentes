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

    // método edit()
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);

        // solo roles activos
        $roles = Rol::where('estado', true)->get();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    //   método update()
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'id_rol'         => 'required|exists:roles,id',
            'nombre'         => 'required|max:100',
            'apellido'       => 'required|max:100',
            'correo'         => 'required|email|max:150',
            'telefono'       => 'required|max:100',
            'direccion'      => 'required|max:200',
            'fecha_registro' => 'required|date',
            'estado'         => 'required|boolean',
        ]);

        $usuario->update([
            'id_rol'         => $request->id_rol,
            'nombre'         => $request->nombre,
            'apellido'       => $request->apellido,
            'correo'         => $request->correo,
            'telefono'       => $request->telefono,
            'direccion'      => $request->direccion,
            'fecha_registro' => $request->fecha_registro,
            'estado'         => $request->estado,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}
