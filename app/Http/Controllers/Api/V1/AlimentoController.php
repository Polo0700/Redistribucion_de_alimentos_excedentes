<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Alimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AlimentoController extends Controller
{
    public function index(Request $request)
    {
        $porPagina = max(1, min((int) $request->query('por_pagina', 10), 50));

        $alimentos = Alimento::with('categoria')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $query->where('nombre', 'like', '%'.$request->query('buscar').'%');
            })
            ->when($request->filled('id_categoria'), function ($query) use ($request) {
                $query->where('id_categoria', $request->query('id_categoria'));
            })
            ->when($request->filled('estado'), function ($query) use ($request) {
                $query->where('estado', $request->query('estado'));
            })
            ->orderBy('id_alimento')
            ->paginate($porPagina);

        return response()->json([
            'success' => true,
            'message' => 'Alimentos consultados correctamente',
            'data' => $alimentos,
            'errors' => null,
        ], 200);
    }

    public function show($id)
    {
        $alimento = Alimento::with('categoria')->find($id);

        if (! $alimento) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento solicitado no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alimento consultado correctamente',
            'data' => $alimento,
            'errors' => null,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_categoria' => [
                'required',
                'integer',
                Rule::exists('categorias_alimento', 'id_categoria')->whereNull('deleted_at'),
            ],
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], $this->mensajes());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos',
                'data' => null,
                'errors' => $validator->errors(),
            ], 422);
        }

        $datos = $validator->validated();
        unset($datos['imagen']);

        $alimento = Alimento::create($datos);

        if ($request->hasFile('imagen')) {
            $ruta = $request->file('imagen')->store('alimentos', 'public');
            $alimento->update(['imagen' => $ruta]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alimento creado correctamente',
            'data' => $alimento->load('categoria'),
            'errors' => null,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $alimento = Alimento::find($id);

        if (! $alimento) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento solicitado no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        $parcial = $request->isMethod('patch');

        $reglas = [
            'id_categoria' => [
                $parcial ? 'sometimes' : 'required',
                'integer',
                Rule::exists('categorias_alimento', 'id_categoria')->whereNull('deleted_at'),
            ],
            'nombre' => ($parcial ? 'sometimes|required' : 'required').'|string|max:80',
            'descripcion' => ($parcial ? 'sometimes|required' : 'required').'|string|max:200',
            'estado' => ($parcial ? 'sometimes|required' : 'required').'|boolean',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $validator = Validator::make($request->all(), $reglas, $this->mensajes());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos',
                'data' => null,
                'errors' => $validator->errors(),
            ], 422);
        }

        $datos = $validator->validated();
        unset($datos['imagen']);

        $alimento->update($datos);

        if ($request->hasFile('imagen')) {
            $imagenAnterior = $alimento->imagen;
            $ruta = $request->file('imagen')->store('alimentos', 'public');

            $alimento->update(['imagen' => $ruta]);

            if ($imagenAnterior) {
                Storage::disk('public')->delete($imagenAnterior);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Alimento actualizado correctamente',
            'data' => $alimento->fresh()->load('categoria'),
            'errors' => null,
        ], 200);
    }

    public function destroy($id)
    {
        $alimento = Alimento::find($id);

        if (! $alimento) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento solicitado no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        if ($alimento->imagen) {
            Storage::disk('public')->delete($alimento->imagen);
        }

        $alimento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alimento eliminado correctamente',
            'data' => null,
            'errors' => null,
        ], 200);
    }

    public function storeImagen(Request $request, $id)
    {
        $alimento = Alimento::find($id);

        if (! $alimento) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento solicitado no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], $this->mensajes());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos',
                'data' => null,
                'errors' => $validator->errors(),
            ], 422);
        }

        $imagenAnterior = $alimento->imagen;
        $ruta = $request->file('imagen')->store('alimentos', 'public');

        $alimento->update(['imagen' => $ruta]);

        if ($imagenAnterior) {
            Storage::disk('public')->delete($imagenAnterior);
        }

        return response()->json([
            'success' => true,
            'message' => 'Imagen del alimento actualizada correctamente',
            'data' => $alimento->fresh(),
            'errors' => null,
        ], 200);
    }

    public function destroyImagen($id)
    {
        $alimento = Alimento::find($id);

        if (! $alimento) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento solicitado no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        if (! $alimento->imagen) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento no tiene una imagen asociada',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        Storage::disk('public')->delete($alimento->imagen);
        $alimento->update(['imagen' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Imagen del alimento eliminada correctamente',
            'data' => null,
            'errors' => null,
        ], 200);
    }

    private function mensajes(): array
    {
        return [
            'id_categoria.required' => 'La categoría del alimento es obligatoria.',
            'id_categoria.integer' => 'La categoría del alimento debe ser un número entero.',
            'id_categoria.exists' => 'La categoría seleccionada no existe.',
            'nombre.required' => 'El nombre del alimento es obligatorio.',
            'nombre.string' => 'El nombre del alimento debe ser texto.',
            'nombre.max' => 'El nombre del alimento no debe superar los 80 caracteres.',
            'descripcion.required' => 'La descripción del alimento es obligatoria.',
            'descripcion.string' => 'La descripción del alimento debe ser texto.',
            'descripcion.max' => 'La descripción del alimento no debe superar los 200 caracteres.',
            'estado.required' => 'El estado del alimento es obligatorio.',
            'estado.boolean' => 'El estado del alimento debe ser verdadero o falso.',
            'imagen.required' => 'La imagen es obligatoria.',
            'imagen.image' => 'El archivo enviado debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpg, jpeg, png o webp.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            'imagen.uploaded' => 'La imagen no se pudo cargar. Verifique el tamaño permitido por el servidor.',
        ];
    }
}
