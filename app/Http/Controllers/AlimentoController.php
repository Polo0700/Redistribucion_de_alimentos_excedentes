<?php

namespace App\Http\Controllers;

use App\Models\Alimento;
use App\Models\CategoriaAlimento;
use Illuminate\Http\Request;

class AlimentoController extends Controller
{
    public function index()
    {
        // trae los alimentos de la base de datos en una zona de tipo categoria de 5 en 5 
        $alimentos = Alimento::with('categoria')->paginate(5);

        // y esto retorna la vista index de alimentos
        return view('alimentos.index', compact('alimentos'));
    }
    
    public function create()
    {
        // esta obtiene y las categoriza e organiza por nombre provenientes de la DB
        $categorias = CategoriaAlimento::orderBy('nombre')->get();

        // esta retorna la vista del formulario de creacion de alimentos
        return view('alimentos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        // esto pide datos y verifica que sean respondidos de la informacion del alimento

        $validado = $request->validate([
            'id_categoria' => 'required|exists:categorias_alimento,id_categoria',
            'nombre'       => 'required|string|max:80',
            'descripcion'  => 'required|string|max:200',
            'estado'       => 'required|boolean',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // crea un tipo de alimento
        $alimento = Alimento::create($validado);
        // comprueba si la peticion tiene una imagen
        if ($request->hasFile('imagen')) {
            // si tiene imagen entonces obtiene su extension
            $extension = $request->file('imagen')->extension();
            // aqui pone un orden alimento_-> _1 junto la extension para tener el nombre completo y su extension
            $nombreImagen = 'Alimento_' . $alimento->id_alimento . '_1.' . $extension;
            // toma la imagen y la almacena en la carpeta alimentos y lo pone publico
            $ruta = $request->file('imagen')->storeAs('alimentos', $nombreImagen, 'public');
            // mete el string de la $ruta en imagen para que se guarde
            $alimento->update(['imagen' => $ruta]);
        }
        // retorna una redireccion a la ruta index del tipo alimentos y da un mensaje de correcto
        return redirect()->route('alimentos.index')->with('success', 'Alimento creado correctamente');
    }
}