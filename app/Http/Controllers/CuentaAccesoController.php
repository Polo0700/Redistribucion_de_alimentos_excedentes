<?php

namespace App\Http\Controllers;

use App\Models\CuentaAcceso;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CuentaAccesoController extends Controller
{
    public function index(Request $request)
    {
        // toma las cuentas que quiere acceso del tipo usuario en la DB
        $cuentas = CuentaAcceso::with('usuario')->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['cuentas' => $cuentas], 200);
        }

        // retorna la pagina del index
        return view('cuentas-acceso.index', compact('cuentas'));
    }

    public function create(Request $request)
    {
        // obtiene los usuarios y los ordena por nombre y los mete a usuarios
        $usuarios = Usuario::orderBy('nombre')->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['usuarios' => $usuarios], 200);
        }

        // retorna la vista de creacion de cuentas
        return view('cuentas-acceso.create', compact('usuarios'));
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
        CuentaAcceso::create($validado);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Cuenta de acceso creada correctamente'], 201);
        }

        // redirige al index y un mensaje de correcto
        return redirect()->route('cuentas-acceso.index')->with('success', 'Cuenta de acceso creada correctamente');
    }

    public function edit(Request $request, $id)
    {
        $cuenta = CuentaAcceso::findOrFail($id);

        $usuarios = Usuario::where('estado', true)->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['cuenta' => $cuenta, 'usuarios' => $usuarios], 200);
        }

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

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Cuenta de acceso actualizada correctamente.'], 200);
        }

        return redirect()
            ->route('cuentas-acceso.index')
            ->with('success', 'Cuenta de acceso actualizada correctamente.');
    }

    // retorna la vista show de la cuenta
    public function show(Request $request, $id)
    {
        $cuenta = CuentaAcceso::with('usuario')->findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['cuenta' => $cuenta], 200);
        }

        return view('cuentas-acceso.show', compact('cuenta'));
    }

    // borra la cuenta de acceso logicamente
    public function destroy(Request $request, $id)
    {
        $cuenta = CuentaAcceso::findOrFail($id);
        $cuenta->delete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Cuenta de acceso eliminada correctamente.'], 200);
        }

        return redirect()->route('cuentas-acceso.index')->with('success', 'Cuenta de acceso eliminada correctamente.');
    }

    // trae los eliminados logicamente de 5 en 5
    public function trashed(Request $request)
    {
        $cuentas = CuentaAcceso::onlyTrashed()->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['cuentas' => $cuentas], 200);
        }

        return view('cuentas-acceso.trashed', compact('cuentas'));
    }

    // regresa la cuenta a la tabla
    public function restore(Request $request, $id)
    {
        $cuenta = CuentaAcceso::onlyTrashed()->findOrFail($id);
        $cuenta->restore();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Cuenta de acceso restaurada correctamente.'], 200);
        }

        return redirect()->route('cuentas-acceso.trashed')->with('success', 'Cuenta de acceso restaurada correctamente.');
    }

    // borra el registro de la tabla por completo
    public function forceDestroy(Request $request, $id)
    {
        $cuenta = CuentaAcceso::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $cuenta->trashed()) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
            }

            return redirect()->route('cuentas-acceso.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // sin tablas dependientes: se elimina directamente
        $cuenta->forceDelete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Cuenta de acceso eliminada definitivamente.'], 200);
        }

        return redirect()->route('cuentas-acceso.trashed')->with('success', 'Cuenta de acceso eliminada definitivamente.');
    }
}
