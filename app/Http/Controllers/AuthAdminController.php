<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthAdminController extends Controller
{
    /**
     * Muestra el formulario de ingreso al área administrativa.
     */
    public function login(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('admin.login');
    }

    /**
     * Autentica contra cuentas_acceso y exige el rol Administrador.
     */
    public function in(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'identificador_externo' => ['required', 'email', 'max:150'],
            'password' => ['required', 'min:4'],
        ]);

        if (! Auth::guard('admin')->attempt($credentials)) {
            return back()
                ->withErrors([
                    'identificador_externo' => 'Usuario o contraseña incorrectos',
                ])
                ->onlyInput('identificador_externo');
        }

        $cuenta = Auth::guard('admin')->user();

        if ($cuenta->usuario?->rol?->nombre !== 'Administrador') {
            Auth::guard('admin')->logout();

            return back()
                ->withErrors([
                    'identificador_externo' => 'Esta cuenta no tiene permisos de administrador',
                ])
                ->onlyInput('identificador_externo');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Cierra la sesión del guard admin.
     */
    public function out(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
