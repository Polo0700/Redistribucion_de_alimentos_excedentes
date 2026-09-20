<?php

namespace App\Http\Controllers;

use App\Models\CategoriaAlimento;
use Illuminate\Http\Request;

class CategoriaAlimentoController extends Controller
{
    // trae las categorias de la base de datos de 5 en 5
    public function index()
    {
        $categorias = CategoriaAlimento::paginate(5);

        // retorna la pagina del index de categorias
        return view('categorias.index', compact('categorias'));
    }

    // genera un formulario en blanco para la pagina
    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'nombre'      => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado'      => 'required|boolean',
        ]);

        // crea una categoria
        CategoriaAlimento::create($validado);

        // redirige al index y un mensaje de correcto
        return redirect()->route('categorias.index')->with('success', 'Categoria creada correctamente');
    }
}