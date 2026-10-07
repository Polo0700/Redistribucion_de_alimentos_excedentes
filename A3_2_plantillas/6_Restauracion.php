<?php

// ============================================================
// PLANTILLA: MÉTODO RESTAURACIÓN
// ============================================================
// COPIA el método restore() dentro de tu controlador real.
// OJO: se usa onlyTrashed() porque un registro eliminado no lo
// encuentra findOrFail() normal. restore() vuelve a estado activo.
// ============================================================

namespace App\Http\Controllers;

use App\Models\Alimento;

class PlantillaRestauracionController extends Controller
{
    public function restore($id)
    {
        // TODO: buscar entre los eliminados, restaurar y redirigir
        // $registro = Alimento::onlyTrashed()->findOrFail($id);
        // $registro->restore();
        // return redirect()->route('alimentos.trashed')
        //     ->with('success', 'Registro restaurado correctamente.');

        return null;
    }
}