<?php

namespace App\Http\Controllers;

use App\Models\ListaDeseo;

class ListaDeseoController extends Controller
{
    public function index()
    {
        $listas = ListaDeseo::with('usuario')->paginate(5);

        return view('listas-deseos.index', compact('listas'));
    }
}