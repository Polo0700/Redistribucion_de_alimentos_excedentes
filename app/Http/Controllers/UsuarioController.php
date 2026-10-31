<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // trae los usuarios de la base de datos con su rol de 5 en 5
    public function index(Request $request)
    {
        $usuarios = Usuario::with('rol')->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['usuarios' => $usuarios], 200);
        }

        // retorna la pagina del index de usuarios
        return view('usuarios.index', compact('usuarios'));
    }

    // obtiene los roles y los ordena por nombre y los mete a roles
    public function create(Request $request)
    {
        $roles = Rol::orderBy('nombre')->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['roles' => $roles], 200);
        }

        // retorna la vista de creacion de usuarios
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // en api la foto es opcional, en web es obligatoria
        $foto = $request->wantsJson() ? 'nullable' : 'required';

        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_rol' => 'required|exists:roles,id',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|email|max:150',
            'telefono' => 'required|string|max:100',
            'direccion' => 'required|string|max:200',
            'foto' => $foto . '|image|mimes:jpeg,png,jpg,webp|max:2048',
            'fecha_registro' => 'required|date',
            'estado' => 'required|boolean',
        ]);

        // guarda el archivo en disco y deja la ruta en $validado
        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->extension();
            $nombreFoto = 'Usuario_' . time() . '_' . uniqid() . '.' . $extension;
            $validado['foto'] = $request->file('foto')->storeAs('usuarios', $nombreFoto, 'public');
        }

        // crea un usuario (con la ruta de la foto ya adentro)
        Usuario::create($validado);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Usuario creado correctamente'], 201);
        }

        // redirige al index y un mensaje de correcto
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    // metodo edit()
    public function edit(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        // solo roles activos
        $roles = Rol::where('estado', true)->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['usuario' => $usuario, 'roles' => $roles], 200);
        }

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    // metodo update()
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

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Usuario actualizado correctamente.'], 200);
        }

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    // retorna la vista show del usuario
    public function show(Request $request, $id)
    {
        $usuario = Usuario::with('rol')->findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['usuario' => $usuario], 200);
        }

        return view('usuarios.show', compact('usuario'));
    }

    // borra el usuario logicamente
    public function destroy(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Usuario eliminado correctamente.'], 200);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    // trae los eliminados logicamente de 5 en 5
    public function trashed(Request $request)
    {
        $usuarios = Usuario::onlyTrashed()->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['usuarios' => $usuarios], 200);
        }

        return view('usuarios.trashed', compact('usuarios'));
    }

    // regresa el usuario a la tabla
    public function restore(Request $request, $id)
    {
        $usuario = Usuario::onlyTrashed()->findOrFail($id);
        $usuario->restore();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Usuario restaurado correctamente.'], 200);
        }

        return redirect()->route('usuarios.trashed')->with('success', 'Usuario restaurado correctamente.');
    }

    // borra el registro de la tabla por completo
    public function forceDestroy(Request $request, $id)
    {
        $usuario = Usuario::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $usuario->trashed()) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
            }

            return redirect()->route('usuarios.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // validacion de relaciones: si otra tabla lo usa, se cancela
        if ($usuario->cuentasAcceso()->count() > 0) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No se puede eliminar: el usuario tiene cuentas de acceso registradas.'], 409);
            }

            return redirect()->route('usuarios.trashed')->with('error', 'No se puede eliminar: el usuario tiene cuentas de acceso registradas.');
        }

        if ($usuario->donaciones()->count() > 0) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No se puede eliminar: el usuario tiene donaciones registradas.'], 409);
            }

            return redirect()->route('usuarios.trashed')->with('error', 'No se puede eliminar: el usuario tiene donaciones registradas.');
        }

        

        

        if ($usuario->accionesImportantes()->count() > 0) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No se puede eliminar: el usuario tiene acciones importantes registradas.'], 409);
            }

            return redirect()->route('usuarios.trashed')->with('error', 'No se puede eliminar: el usuario tiene acciones importantes registradas.');
        }

        $usuario->forceDelete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Usuario eliminado definitivamente.'], 200);
        }

        return redirect()->route('usuarios.trashed')->with('success', 'Usuario eliminado definitivamente.');
    }
}
