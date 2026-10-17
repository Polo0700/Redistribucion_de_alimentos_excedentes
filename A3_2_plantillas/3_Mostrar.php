<?php

// ============================================================
// PLANTILLA: MÉTODO MOSTRAR (consulta individual, solo lectura)
// ============================================================
// COPIA el método show() dentro de tu controlador real
// (ej. AlimentoController) y completa el cuerpo.
// ============================================================

namespace App\Http\Controllers;

use App\Models\Alimento;

class PlantillaMostrarController extends Controller
{
    public function show($id)
    {
        // TODO: cargar el registro con sus relaciones y mostrar la vista
        // $registro = Alimento::with('categoria')->findOrFail($id);
        // return view('alimentos.show', compact('registro'));

        return null;
    }
}