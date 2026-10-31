<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // trae los roles de la base de datos de 5 en 5
    public function index(Request $request)
    {
        $roles = Rol::paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['roles' => $roles], 200);
        }

        return view('roles.index', compact('roles'));
    }

    // genera un formulario en blanco para la pagina
    public function create(Request $request)
    {
        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Formulario de creación de roles'], 200);
        }

        return view('roles.create');
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
        ]);

        // crea un rol
        Rol::create($validado);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Rol creado correctamente'], 201);
        }

        // redirige al index y un mensaje de correcto
        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente');
    }

    // metodo edit()
    public function edit(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['rol' => $rol], 200);
        }

        return view('roles.edit', compact('rol'));
    }

    // metodo update()
    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:50',
            'descripcion' => 'nullable|max:200',
            'estado' => 'required|boolean',
        ]);

        $rol->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
        ]);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Rol actualizado correctamente.'], 200);
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }

    // retorna la vista show del rol
    public function show(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['rol' => $rol], 200);
        }

        return view('roles.show', compact('rol'));
    }

    // borra el rol logicamente
    public function destroy(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Rol eliminado correctamente.'], 200);
        }

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }

    // trae los eliminados logicamente de 5 en 5
    public function trashed(Request $request)
    {
        $roles = Rol::onlyTrashed()->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['roles' => $roles], 200);
        }

        return view('roles.trashed', compact('roles'));
    }

    // regresa el rol a la tabla
    public function restore(Request $request, $id)
    {
        $rol = Rol::onlyTrashed()->findOrFail($id);
        $rol->restore();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Rol restaurado correctamente.'], 200);
        }

        return redirect()->route('roles.trashed')->with('success', 'Rol restaurado correctamente.');
    }

    // borra el registro de la tabla por completo
    public function forceDestroy(Request $request, $id)
    {
        $rol = Rol::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $rol->trashed()) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
            }

            return redirect()->route('roles.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // validacion de relaciones: si otra tabla lo usa, se cancela
        if ($rol->usuarios()->count() > 0) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No se puede eliminar: el rol tiene usuarios asignados.'], 409);
            }

            return redirect()->route('roles.trashed')->with('error', 'No se puede eliminar: el rol tiene usuarios asignados.');
        }

        $rol->forceDelete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Rol eliminado definitivamente.'], 200);
        }

        return redirect()->route('roles.trashed')->with('success', 'Rol eliminado definitivamente.');
    }
}
