<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;

class SolicitudController extends Controller
{
    public function index()
    {
        $solicitudes = Solicitud::with('usuario')->paginate(5);

        return view('solicitudes.index', compact('solicitudes'));
    }
}