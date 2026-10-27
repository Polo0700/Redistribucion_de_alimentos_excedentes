<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // listado paginado de roles
    public function index()
    {
        $roles = Rol::paginate(5);

        return response()->json(['roles' => $roles], 200);
    }

    // datos del formulario de creación (roles no requiere catálogos)
    public function create()
    {
        return response()->json(['message' => 'Formulario de creación de roles'], 200);
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
        ]);

        Rol::create($validado);

        return response()->json(['message' => 'Rol creado correctamente'], 201);
    }

    // datos del formulario de edición
    public function edit($id)
    {
        $rol = Rol::findOrFail($id);

        return response()->json(['rol' => $rol], 200);
    }

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

        return response()->json(['message' => 'Rol actualizado correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $rol = Rol::findOrFail($id);

        return response()->json(['rol' => $rol], 200);
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

        return response()->json(['message' => 'Rol eliminado correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $roles = Rol::onlyTrashed()->paginate(5);

        return response()->json(['roles' => $roles], 200);
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

        return response()->json(['message' => 'Rol restaurado correctamente.'], 200);
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
        if (! $rol->trashed()) {
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($rol->usuarios()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar: el rol tiene usuarios asignados.'], 409);
        }

        $rol->forceDelete();

        return response()->json(['message' => 'Rol eliminado definitivamente.'], 200);
    }
}
