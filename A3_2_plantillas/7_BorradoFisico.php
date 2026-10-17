<?php

// ============================================================
// PLANTILLA: MÉTODO BORRADO FÍSICO (eliminación definitiva)
// ============================================================
// COPIA el método forceDestroy() dentro de tu controlador real.
// ORDEN IMPORTANTE:
//  1. withTrashed()->findOrFail($id)  (obligatorio para hallar eliminados)
//  2. Validar que esté lógicamente eliminado (trashed())
//  3. Validar RELACIONES (ver 8_ValidacionRelaciones.php)
//  4. Borrar la IMAGEN del storage
//  5. forceDelete() y redirigir con mensaje
// ============================================================

namespace App\Http\Controllers;

use App\Models\Alimento;
use Illuminate\Support\Facades\Storage;

class PlantillaBorradoFisicoController extends Controller
{
    public function forceDestroy($id)
    {
        // TODO:
        // $registro = Alimento::withTrashed()->findOrFail($id);
        // if (!$registro->trashed()) { return back()->with('error', '...'); }
        // if (VE RELACIONES > 0)    { return back()->with('error', '...'); }
        // if ($registro->imagen && Storage::disk('public')->exists($registro->imagen)) {
        //     Storage::disk('public')->delete($registro->imagen);
        // }
        // $registro->forceDelete();
        // return back()->with('success', 'Registro eliminado definitivamente.');

        return null;
    }
}