<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthAdminController extends Controller
{
    // muestra el formulario de login
    public function login(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('admin.login');
    }

    // checa usuario y contraseña y que la cuenta este activa
    public function in(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:30'],
            'password' => ['required', 'min:8'],
        ]);

        if (! Auth::guard('admin')->attempt($credentials)) {
            return back()
                ->with('aviso', 'Usuario o contraseña incorrectos')
                ->onlyInput('username');
        }

        $cuenta = Auth::guard('admin')->user();

        if (! $cuenta->activo) {
            Auth::guard('admin')->logout();

            return back()
                ->with('aviso', 'La cuenta está deshabilitada')
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('exito', 'Inicio de sesión exitoso');
    }

    // cierra la sesion del admin
    public function out(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('exito', 'Cierre de sesión exitoso');
    }
}
