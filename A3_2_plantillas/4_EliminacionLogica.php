<?php

// ============================================================
// PLANTILLA: MÉTODO ELIMINACIÓN LÓGICA (borrado lógico)
// ============================================================
// COPIA el método destroy() dentro de tu controlador real.
// $registro->delete() solo pone deleted_at, NO borra de la BD.
// ============================================================

namespace App\Http\Controllers;

use App\Models\Alimento;

class PlantillaEliminacionLogicaController extends Controller
{
    public function destroy($id)
    {
        // TODO: buscar, eliminar lógicamente y redirigir con mensaje
        $registro = Alimento::findOrFail($id);
        $registro->delete();
        return redirect()->route('alimentos.index')->with('success', 'Registro eliminado correctamente');
    }
}