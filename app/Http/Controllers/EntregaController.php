<?php

namespace App\Http\Controllers;

use App\Models\Entrega;

class EntregaController extends Controller
{
    public function index()
    {
        $entregas = Entrega::with('solicitud')->paginate(5);

        return view('entregas.index', compact('entregas'));
    }
}