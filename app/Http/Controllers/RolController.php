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

        return response()->json([
            'roles' => $roles
        ], 200);
    }

    // genera un formulario en blanco para la pagina
    public function create()
    {
        return response()->json([
            'message' => 'Formulario de creación de rol'
        ], 200);
    }

    public function store(Request $request)
    {
        // funcion que extrae lo que viene de la pagina y valida que sea correcta
        $validado = $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
        ]);

        // un nuevo rol validado es añadido en la DB
        $rol = Rol::create($validado);

        return response()->json([
            'message' => 'Rol creado correctamente',
            'data' => $rol
        ], 201);
    }

    // metodfo editar
    public function edit($id)
    {
        $rol = Rol::findOrFail($id);

        return response()->json([
            'rol' => $rol
        ], 200);
    }

    // metodo ubdete
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

        return response()->json([
            'message' => 'Rol actualizado correctamente.',
            'data' => $rol
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $rol = Rol::findOrFail($id);

        return response()->json([
            'rol' => $rol
        ], 200);
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

        return response()->json([
            'message' => 'Rol eliminado correctamente.'
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $roles = Rol::onlyTrashed()->paginate(5);

        return response()->json([
            'roles' => $roles
        ], 200);
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

        return response()->json([
            'message' => 'Rol restaurado correctamente.'
        ], 200);
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
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.'
            ], 422);
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($rol->usuarios()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: el rol tiene usuarios asignados.'
            ], 422);
        }

        $rol->forceDelete();

        return response()->json([
            'message' => 'Rol eliminado definitivamente.'
        ], 200);
    }
}