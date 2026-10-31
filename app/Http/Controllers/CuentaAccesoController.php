<?php

namespace App\Http\Controllers;

use App\Models\CuentaAcceso;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CuentaAccesoController extends Controller
{
    public function index()
    {
        // toma las cuentas que quiere acceso del tipo usuario en la DB
        $cuentas = CuentaAcceso::with('usuario')->paginate(5);

        return response()->json([
            'cuentas' => $cuentas
        ], 200);
    }

    public function create()
    {
        // obtiene los usuarios y los ordena por nombre y los mete a usuarios
        $usuarios = Usuario::orderBy('nombre')->get();

        return response()->json([
            'usuarios' => $usuarios
        ], 200);
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'proveedor' => 'required|string|max:30',
            'identificador_externo' => 'required|string|max:150',
            'contrasena' => 'required|string|min:6|max:255',
            'fecha_ultimo_acceso' => 'required|date',
        ]);

        // toma de validado la contraseña y la convierte a hash y la pone como parametro en validado
        $validado['contrasena_hash'] = Hash::make($validado['contrasena']);

        // quita la contraseña sin hashear
        unset($validado['contrasena']);

        // crea una cuenta
        $cuenta = CuentaAcceso::create($validado);

        return response()->json([
            'message' => 'Cuenta de acceso creada correctamente',
            'data' => $cuenta
        ], 201);
    }

    public function edit($id)
    {
        $cuenta = CuentaAcceso::findOrFail($id);

        $usuarios = Usuario::where('estado', true)->get();

        return response()->json([
            'cuenta' => $cuenta,
            'usuarios' => $usuarios
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $cuenta = CuentaAcceso::findOrFail($id);

        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'proveedor' => 'required|max:30',
            'identificador_externo' => 'required|max:150',
            'contrasena_hash' => 'required|max:255',
            'fecha_ultimo_acceso' => 'required|date',
        ]);

        $cuenta->update([
            'id_usuario' => $request->id_usuario,
            'proveedor' => $request->proveedor,
            'identificador_externo' => $request->identificador_externo,
            'contrasena_hash' => $request->contrasena_hash,
            'fecha_ultimo_acceso' => $request->fecha_ultimo_acceso,
        ]);

        return response()->json([
            'message' => 'Cuenta de acceso actualizada correctamente.',
            'data' => $cuenta
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $cuenta = CuentaAcceso::with('usuario')->findOrFail($id);

        return response()->json([
            'cuenta' => $cuenta
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $cuenta = CuentaAcceso::findOrFail($id);
        $cuenta->delete();

        return response()->json([
            'message' => 'Cuenta de acceso eliminada correctamente.'
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $cuentas = CuentaAcceso::onlyTrashed()->paginate(5);

        return response()->json([
            'cuentas' => $cuentas
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | RESTAURAR REGISTRO
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $cuenta = CuentaAcceso::onlyTrashed()->findOrFail($id);
        $cuenta->restore();

        return response()->json([
            'message' => 'Cuenta de acceso restaurada correctamente.'
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO FÍSICO (solo para registros lógicamente eliminados)
    |--------------------------------------------------------------------------
    */
    public function forceDestroy($id)
    {
        $cuenta = CuentaAcceso::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $cuenta->trashed()) {
            return response()->json([
                'message' => 'Primero debe aplicar el borrado lógico al registro.'
            ], 422);
        }

        // sin tablas dependientes: se elimina directamente
        $cuenta->forceDelete();

        return response()->json([
            'message' => 'Cuenta de acceso eliminada definitivamente.'
        ], 200);
    }
}
