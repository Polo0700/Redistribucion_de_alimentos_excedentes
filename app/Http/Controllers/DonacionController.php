<?php

namespace App\Http\Controllers;

use App\Models\Donacion;

class DonacionController extends Controller
{
    public function index()
    {
        $donaciones = Donacion::with('usuario')->paginate(5);

        return view('donaciones.index', compact('donaciones'));
    }
}
