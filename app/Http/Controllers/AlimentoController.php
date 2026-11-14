<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlimentoRequest;
use App\Http\Resources\AlimentoResource;
use App\Models\Alimento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlimentoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    | Módulo administrativo: respuesta estandarizada con JsonResource.
    | Módulo cliente (Actividad 4.4): conserva su estructura original.
    */
    public function index(Request $request)
    {
        // trae los alimentos de la base de datos con su categoría de 5 en 5
        $alimentos = Alimento::with('categoria')->paginate(5);

        if ($request->routeIs('cliente.*')) {
            return response()->json([
                'alimentos' => $alimentos,
            ], 200);
        }

        return AlimentoResource::collection($alimentos)->response();
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVO ALIMENTO
    |--------------------------------------------------------------------------
    */
    public function store(AlimentoRequest $request): JsonResponse
    {
        $validado = $request->validated();

        // el campo imagen no es una columna de la tabla
        unset($validado['imagen']);

        // crear alimento
        $alimento = Alimento::create($validado);

        // guardar imagen si existe
        if ($request->hasFile('imagen')) {
            $extension = $request->file('imagen')->extension();
            $nombreImagen = 'Alimento_'.$alimento->id_alimento.'_1.'.$extension;
            $ruta = $request->file('imagen')->storeAs('alimentos', $nombreImagen, 'public');
            $alimento->update(['imagen' => $ruta]);
        }

        return (new AlimentoResource($alimento->fresh()))
            ->additional(['message' => 'Alimento creado correctamente.'])
            ->response()
            ->setStatusCode(201);
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR ALIMENTO
    |--------------------------------------------------------------------------
    */
    public function update(AlimentoRequest $request, $id): JsonResponse
    {
        $alimento = Alimento::findOrFail($id);
        $imagenAnterior = $alimento->imagen;

        $validado = $request->validated();

        unset($validado['imagen']);

        // actualizar datos
        $alimento->update($validado);

        // si hay nueva imagen
        if ($request->hasFile('imagen')) {
            $extension = $request->file('imagen')->extension();
            $nombreImagen = 'Alimento_'.$alimento->id_alimento.'_1.'.$extension;
            $rutaNueva = $request->file('imagen')->storeAs('alimentos', $nombreImagen, 'public');

            $alimento->update(['imagen' => $rutaNueva]);

            // eliminar imagen anterior si existe y es distinta
            if ($imagenAnterior && $imagenAnterior !== $rutaNueva) {
                Storage::disk('public')->delete($imagenAnterior);
            }
        }

        return (new AlimentoResource($alimento->fresh()))
            ->additional(['message' => 'Alimento actualizado correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    | Igual que index(): JsonResource en administrativo, estructura
    | original en el módulo cliente.
    */
    public function show(Request $request, $id): JsonResponse
    {
        $alimento = Alimento::with('categoria')->findOrFail($id);

        if ($request->routeIs('cliente.*')) {
            return response()->json([
                'alimento' => $alimento,
            ], 200);
        }

        return (new AlimentoResource($alimento))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id): JsonResponse
    {
        $alimento = Alimento::findOrFail($id);
        $alimento->delete();

        return response()->json([
            'message' => 'Alimento eliminado correctamente.',
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        return AlimentoResource::collection(Alimento::onlyTrashed()->paginate(5))->response();
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id): JsonResponse
    {
        $alimento = Alimento::onlyTrashed()->findOrFail($id);
        $alimento->restore();

        return (new AlimentoResource($alimento))
            ->additional(['message' => 'Alimento restaurado correctamente.'])
            ->response();
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id): JsonResponse
    {
        $alimento = Alimento::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminado lógicamente
        if (! $alimento->trashed()) {
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.',
            ], 422);
        }

        // validación de relaciones: si otra tabla lo usa, se cancela
        if ($alimento->detallesDonacion()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar: el alimento está registrado en Detalles de Donación.',
            ], 422);
        }

        // eliminar la imagen asociada del almacenamiento
        if ($alimento->imagen && Storage::disk('public')->exists($alimento->imagen)) {
            Storage::disk('public')->delete($alimento->imagen);
        }

        // eliminar el registro de la base de datos
        $alimento->forceDelete();

        return response()->json([
            'message' => 'Alimento eliminado físicamente junto con su imagen.',
        ], 200);
    }
}
