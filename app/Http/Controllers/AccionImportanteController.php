<?php

namespace App\Http\Controllers;

use App\Models\AccionImportante;

class AccionImportanteController extends Controller
{
    public function index()
    {
        $acciones = AccionImportante::with('usuario')->paginate(5);

        return view('acciones-importantes.index', compact('acciones'));
    }
}