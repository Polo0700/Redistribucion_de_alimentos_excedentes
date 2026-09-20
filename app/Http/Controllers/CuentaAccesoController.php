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
}