<?php

// ============================================================
// PLANTILLA: MÉTODO LISTADO DE ELIMINADOS (papelera)
// ============================================================
// COPIA el método trashed() dentro de tu controlador real.
// onlyTrashed() trae SOLO los registros eliminados lógicamente.
// ============================================================

namespace App\Http\Controllers;

use App\Models\Alimento;

class PlantillaListadoEliminadosController extends Controller
{
    public function trashed()
    {
        // TODO: traer los eliminados y mostrar la vista papelera
        // $registros = Alimento::onlyTrashed()->paginate(5);
        // return view('alimentos.trashed', compact('registros'));

        return null;
    }
}