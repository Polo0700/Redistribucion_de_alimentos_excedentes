<?php

namespace Tests\Feature;

use App\Models\CuentaAcceso;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    private function crearCuentaAdmin(string $rolNombre = 'Administrador'): CuentaAcceso
    {
        $rol = Rol::create([
            'nombre' => $rolNombre,
            'descripcion' => 'Rol de prueba',
            'estado' => 1,
        ]);

        $usuario = Usuario::create([
            'id_rol' => $rol->id,
            'nombre' => 'Jorge',
            'apellido' => 'Moreno',
            'correo' => 'admin@test.com',
            'telefono' => '1234567890',
            'direccion' => 'Calle test 123',
            'fecha_registro' => now(),
            'estado' => 1,
        ]);

        return CuentaAcceso::create([
            'id_usuario' => $usuario->id,
            'proveedor' => 'Correo',
            'identificador_externo' => 'admin@test.com',
            'contrasena_hash' => bcrypt('12345678'),
            'fecha_ultimo_acceso' => now(),
        ]);
    }

    public function test_login_muestra_formulario(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_ingreso_valido_autentica_y_redirige_al_dashboard(): void
    {
        $this->crearCuentaAdmin();

        $respuesta = $this->post('/admin/login', [
            'identificador_externo' => 'admin@test.com',
            'password' => '12345678',
        ]);

        $respuesta->assertRedirect(route('dashboard'));
        $this->assertAuthenticated('admin');
    }

    public function test_contrasena_incorrecta_no_autentica(): void
    {
        $this->crearCuentaAdmin();

        $respuesta = $this->post('/admin/login', [
            'identificador_externo' => 'admin@test.com',
            'password' => 'clave-mala',
        ]);

        $respuesta->assertSessionHasErrors('identificador_externo');
        $this->assertGuest('admin');
    }

    public function test_cuenta_sin_rol_administrador_no_autentica(): void
    {
        $this->crearCuentaAdmin('Cliente');

        $respuesta = $this->post('/admin/login', [
            'identificador_externo' => 'admin@test.com',
            'password' => '12345678',
        ]);

        $respuesta->assertSessionHasErrors('identificador_externo');
        $this->assertGuest('admin');
    }

    public function test_campos_requeridos(): void
    {
        $this->post('/admin/login', [])
            ->assertSessionHasErrors(['identificador_externo', 'password']);
    }

    public function test_logout_cierra_sesion_y_redirige_al_login(): void
    {
        $this->crearCuentaAdmin();

        $this->post('/admin/login', [
            'identificador_externo' => 'admin@test.com',
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
        $cuenta = $this->crearCuentaAdmin();
        $this->actingAs($cuenta, 'admin');

        $this->get(route('dashboard'))->assertOk();

        $this->get(route('alimentos.index'))->assertOk();
    }
}
