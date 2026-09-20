<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{   
    // permite cargar las paginas y los elementos de las mismas
    public function index()
    {
        $roles = Rol::paginate(5);

        return view('roles.index', compact('roles'));
    }
    // genera un formulario en blanco para la pagina
    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        // funcion que extrae lo que viene de la pagina y valida que sea correcta
        $validado = $request->validate([
            'nombre'      => 'required|string|max:50',
            'descripcion' => 'required|string|max:200',
            'estado'      => 'required|boolean',
        ]);

        // un nuevo rol validado es añadido en la DB
        Rol::create($validado);
        // retorna la pagina index y te da un mensaje de exito
        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente');
    }
    // metodfo editar
    public function edit($id)
    {
        $rol = Rol::findOrFail($id);
        return view('roles.edit', compact('rol'));
    }

    //metodo ubdete
    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);

        $request->validate([
            'nombre'      => 'required|max:50',
            'descripcion' => 'nullable|max:200',
            'estado'      => 'required|boolean',
        ]);

        $rol->update([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado'      => $request->estado,
        ]);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }
}
