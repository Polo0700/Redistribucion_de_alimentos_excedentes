<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Http\Requests\AdminResourceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Http\Resources\AdministradorResource;

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

        return AdministradorResource::collection($administradores);
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
    // se usa el AdminResourceRequest para validar los datos de entrada
    public function store(AdminResourceRequest $request)
    {
        // validación: el nivel siempre se valida contra los valores permitidos
        $validado = $request->validated();

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

        return (new AdministradorResource($administrador))
            ->additional(['message'=> 'Administrador creado correctamente.'])
            ->response()
            ->setStatusCode(201);
        }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $administrador = Administrador::findOrFail($id);

        return new AdministradorResource($administrador);
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
    public function update(AdminResourceRequest $request, $id)
    {
        $administrador = Administrador::findOrFail($id);
        $fotoAnterior = $administrador->foto;

        // validación ya hecha por AdminResourceRequest (rules() con ignore del propio id)
        $validado = $request->validated();

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

        return (new AdministradorResource($administrador))
            ->additional(['message'=> 'Administrador actualizado correctamente.'])
            ->response();
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
