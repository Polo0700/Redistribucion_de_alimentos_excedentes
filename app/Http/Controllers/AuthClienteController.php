<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientePerfilRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\CuentaAcceso;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthClienteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTRO (API con Sanctum)
    |--------------------------------------------------------------------------
    | Endpoint público: crea el Usuario y su CuentaAcceso asignando
    | automáticamente el rol de Cliente.
    */
    public function registrar(ClienteRegistroRequest $request): JsonResponse
    {
        $datos = $request->validated();

        // el rol de Cliente se asigna en el backend (nunca viene del payload)
        $rol = Rol::firstOrCreate(
            ['nombre' => 'Cliente'],
            ['descripcion' => 'Cliente registrado', 'estado' => 1]
        );

        $usuario = Usuario::create([
            'id_rol' => $rol->id,
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'correo' => $datos['correo'],
            'telefono' => $datos['telefono'],
            'direccion' => $datos['direccion'],
            'fecha_registro' => now(),
            'estado' => 1,
        ]);

        $cuenta = CuentaAcceso::create([
            'id_usuario' => $usuario->id,
            'proveedor' => 'Correo',
            'identificador_externo' => $datos['correo'],
            'contrasena_hash' => Hash::make($datos['password']),
            'fecha_ultimo_acceso' => now(),
        ]);

        // token de acceso firmado: createToken('client-token')
        $token = $cuenta->createToken('client-token')->plainTextToken;

        return response()->json([
            'message' => 'Cliente registrado correctamente.',
            'usuario' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido,
                'correo' => $usuario->correo,
                'rol' => $rol->nombre,
            ],
            'token' => $token,
        ], 201);
    }

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

        $token = $cuenta->createToken('client-token')->plainTextToken;

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

    /*
    |--------------------------------------------------------------------------
    | PERFIL (datos propios)
    |--------------------------------------------------------------------------
    | Consulta y actualización del perfil del CLIENTE autenticado:
    | nunca recibe un id, opera siempre sobre el usuario del token.
    */
    public function perfil(Request $request): JsonResponse
    {
        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        return response()->json([
            'usuario' => new UsuarioResource($cuenta->usuario),
        ], 200);
    }

    public function actualizarPerfil(ClientePerfilRequest $request): JsonResponse
    {
        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        $cuenta->usuario->update($request->validated());

        return response()->json([
            'usuario' => new UsuarioResource($cuenta->usuario->fresh()),
        ], 200);
    }
}
