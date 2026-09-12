<?php

namespace App\Http\Controllers;

use App\Models\Alimento;

class AlimentoController extends Controller
{
    public function index()
    {
        $alimentos = Alimento::with('categoria')->paginate(5);

        return view('alimentos.index', compact('alimentos'));
    }
}