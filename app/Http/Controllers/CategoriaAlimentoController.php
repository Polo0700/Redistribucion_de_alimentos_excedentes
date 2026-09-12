<?php

namespace App\Http\Controllers;

use App\Models\CategoriaAlimento;

class CategoriaAlimentoController extends Controller
{
    public function index()
    {
        $categorias = CategoriaAlimento::paginate(5);

        return view('categorias.index', compact('categorias'));
    }
}