<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CategoriaAlimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $porPagina = max(1, min((int) $request->query('por_pagina', 10), 50));

        $categorias = CategoriaAlimento::query()
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $query->where('nombre', 'like', '%'.$request->query('buscar').'%');
            })
            ->when($request->filled('estado'), function ($query) use ($request) {
                $query->where('estado', $request->query('estado'));
            })
            ->orderBy('id_categoria')
            ->paginate($porPagina);

        return response()->json([
            'success' => true,
            'message' => 'Categorías consultadas correctamente',
            'data' => $categorias,
            'errors' => null,
        ], 200);
    }

    public function show($id)
    {
        $categoria = CategoriaAlimento::find($id);

        if (! $categoria) {
            return response()->json([
                'success' => false,
                'message' => 'La categoría solicitada no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Categoría consultada correctamente',
            'data' => $categoria,
            'errors' => null,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
        ], $this->mensajes());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos',
                'data' => null,
                'errors' => $validator->errors(),
            ], 422);
        }

        $categoria = CategoriaAlimento::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada correctamente',
            'data' => $categoria,
            'errors' => null,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $categoria = CategoriaAlimento::find($id);

        if (! $categoria) {
            return response()->json([
                'success' => false,
                'message' => 'La categoría solicitada no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        $parcial = $request->isMethod('patch');

        $reglas = [
            'nombre' => ($parcial ? 'sometimes|required' : 'required').'|string|max:80',
            'descripcion' => ($parcial ? 'sometimes|required' : 'required').'|string|max:200',
            'estado' => ($parcial ? 'sometimes|required' : 'required').'|boolean',
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

        $categoria->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada correctamente',
            'data' => $categoria->fresh(),
            'errors' => null,
        ], 200);
    }

    public function destroy($id)
    {
        $categoria = CategoriaAlimento::find($id);

        if (! $categoria) {
            return response()->json([
                'success' => false,
                'message' => 'La categoría solicitada no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada correctamente',
            'data' => null,
            'errors' => null,
        ], 200);
    }

    private function mensajes(): array
    {
        return [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.string' => 'El nombre de la categoría debe ser texto.',
            'nombre.max' => 'El nombre de la categoría no debe superar los 80 caracteres.',
            'descripcion.required' => 'La descripción de la categoría es obligatoria.',
            'descripcion.string' => 'La descripción de la categoría debe ser texto.',
            'descripcion.max' => 'La descripción de la categoría no debe superar los 200 caracteres.',
            'estado.required' => 'El estado de la categoría es obligatorio.',
            'estado.boolean' => 'El estado de la categoría debe ser verdadero o falso.',
        ];
    }
}
