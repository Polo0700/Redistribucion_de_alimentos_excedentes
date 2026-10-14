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
        // return view('usuarios.index', compact('usuarios'));
        return response()->json(['usuarios'=>$usuarios], 200);
    }

    // obtiene los roles y los ordena por nombre y los mete a roles
    public function create()
    {
        $roles = Rol::orderBy('nombre')->get();

        // retorna la vista de creacion de usuarios
        // return view('usuarios.create', compact('roles'));
        return response()->json(['roles' => $roles], 200);
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
        // return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
        return response()->json(['message' => 'Usuario creado correctamente'], 201);
    }

    // método edit()
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);

        // solo roles activos
        $roles = Rol::where('estado', true)->get();

        // return view('usuarios.edit', compact('usuario', 'roles'));
        return response()->json(['usuario' => $usuario, 'roles' => $roles], 200);
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

        // return redirect()
        //     ->route('usuarios.index')
        //     ->with('success', 'Usuario actualizado correctamente.');
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

        // return view('usuarios.show', compact('usuario'));
        return response()->json(['usuario'=>$usuario],200);
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

        // return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
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

        // return view('usuarios.trashed', compact('usuarios'));
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

        // return redirect()->route('usuarios.trashed')->with('success', 'Usuario restaurado correctamente.');
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
        if (!$usuario->trashed()) {
            // return redirect()->route('usuarios.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
            return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($usuario->cuentasAcceso()->count() > 0) {
            // return redirect()->route('usuarios.trashed')->with('error', 'No se puede eliminar: el usuario tiene cuentas de acceso registradas.');
            return response()->json(['error' => 'No se puede eliminar: el usuario tiene cuentas de acceso registradas.'], 409);
        }

        if ($usuario->donaciones()->count() > 0) {
            // return redirect()->route('usuarios.trashed')->with('error', 'No se puede eliminar: el usuario tiene donaciones registradas.');
            return response()->json(['error' => 'No se puede eliminar: el usuario tiene donaciones registradas.'], 409);
        }

        if ($usuario->carritos()->count() > 0) {
            // return redirect()->route('usuarios.trashed')->with('error', 'No se puede eliminar: el usuario tiene carritos registrados.');
            return response()->json(['error' => 'No se puede eliminar: el usuario tiene carritos registrados.'], 409);
        }

        if ($usuario->listasDeseos()->count() > 0) {
            // return redirect()->route('usuarios.trashed')->with('error', 'No se puede eliminar: el usuario tiene listas de deseos registradas.');
            return response()->json(['error' => 'No se puede eliminar: el usuario tiene listas de deseos registradas.'], 409);
        }

        if ($usuario->accionesImportantes()->count() > 0) {
            // return redirect()->route('usuarios.trashed')->with('error', 'No se puede eliminar: el usuario tiene acciones importantes registradas.');
            return response()->json(['error' => 'No se puede eliminar: el usuario tiene acciones importantes registradas.'], 409);
        }

        $usuario->forceDelete();

        // return redirect()->route('usuarios.trashed')->with('success', 'Usuario eliminado definitivamente.');
        return response()->json(['message' => 'Usuario eliminado definitivamente.'], 200);
    }
}
