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
            'nivel' => 3,
        ]);
    }

    public function test_login_muestra_formulario(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_ingreso_valido_autentica_y_redirige_al_dashboard(): void
    {
        $this->crearAdmin();

        $respuesta = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => '12345678',
        ]);

        $respuesta->assertRedirect(route('dashboard'));
        $this->assertAuthenticated('admin');
    }

    public function test_contrasena_incorrecta_no_autentica(): void
    {
        $this->crearAdmin();

        $respuesta = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => 'clave-mala',
        ]);

        $respuesta->assertSessionHas('aviso');
        $this->assertGuest('admin');
    }

    public function test_cuenta_deshabilitada_no_autentica(): void
    {
        $this->crearAdmin(activo: false);

        $respuesta = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => '12345678',
        ]);

        $respuesta->assertSessionHas('aviso');
        $this->assertGuest('admin');
    }

    public function test_campos_requeridos(): void
    {
        $this->post('/admin/login', [])
            ->assertSessionHasErrors(['username', 'password']);
    }

    public function test_logout_cierra_sesion_y_redirige_al_login(): void
    {
        $this->crearAdmin();

        $this->post('/admin/login', [
            'username' => 'admin',
            'password' => '12345678',
        ]);

        $respuesta = $this->post('/admin/logout');

        $respuesta->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    }

    public function test_panel_sin_sesion_redirige_al_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('admin.login'));

        $this->get(route('alimentos.index'))->assertRedirect(route('admin.login'));

        $this->get(route('roles.index'))->assertRedirect(route('admin.login'));
    }

    public function test_panel_con_sesion_de_admin_accede(): void
    {
        $cuenta = $this->crearAdmin();
        $this->actingAs($cuenta, 'admin');

        $this->get(route('dashboard'))->assertOk();

        $this->get(route('alimentos.index'))->assertOk();
    }
}
