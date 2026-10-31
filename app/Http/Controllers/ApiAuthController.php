<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    // crea un token de sanctum con usuario y contrasena del admin
    public function login(Request $request): JsonResponse
    {
        $validado = $request->validate([
            'username' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
        ]);

        $admin = Administrador::where('username', $validado['username'])->first();

        if (! $admin || ! Hash::check($validado['password'], $admin->password)) {
            return response()->json(['message' => 'Usuario o contrasena incorrectos'], 401);
        }

        if (! $admin->activo) {
            return response()->json(['message' => 'La cuenta esta deshabilitada'], 403);
        }

        $token = $admin->createToken('api')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'administrador' => $admin,
        ]);
    }

    // revoca el token que trae la peticion
    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'Sesion cerrada, token revocado']);
    }
}
