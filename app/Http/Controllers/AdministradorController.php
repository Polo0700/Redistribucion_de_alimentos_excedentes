<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdministradorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO (consulta - niveles 1, 2 y 3)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $administradores = Administrador::orderBy('id')->paginate(5);

        return view('administradores.index', compact('administradores'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR (nivel 2 y 3)
    |--------------------------------------------------------------------------
    */
    public function create()
    {
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

        return redirect()->route('administradores.index')->with('success', 'Administrador creado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | CONSULTA INDIVIDUAL (solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $administrador = Administrador::findOrFail($id);

        return view('administradores.show', compact('administrador'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDITAR (nivel 2 y 3)
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $administrador = Administrador::findOrFail($id);

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

        // un administrador no puede desactivarse ni degradarse a sí mismo
        if ($administrador->id === auth('admin')->id()) {
            if (! $request->boolean('activo')) {
                return redirect()->route('administradores.index')->with('error', 'No puede desactivar su propia cuenta.');
            }

            if ((int) $request->nivel !== (int) $administrador->nivel) {
                return redirect()->route('administradores.index')->with('error', 'No puede cambiar su propio nivel.');
            }
        }

        $datos = [
            'nombre_completo' => $validado['nombre_completo'],
            'username' => $validado['username'],
            'activo' => $request->boolean('activo'),
            'nivel' => (int) $validado['nivel'],
        ];

        // la contraseña solo se cambia si se envía una nueva
        if (! empty($validado['password'])) {
            $datos['password'] = Hash::make($validado['password']);
        }

        if ($request->hasFile('foto')) {
            $datos['foto'] = $request->file('foto')->store('administradores', 'public');
        }

        $administrador->update($datos);

        return redirect()->route('administradores.index')->with('success', 'Administrador actualizado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO (nivel 3)
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $administrador = Administrador::findOrFail($id);

        // un administrador no puede eliminarse a sí mismo
        if ($administrador->id === auth('admin')->id()) {
            return redirect()->route('administradores.index')->with('error', 'No puede eliminar su propia cuenta.');
        }

        $administrador->delete();

        return redirect()->route('administradores.index')->with('success', 'Administrador eliminado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $administradores = Administrador::onlyTrashed()->paginate(5);

        return view('administradores.trashed', compact('administradores'));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $administrador = Administrador::onlyTrashed()->findOrFail($id);
        $administrador->restore();

        return redirect()->route('administradores.trashed')->with('success', 'Administrador restaurado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $administrador = Administrador::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $administrador->trashed()) {
            return redirect()->route('administradores.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        $administrador->forceDelete();

        return redirect()->route('administradores.trashed')->with('success', 'Administrador eliminado definitivamente.');
    }
}
