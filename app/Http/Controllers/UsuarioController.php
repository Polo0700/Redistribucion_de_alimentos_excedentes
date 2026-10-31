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

        return response()->json([
            'usuarios' => $usuarios
        ], 200);
    }

    // obtiene los roles y los ordena por nombre y los mete a roles
    public function create()
    {
        $roles = Rol::orderBy('nombre')->get();

        return response()->json([
            'roles' => $roles
        ], 200);
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_rol' => 'required|exists:roles,id',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|email|max:150',
            'telefono' => 'required|string|max:100',
            'direccion' => 'required|string|max:200',
            'fecha_registro' => 'required|date',
            'estado' => 'required|boolean',
        ]);

        // crea un usuario
        $usuario = Usuario::create($validado);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'data' => $usuario
        ], 201);
    }

    // método edit()
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);

        // solo roles activos
        $roles = Rol::where('estado', true)->get();

        return response()->json([
            'usuario' => $usuario,
            'roles' => $roles
        ], 200);
    }

    // método update()
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'id_rol' => 'required|exists:roles,id',
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'correo' => 'required|email|max:150',
            'telefono' => 'required|max:100',
            'direccion' => 'required|max:200',
            'fecha_registro' => 'required|date',
            'estado' => 'required|boolean',
        ]);

        $usuario->update([
            'id_rol' => $request->id_rol,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_registro' => $request->fecha_registro,
            'estado' => $request->estado,
        ]);

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'data' => $usuario
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $usuario = Usuario::with('rol')->findOrFail($id);

        return response()->json([
            'usuario' => $usuario
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente.'
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $usuarios = Usuario::onlyTrashed()->paginate(5);

        return response()->json([
            'usuarios' => $usuarios
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $usuario = Usuario::onlyTrashed()->findOrFail($id);
        $usuario->restore();

        return response()->json([
            'message' => 'Usuario restaurado correctamente.'
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $usuario = Usuario::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $usuario->trashed()) {
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.'
            ], 422);
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($usuario->cuentasAcceso()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: el usuario tiene cuentas de acceso registradas.'
            ], 422);
        }

        if ($usuario->donaciones()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: el usuario tiene donaciones registradas.'
            ], 422);
        }

        if ($usuario->accionesImportantes()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: el usuario tiene acciones importantes registradas.'
            ], 422);
        }

        $usuario->forceDelete();

        return response()->json([
            'message' => 'Usuario eliminado definitivamente.'
        ], 200);
    }
}
