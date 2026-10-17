<?php

// ============================================================
// PLANTILLA: VALIDACIÓN DE RELACIONES (bloque del borrado físico)
// ============================================================
// Este bloque va DENTRO de forceDestroy(), ANTES de borrar la
// imagen y ANTES de forceDelete(). No es un método aparte.
//
// Lógica: por cada relación hija (HasMany) del registro, contar
// registros dependientes. Si hay >0, cancelar con mensaje claro.
//
// Para Alimento, según el DER, las relaciones son:
//   $registro->detallesDonacion()->count()
//   $registro->detallesCarrito()->count()
//   $registro->deseosDetalle()->count()
// ============================================================

namespace App\Http\Controllers;

use App\Models\Alimento;

class PlantillaValidacionRelacionesController extends Controller
{
    public function forzarBorradoFisico(Alimento $registro)
    {
        // TODO (bloque de ejemplo, adáptalo dentro de forceDestroy):
        // if ($registro->detallesDonacion()->count() > 0) {
        //     return back()->with('error', 'No se puede eliminar: el alimento está en Detalles de Donación.');
        // }

        return null;
    }
}