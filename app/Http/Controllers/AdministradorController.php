<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdministradorController extends Controller
{
    // lista los administradores de 5 en 5
    public function index(Request $request)
    {
        $administradores = Administrador::orderBy('id')->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['administradores' => $administradores], 200);
        }

        return view('administradores.index', compact('administradores'));
    }

    // formulario para crear un administrador (niveles 2 y 3)
    public function create(Request $request)
    {
        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json([], 200);
        }

        return view('administradores.create');
    }

    public function store(Request $request)
    {
        // el nivel SIEMPRE se valida en el servidor contra los valores permitidos
        $validado = $request->validate([
            'nombre_completo' => 'required|string|max:50',
            'username' => 'required|string|max:30|unique:administradores,username',
            'password' => 'required|string|min:6|max:255',
            'foto' => 'nullable|image|max:2048',
            'activo' => 'nullable|boolean',
            'nivel' => 'required|integer|in:' . implode(',', Administrador::NIVELES_PERMITIDOS),
        ], [
            'nivel.in' => 'El nivel debe ser 1, 2 o 3.',
        ]);

        $validado['password'] = Hash::make($validado['password']);
        $validado['activo'] = $request->boolean('activo');

        if ($request->hasFile('foto')) {
            $validado['foto'] = $request->file('foto')->store('administradores', 'public');
        } else {
            unset($validado['foto']);
        }

        Administrador::create($validado);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Administrador creado correctamente.'], 201);
        }

        return redirect()->route('administradores.index')->with('success', 'Administrador creado correctamente.');
    }

    // retorna la vista show de un administrador, solo lectura
    public function show(Request $request, $id)
    {
        $administrador = Administrador::findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['administrador' => $administrador], 200);
        }

        return view('administradores.show', compact('administrador'));
    }

    // formulario para editar un administrador (niveles 2 y 3)
    public function edit(Request $request, $id)
    {
        $administrador = Administrador::findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['administrador' => $administrador], 200);
        }

        return view('administradores.edit', compact('administrador'));
    }

    public function update(Request $request, $id)
    {
        $administrador = Administrador::findOrFail($id);

        $validado = $request->validate([
            'nombre_completo' => 'required|string|max:50',
            'username' => ['required', 'string', 'max:30', Rule::unique('administradores', 'username')->ignore($administrador->id)],
            'password' => 'nullable|string|min:6|max:255',
            'foto' => 'nullable|image|max:2048',
            'activo' => 'nullable|boolean',
            'nivel' => 'required|integer|in:' . implode(',', Administrador::NIVELES_PERMITIDOS),
        ], [
            'nivel.in' => 'El nivel debe ser 1, 2 o 3.',
        ]);

        // no se puede desactivar ni degradar su propia cuenta
        if ($administrador->id === auth('admin')->id()) {
            if (! $request->boolean('activo')) {
                // en api responde 403
                if ($request->wantsJson()) {
                    return response()->json(['message' => 'No puede desactivar su propia cuenta.'], 403);
                }

                return redirect()->route('administradores.index')->with('error', 'No puede desactivar su propia cuenta.');
            }

            if ((int) $request->nivel !== (int) $administrador->nivel) {
                // en api responde 403
                if ($request->wantsJson()) {
                    return response()->json(['message' => 'No puede cambiar su propio nivel.'], 403);
                }

                return redirect()->route('administradores.index')->with('error', 'No puede cambiar su propio nivel.');
            }
        }

        $datos = [
            'nombre_completo' => $validado['nombre_completo'],
            'username' => $validado['username'],
            'activo' => $request->boolean('activo'),
            'nivel' => (int) $validado['nivel'],
        ];

        // la contraseña solo se cambia si se envia una nueva
        if (! empty($validado['password'])) {
            $datos['password'] = Hash::make($validado['password']);
        }

        if ($request->hasFile('foto')) {
            $datos['foto'] = $request->file('foto')->store('administradores', 'public');
        }

        $administrador->update($datos);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Administrador actualizado correctamente.'], 200);
        }

        return redirect()->route('administradores.index')->with('success', 'Administrador actualizado correctamente.');
    }

    // borra logicamente al administrador (solo nivel 3)
    public function destroy(Request $request, $id)
    {
        $administrador = Administrador::findOrFail($id);

        // no se puede eliminar su propia cuenta
        if ($administrador->id === auth('admin')->id()) {
            // en api responde 403
            if ($request->wantsJson()) {
                return response()->json(['message' => 'No puede eliminar su propia cuenta.'], 403);
            }

            return redirect()->route('administradores.index')->with('error', 'No puede eliminar su propia cuenta.');
        }

        $administrador->delete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Administrador eliminado correctamente.'], 200);
        }

        return redirect()->route('administradores.index')->with('success', 'Administrador eliminado correctamente.');
    }

    // trae los eliminados logicamente de 5 en 5
    public function trashed(Request $request)
    {
        $administradores = Administrador::onlyTrashed()->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['administradores' => $administradores], 200);
        }

        return view('administradores.trashed', compact('administradores'));
    }

    // regresa el administrador a la tabla
    public function restore(Request $request, $id)
    {
        $administrador = Administrador::onlyTrashed()->findOrFail($id);
        $administrador->restore();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Administrador restaurado correctamente.'], 200);
        }

        return redirect()->route('administradores.trashed')->with('success', 'Administrador restaurado correctamente.');
    }

    // borra el registro de la tabla por completo
    public function forceDestroy(Request $request, $id)
    {
        $administrador = Administrador::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $administrador->trashed()) {
            // en api responde 409
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
            }

            return redirect()->route('administradores.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        $administrador->forceDelete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Administrador eliminado definitivamente.'], 200);
        }

        return redirect()->route('administradores.trashed')->with('success', 'Administrador eliminado definitivamente.');
    }
}
