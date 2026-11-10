<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAdminController extends Controller
{
    public function login(): JsonResponse
    {
        return response()->json([
            'message' => 'Método no permitido. Use POST /api/v1/admin/login',
        ], 405);
    }

    public function in(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:30'],
            'password' => ['required', 'min:8'],
        ]);

        if (! Auth::guard('admin')->attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $admin = Auth::guard('admin')->user();

        if (! $admin->activo) {
            Auth::guard('admin')->logout();

            return response()->json(['message' => 'Cuenta deshabilitada'], 403);
        }

        $token = $admin->createToken('api-admin')->plainTextToken;

        return response()->json([
            'token' => $token,
            'admin' => [
                'id' => $admin->id,
                'username' => $admin->username,
                'nivel' => $admin->nivel,
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
}
