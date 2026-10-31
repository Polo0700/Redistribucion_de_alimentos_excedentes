<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdministradorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // trae los administradores de 5 en 5
        $administradores = Administrador::orderBy('id')->paginate(5);

        return response()->json([
            'administradores' => $administradores,
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO DE CREACIÓN
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        // niveles de acceso que acepta el servidor
        return response()->json([
            'niveles' => Administrador::NIVELES_PERMITIDOS,
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVO ADMINISTRADOR
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // validación: el nivel siempre se valida contra los valores permitidos
        $validado = $request->validate([
            'nombre_completo' => 'required|string|max:50',
            'username' => 'required|string|max:30|unique:administradores,username',
            'password' => 'required|string|min:8|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'activo' => 'nullable|boolean',
            'nivel' => 'required|integer|in:'.implode(',', Administrador::NIVELES_PERMITIDOS),
        ], [
            'nivel.in' => 'El nivel debe ser 1, 2 o 3.',
        ]);

        // la contraseña se guarda cifrada y activo arranca en true si no lo envían
        $validado['password'] = Hash::make($validado['password']);
        $validado['activo'] = $request->boolean('activo', true);

        unset($validado['foto']);

        $administrador = Administrador::create($validado);

        // guardar la foto si existe
        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->extension();
            $nombreFoto = 'Administrador_'.$administrador->id.'.'.$extension;
            $ruta = $request->file('foto')->storeAs('administradores', $nombreFoto, 'public');
            $administrador->update(['foto' => $ruta]);
        }

        return response()->json([
            'message' => 'Administrador creado correctamente.',
            'data' => $administrador,
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $administrador = Administrador::findOrFail($id);

        return response()->json([
            'administrador' => $administrador,
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO DE EDICIÓN
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $administrador = Administrador::findOrFail($id);

        return response()->json([
            'administrador' => $administrador,
            'niveles' => Administrador::NIVELES_PERMITIDOS,
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR ADMINISTRADOR
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $administrador = Administrador::findOrFail($id);
        $fotoAnterior = $administrador->foto;

        // validación (el username no puede repetirse, pero se ignora el propio)
        $validado = $request->validate([
            'nombre_completo' => 'required|string|max:50',
            'username' => ['required', 'string', 'max:30', Rule::unique('administradores', 'username')->ignore($administrador->id)],
            'password' => 'nullable|string|min:8|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'activo' => 'nullable|boolean',
            'nivel' => 'required|integer|in:'.implode(',', Administrador::NIVELES_PERMITIDOS),
        ], [
            'nivel.in' => 'El nivel debe ser 1, 2 o 3.',
        ]);

        $datos = [
            'nombre_completo' => $validado['nombre_completo'],
            'username' => $validado['username'],
            'activo' => $request->boolean('activo', true),
            'nivel' => (int) $validado['nivel'],
        ];

        // la contraseña solo se cambia si se envía una nueva
        if (! empty($validado['password'])) {
            $datos['password'] = Hash::make($validado['password']);
        }

        $administrador->update($datos);

        // si hay foto nueva, se guarda y se borra la anterior
        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->extension();
            $nombreFoto = 'Administrador_'.$administrador->id.'_'.now()->timestamp.'.'.$extension;
            $rutaNueva = $request->file('foto')->storeAs('administradores', $nombreFoto, 'public');

            $administrador->update(['foto' => $rutaNueva]);

            if ($fotoAnterior && $fotoAnterior !== $rutaNueva && Storage::disk('public')->exists($fotoAnterior)) {
                Storage::disk('public')->delete($fotoAnterior);
            }
        }

        return response()->json([
            'message' => 'Administrador actualizado correctamente.',
            'data' => $administrador,
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $administrador = Administrador::findOrFail($id);
        $administrador->delete();

        return response()->json([
            'message' => 'Administrador eliminado correctamente.',
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $administradores = Administrador::onlyTrashed()->paginate(5);

        return response()->json([
            'administradores' => $administradores,
        ], 200);
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

        return response()->json([
            'message' => 'Administrador restaurado correctamente.',
        ], 200);
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
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.',
            ], 422);
        }

        // eliminar la foto asociada del almacenamiento
        if ($administrador->foto && Storage::disk('public')->exists($administrador->foto)) {
            Storage::disk('public')->delete($administrador->foto);
        }

        $administrador->forceDelete();

        return response()->json([
            'message' => 'Administrador eliminado definitivamente.',
        ], 200);
    }
}
