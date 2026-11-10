<?php

namespace App\Http\Controllers;

use App\Models\CuentaAcceso;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthClienteController extends Controller
{
    public function login(): JsonResponse
    {
        return response()->json([
            'message' => 'Método no permitido. Use POST /api/v1/cliente/login',
        ], 405);
    }

    public function in(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'correo' => ['required', 'email', 'max:150'],
            'password' => ['required', 'min:8'],
        ]);

        $cuenta = CuentaAcceso::where('proveedor', 'Correo')
            ->where('identificador_externo', $credentials['correo'])
            ->first();

        if (! $cuenta || ! Hash::check($credentials['password'], $cuenta->contrasena_hash)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $usuario = $cuenta->usuario;

        if (! $usuario->estado) {
            return response()->json(['message' => 'Cuenta deshabilitada'], 403);
        }

        $cuenta->update(['fecha_ultimo_acceso' => now()]);

        $token = $cuenta->createToken('api-cliente')->plainTextToken;

        return response()->json([
            'token' => $token,
            'usuario' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido,
                'correo' => $usuario->correo,
            ],
        ], 200);
    }

    public function out(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json(['message' => 'Sesión cerrada'], 200);
    }

    public function perfil(Request $request): JsonResponse
    {
        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        return response()->json(['usuario' => $cuenta->usuario], 200);
    }

    public function actualizarPerfil(Request $request): JsonResponse
    {
        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        $validado = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:100',
            'direccion' => 'required|string|max:200',
        ]);

        $cuenta->usuario->update($validado);

        return response()->json(['usuario' => $cuenta->usuario->fresh()], 200);
    }
}
