<?php

namespace Tests\Feature;

use App\Models\Administrador;
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

    public function test_logout_revoca_el_token(): void
    {
        $admin = $this->crearAdmin();
        $token = $admin->createToken('test')->plainTextToken;

        $this->withToken($token)->postJson('/api/v1/admin/logout')->assertOk();

        $this->app['auth']->forgetGuards();

        $this->withToken($token)->getJson('/api/v1/admin/alimentos')->assertStatus(401);
    }
}
