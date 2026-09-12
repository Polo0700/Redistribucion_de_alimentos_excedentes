<?php

namespace App\Http\Controllers;

use App\Models\Carrito;

class CarritoController extends Controller
{
    public function index()
    {
        $carritos = Carrito::with('usuario')->paginate(5);

        return view('carritos.index', compact('carritos'));
    }
}