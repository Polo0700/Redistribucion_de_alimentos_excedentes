<?php

namespace App\Http\Controllers;

use App\Models\Alimento;
use App\Models\CategoriaAlimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlimentoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // trae los alimentos de la base de datos con su categoría de 5 en 5
        $alimentos = Alimento::with('categoria')->paginate(5);

        // retorna la vista index de alimentos
        return view('alimentos.index', compact('alimentos'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO DE CREACIÓN
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        // obtiene las categorías ordenadas por nombre
        $categorias = CategoriaAlimento::orderBy('nombre')->get();

        // retorna la vista de creación de alimentos
        return view('alimentos.create', compact('categorias'));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVO ALIMENTO
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // validación
        $validado = $request->validate([
            'id_categoria' => 'required|exists:categorias_alimento,id_categoria',
            'nombre'       => 'required|string|max:80',
            'descripcion'  => 'required|string|max:200',
            'estado'       => 'required|boolean',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // crear alimento
        $alimento = Alimento::create($validado);

        // guardar imagen si existe
        if ($request->hasFile('imagen')) {
            $extension = $request->file('imagen')->extension();
            $nombreImagen = 'Alimento_' . $alimento->id_alimento . '_1.' . $extension;
            $ruta = $request->file('imagen')->storeAs('alimentos', $nombreImagen, 'public');
            $alimento->update(['imagen' => $ruta]);
        }

        return redirect()->route('alimentos.index')->with('success', 'Alimento creado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO DE EDICIÓN
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $alimento = Alimento::findOrFail($id);
        $categorias = CategoriaAlimento::orderBy('nombre')->get();

        return view('alimentos.edit', compact('alimento', 'categorias'));
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR ALIMENTO
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $alimento = Alimento::findOrFail($id);
        $imagenAnterior = $alimento->imagen;

        // validación
        $validado = $request->validate([
            'id_categoria' => 'required|exists:categorias_alimento,id_categoria',
            'nombre'       => 'required|string|max:80',
            'descripcion'  => 'nullable|string|max:200',
            'estado'       => 'required|boolean',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // actualizar datos
        $alimento->update([
            'id_categoria' => $validado['id_categoria'],
            'nombre'       => $validado['nombre'],
            'descripcion'  => $validado['descripcion'],
            'estado'       => $validado['estado'],
        ]);

        // si hay nueva imagen
        if ($request->hasFile('imagen')) {
            $extension = $request->file('imagen')->extension();
            $nombreImagen = 'Alimento_' . $alimento->id_alimento . '_1.' . $extension;
            $rutaNueva = $request->file('imagen')->storeAs('alimentos', $nombreImagen, 'public');

            $alimento->update(['imagen' => $rutaNueva]);

            // eliminar imagen anterior si existe y es distinta
            if ($imagenAnterior && $imagenAnterior !== $rutaNueva) {
                Storage::disk('public')->delete($imagenAnterior);
            }
        }

        return redirect()->route('alimentos.index')->with('success', 'Alimento actualizado correctamente.');
    }
}
