<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdministradorRequest;
use App\Http\Resources\AdministradorResource;
use App\Models\Administrador;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdministradorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return AdministradorResource::collection(Administrador::orderBy('id')->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVO ADMINISTRADOR
    |--------------------------------------------------------------------------
    */
    public function store(AdministradorRequest $request): JsonResponse
    {
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
            ->additional(['message' => 'Administrador creado correctamente.'])
            ->response()
            ->setStatusCode(201);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id): JsonResponse
    {
        return (new AdministradorResource(Administrador::findOrFail($id)))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR ADMINISTRADOR
    |--------------------------------------------------------------------------
    */
    public function update(AdministradorRequest $request, $id): JsonResponse
    {
        $administrador = Administrador::findOrFail($id);
        $fotoAnterior = $administrador->foto;

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
            ->additional(['message' => 'Administrador actualizado correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id): JsonResponse
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
        return AdministradorResource::collection(Administrador::onlyTrashed()->paginate(5));
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id): JsonResponse
    {
        $administrador = Administrador::onlyTrashed()->findOrFail($id);
        $administrador->restore();

        return (new AdministradorResource($administrador))
            ->additional(['message' => 'Administrador restaurado correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id): JsonResponse
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
