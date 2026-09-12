<?php

namespace App\Http\Controllers;

use App\Models\CuentaAcceso;

class CuentaAccesoController extends Controller
{
    public function index()
    {
        $cuentas = CuentaAcceso::with('usuario')->paginate(5);

        return view('cuentas-acceso.index', compact('cuentas'));
    }
}