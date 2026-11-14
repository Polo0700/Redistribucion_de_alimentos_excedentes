<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\CuentaAcceso;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdmin(bool $activo = true): Administrador
    {
        return Administrador::create([
            'nombre_completo' => 'Jorge Moreno',
            'username' => 'admin',
            'password' => Hash::make('12345678'),
            'activo' => $activo,
        ]);
    }

    /**
     * Crea un cliente (no administrador) con token Sanctum, para comprobar
     * que el middleware EnsureEsAdmin devuelve 403 en el área administrativa.
     */
    private function tokenDeCliente(): string
    {
        $rol = Rol::create([
            'nombre' => 'Cliente',
            'descripcion' => 'Cliente registrado',
            'estado' => 1,
        ]);

        $usuario = Usuario::create([
            'id_rol' => $rol->id,
            'nombre' => 'Ana',
            'apellido' => 'Perez',
            'correo' => 'cliente@test.com',
            'telefono' => '3311223344',
            'direccion' => 'Calle Uno 123',
            'fecha_registro' => now(),
            'estado' => 1,
        ]);

        $cuenta = CuentaAcceso::create([
            'id_usuario' => $usuario->id,
            'proveedor' => 'Correo',
            'identificador_externo' => 'cliente@test.com',
            'contrasena_hash' => Hash::make('12345678'),
            'fecha_ultimo_acceso' => now(),
        ]);

        return $cuenta->createToken('test')->plainTextToken;
    }

    public function test_login_con_get_devuelve_405(): void
    {
        $this->getJson('/api/v1/admin/login')->assertStatus(405);
    }

    public function test_ingreso_valido_devuelve_token(): void
    {
        $this->crearAdmin();

        $this->postJson('/api/v1/admin/login', [
            'username' => 'admin',
            'password' => '12345678',
        ])
            ->assertOk()
            ->assertJsonStructure(['token', 'admin' => ['id', 'username', 'nivel']]);
    }

    public function test_contrasena_incorrecta_devuelve_401(): void
    {
        $this->crearAdmin();

        $this->postJson('/api/v1/admin/login', [
            'username' => 'admin',
            'password' => 'clave-mala',
        ])->assertStatus(401);
    }

    public function test_cuenta_deshabilitada_devuelve_403(): void
    {
        $this->crearAdmin(activo: false);

        $this->postJson('/api/v1/admin/login', [
            'username' => 'admin',
            'password' => '12345678',
        ])->assertStatus(403);
    }

    public function test_campos_requeridos_devuelve_422(): void
    {
        $this->postJson('/api/v1/admin/login', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'password']);
    }

    public function test_ruta_protegida_sin_token_devuelve_401(): void
    {
        $this->crearAdmin();

        $this->getJson('/api/v1/admin/alimentos')->assertStatus(401);
    }

    public function test_ruta_protegida_con_token_de_admin_devuelve_200(): void
    {
        $admin = $this->crearAdmin();
        $token = $admin->createToken('test')->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/admin/alimentos')->assertOk();
    }

    /**
     * Autorizacion (Opcion C): EnsureEsAdmin rechaza con 403 a cualquier
     * token que no pertenezca a un Administrador.
     */
    public function test_token_de_cliente_devuelve_403_en_rutas_admin(): void
    {
        $this->crearAdmin();
        $tokenCliente = $this->tokenDeCliente();

        $this->withToken($tokenCliente)
            ->getJson('/api/v1/admin/administradores')
            ->assertStatus(403)
            ->assertJsonPath('message', 'Acceso restringido al área administrativa');

        $this->withToken($tokenCliente)
            ->postJson('/api/v1/admin/administradores', [])
            ->assertStatus(403);

        $this->withToken($tokenCliente)
            ->getJson('/api/v1/admin/usuarios')
            ->assertStatus(403);
    }

    public function test_logout_revoca_el_token(): void
    {
        $admin = $this->crearAdmin();
        $token = $admin->createToken('test')->plainTextToken;

        $this->withToken($token)->postJson('/api/v1/admin/logout')->assertOk();

        $this->app['auth']->forgetGuards();

        $this->withToken($token)->getJson('/api/v1/admin/alimentos')->assertStatus(401);
    }
}
