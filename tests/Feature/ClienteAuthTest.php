<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\CuentaAcceso;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClienteAuthTest extends TestCase
{
    use RefreshDatabase;

    private function crearCliente(string $correo = 'cliente@test.com'): CuentaAcceso
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
            'correo' => $correo,
            'telefono' => '3311223344',
            'direccion' => 'Calle Uno 123',
            'fecha_registro' => now(),
            'estado' => 1,
        ]);

        return CuentaAcceso::create([
            'id_usuario' => $usuario->id,
            'proveedor' => 'Correo',
            'identificador_externo' => $correo,
            'contrasena_hash' => Hash::make('12345678'),
            'fecha_ultimo_acceso' => now(),
        ]);
    }

    private function tokenDe(CuentaAcceso $cuenta): string
    {
        return $cuenta->createToken('test')->plainTextToken;
    }

    public function test_login_con_get_devuelve_405(): void
    {
        $this->getJson('/api/v1/cliente/login')->assertStatus(405);
    }

    public function test_login_valido_devuelve_token(): void
    {
        $this->crearCliente();

        $this->postJson('/api/v1/cliente/login', [
            'correo' => 'cliente@test.com',
            'password' => '12345678',
        ])
            ->assertOk()
            ->assertJsonStructure(['token', 'usuario' => ['id', 'nombre', 'apellido', 'correo']]);
    }

    public function test_password_incorrecta_devuelve_401(): void
    {
        $this->crearCliente();

        $this->postJson('/api/v1/cliente/login', [
            'correo' => 'cliente@test.com',
            'password' => 'clave-mala',
        ])->assertStatus(401);
    }

    public function test_campos_requeridos_devuelve_422(): void
    {
        $this->postJson('/api/v1/cliente/login', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['correo', 'password']);
    }

    public function test_perfil_sin_token_devuelve_401(): void
    {
        $this->crearCliente();

        $this->getJson('/api/v1/cliente/perfil')->assertStatus(401);
    }

    public function test_perfil_con_token_devuelve_200(): void
    {
        $cuenta = $this->crearCliente();

        $this->withToken($this->tokenDe($cuenta))
            ->getJson('/api/v1/cliente/perfil')
            ->assertOk()
            ->assertJsonPath('usuario.correo', 'cliente@test.com');
    }

    public function test_actualizar_perfil_devuelve_200(): void
    {
        $cuenta = $this->crearCliente();

        $this->withToken($this->tokenDe($cuenta))
            ->patchJson('/api/v1/cliente/perfil', [
                'nombre' => 'Ana Maria',
                'apellido' => 'Perez Lopez',
                'telefono' => '3399887766',
                'direccion' => 'Calle Dos 456',
            ])
            ->assertOk()
            ->assertJsonPath('usuario.nombre', 'Ana Maria');

        $this->assertDatabaseHas('usuarios', [
            'id' => $cuenta->id_usuario,
            'nombre' => 'Ana Maria',
        ]);
    }

    public function test_logout_revoca_el_token(): void
    {
        $cuenta = $this->crearCliente();
        $token = $this->tokenDe($cuenta);

        $this->withToken($token)->postJson('/api/v1/cliente/logout')->assertOk();

        $this->app['auth']->forgetGuards();

        $this->withToken($token)->getJson('/api/v1/cliente/perfil')->assertStatus(401);
    }

    public function test_cliente_no_puede_aceder_al_area_admin(): void
    {
        $cuenta = $this->crearCliente();

        $this->withToken($this->tokenDe($cuenta))
            ->getJson('/api/v1/admin/alimentos')
            ->assertStatus(403);
    }

    public function test_admin_no_puede_aceder_al_area_cliente(): void
    {
        $admin = Administrador::create([
            'nombre_completo' => 'Admin API',
            'username' => 'admin.api',
            'password' => Hash::make('12345678'),
            'activo' => true,
            'nivel' => 3,
        ]);

        $this->withToken($admin->createToken('test')->plainTextToken)
            ->getJson('/api/v1/cliente/perfil')
            ->assertStatus(403);
    }

    public function test_catalogo_de_alimentos_devuelve_200(): void
    {
        $cuenta = $this->crearCliente();

        $this->withToken($this->tokenDe($cuenta))
            ->getJson('/api/v1/cliente/alimentos')
            ->assertOk()
            ->assertJsonStructure(['alimentos']);
    }

    public function test_pagina_de_login_esta_disponible(): void
    {
        $this->get('/cliente')->assertOk();
    }

    public function test_pagina_de_panel_esta_disponible(): void
    {
        $this->get('/cliente/panel')->assertOk();
    }
}
