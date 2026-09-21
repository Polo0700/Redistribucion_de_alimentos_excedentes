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
        // retorna la pagina del index
        return view('cuentas-acceso.index', compact('cuentas'));
    }

    public function create()
    {
        // obtiene los usuarios y los ordena por nombre y los mete a usuarios
        $usuarios = Usuario::orderBy('nombre')->get();
        // retorna la vista de creacion de cuentas
        return view('cuentas-acceso.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_usuario'          => 'required|exists:usuarios,id',
            'proveedor'           => 'required|string|max:30',
            'identificador_externo' => 'required|string|max:150',
            'contrasena'          => 'required|string|min:6|max:255',
            'fecha_ultimo_acceso' => 'required|date',
        ]);
        // toma de validado la contraseña y la convierte a hash y la pone como parametro en validado
        $validado['contrasena_hash'] = Hash::make($validado['contrasena']);
        // quita la contraseña sin hashear
        unset($validado['contrasena']);
        // crea una cuenta
        CuentaAcceso::create($validado);
        // redirige al index y un mensaje de correcto
        return redirect()->route('cuentas-acceso.index')->with('success', 'Cuenta de acceso creada correctamente');
    }

    public function edit($id)
{
    $cuenta = CuentaAcceso::findOrFail($id);

    $usuarios = Usuario::where('estado', true)->get();

    return view(
        'cuentas-acceso.edit',
        compact('cuenta', 'usuarios')
    );
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

return redirect()
        ->route('cuentas-acceso.index')
        ->with('success', 'Cuenta de acceso actualizada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR (consulta individual, solo lectura)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $cuenta = CuentaAcceso::with('usuario')->findOrFail($id);

        return view('cuentas-acceso.show', compact('cuenta'));
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

        return redirect()->route('cuentas-acceso.index')->with('success', 'Cuenta de acceso eliminada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE REGISTROS ELIMINADOS (papelera)
    |--------------------------------------------------------------------------
    */
    public function trashed()
    {
        $cuentas = CuentaAcceso::onlyTrashed()->paginate(5);

        return view('cuentas-acceso.trashed', compact('cuentas'));
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

        return redirect()->route('cuentas-acceso.trashed')->with('success', 'Cuenta de acceso restaurada correctamente.');
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
        if (!$cuenta->trashed()) {
            return redirect()->route('cuentas-acceso.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // sin tablas dependientes: se elimina directamente
        $cuenta->forceDelete();

        return redirect()->route('cuentas-acceso.trashed')->with('success', 'Cuenta de acceso eliminada definitivamente.');
    }
}