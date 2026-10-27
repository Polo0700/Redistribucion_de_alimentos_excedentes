<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // listado paginado de usuarios con su rol
    public function index()
    {
        $usuarios = Usuario::with('rol')->paginate(5);

        return response()->json(['usuarios' => $usuarios], 200);
    }

    // catálogo de roles para el formulario de creación
    public function create()
    {
        $roles = Rol::orderBy('nombre')->get();

        return response()->json(['roles' => $roles], 200);
    }

    public function store(Request $request)
    {
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

        Usuario::create($validado);

        return response()->json(['message' => 'Usuario creado correctamente'], 201);
    }

    // datos del usuario y solo roles activos para el formulario de edición
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);

        $roles = Rol::where('estado', true)->get();

        return response()->json(['usuario' => $usuario, 'roles' => $roles], 200);
    }

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

        return response()->json(['message' => 'Usuario actualizado correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $usuario = Usuario::with('rol')->findOrFail($id);

        return response()->json(['usuario' => $usuario], 200);
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

        return response()->json(['message' => 'Usuario eliminado correctamente.'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $usuarios = Usuario::onlyTrashed()->paginate(5);

        return response()->json(['usuarios' => $usuarios], 200);
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

        return response()->json(['message' => 'Usuario restaurado correctamente.'], 200);
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
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($usuario->cuentasAcceso()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar: el usuario tiene cuentas de acceso registradas.'], 409);
        }

        if ($usuario->donaciones()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar: el usuario tiene donaciones registradas.'], 409);
        }

        if ($usuario->accionesImportantes()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar: el usuario tiene acciones importantes registradas.'], 409);
        }

        $usuario->forceDelete();

        return response()->json(['message' => 'Usuario eliminado definitivamente.'], 200);
    }
}
