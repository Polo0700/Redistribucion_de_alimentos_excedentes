<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\AdministradorResource;
use App\Models\Administrador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN (API con Sanctum)
    |--------------------------------------------------------------------------
    */
    public function login(LoginRequest $request): JsonResponse
    {
        // busca al administrador por su username
        $administrador = Administrador::where('username', $request->username)->first();

        // si no existe o la contraseña no coincide, las credenciales son inválidas
        if (! $administrador || ! Hash::check($request->password, $administrador->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        // solo pueden iniciar sesión los administradores activos
        if (! $administrador->activo) {
            return response()->json([
                'message' => 'La cuenta del administrador está inactiva.',
            ], 403);
        }

        // revoca los tokens anteriores para no acumular sesiones
        $administrador->tokens()->delete();

        // genera el token de Sanctum y lo devuelve en texto plano
        $token = $administrador->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Sesión iniciada correctamente.',
            'administrador' => new AdministradorResource($administrador),
            'token' => $token,
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT (revoca el token actual)
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ], 200);
    }
}
