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

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $rol = Rol::findOrFail($id);

        return view('roles.show', compact('rol'));
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $roles = Rol::onlyTrashed()->paginate(5);

        return view('roles.trashed', compact('roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $rol = Rol::onlyTrashed()->findOrFail($id);
        $rol->restore();

        return redirect()->route('roles.trashed')->with('success', 'Rol restaurado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $rol = Rol::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (!$rol->trashed()) {
            return redirect()->route('roles.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($rol->usuarios()->count() > 0) {
            return redirect()->route('roles.trashed')->with('error', 'No se puede eliminar: el rol tiene usuarios asignados.');
        }

        $rol->forceDelete();

        return redirect()->route('roles.trashed')->with('success', 'Rol eliminado definitivamente.');
    }
}
