<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\AutenticaComoAdmin;
use Tests\TestCase;

class AutorizacionNivelesTest extends TestCase
{
    use AutenticaComoAdmin, RefreshDatabase;

    private function crearRol(string $nombre = 'Rol de prueba'): Rol
    {
        return Rol::create([
            'nombre' => $nombre,
            'descripcion' => 'Rol para pruebas de autorización',
            'estado' => 1,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NIVEL 1: solo consulta
    |--------------------------------------------------------------------------
    */
    public function test_nivel_1_puede_consultar(): void
    {
        $this->actingAsAdmin(1);

        $this->get(route('roles.index'))->assertOk();
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_nivel_1_no_puede_crear(): void
    {
        $this->actingAsAdmin(1);

        $this->get(route('roles.create'))->assertForbidden();

        $this->post(route('roles.store'), [
            'nombre' => 'Nuevo rol',
            'descripcion' => 'Intento de nivel 1',
            'estado' => 1,
        ])->assertForbidden();

        $this->assertDatabaseMissing('roles', ['nombre' => 'Nuevo rol']);
    }

    public function test_nivel_1_no_puede_editar(): void
    {
        $rol = $this->crearRol();
        $this->actingAsAdmin(1);

        $this->get(route('roles.edit', $rol->id))->assertForbidden();

        $this->put(route('roles.update', $rol->id), [
            'nombre' => 'Modificado',
            'descripcion' => 'Intento de nivel 1',
            'estado' => 1,
        ])->assertForbidden();

        $this->assertDatabaseHas('roles', ['id' => $rol->id, 'nombre' => $rol->nombre]);
    }

    public function test_nivel_1_no_puede_eliminar(): void
    {
        $rol = $this->crearRol();
        $this->actingAsAdmin(1);

        $this->delete(route('roles.destroy', $rol->id))->assertForbidden();

        $this->assertDatabaseHas('roles', ['id' => $rol->id, 'deleted_at' => null]);
    }

    public function test_nivel_1_no_puede_administrar_administradores(): void
    {
        $this->actingAsAdmin(1);

        $this->get(route('administradores.index'))->assertForbidden();
        $this->get(route('administradores.create'))->assertForbidden();

        $this->post(route('administradores.store'), [
            'nombre_completo' => 'Nuevo Admin',
            'username' => 'nuevo-admin',
            'password' => 'secret123',
            'nivel' => 3,
        ])->assertForbidden();

        $this->assertDatabaseMissing('administradores', ['username' => 'nuevo-admin']);
    }

    /*
    |--------------------------------------------------------------------------
    | NIVEL 2: consulta, crear y editar (no eliminar ni administrar admins)
    |--------------------------------------------------------------------------
    */
    public function test_nivel_2_puede_crear(): void
    {
        $this->actingAsAdmin(2);

        $this->get(route('roles.create'))->assertOk();

        $this->post(route('roles.store'), [
            'nombre' => 'Rol nivel 2',
            'descripcion' => 'Creado por nivel 2',
            'estado' => 1,
        ])->assertRedirect(route('roles.index'));

        $this->assertDatabaseHas('roles', ['nombre' => 'Rol nivel 2']);
    }

    public function test_nivel_2_puede_editar(): void
    {
        $rol = $this->crearRol();
        $this->actingAsAdmin(2);

        $this->get(route('roles.edit', $rol->id))->assertOk();

        $this->put(route('roles.update', $rol->id), [
            'nombre' => 'Rol editado',
            'descripcion' => 'Editado por nivel 2',
            'estado' => 1,
        ])->assertRedirect(route('roles.index'));

        $this->assertDatabaseHas('roles', ['id' => $rol->id, 'nombre' => 'Rol editado']);
    }

    public function test_nivel_2_no_puede_eliminar(): void
    {
        $rol = $this->crearRol();
        $this->actingAsAdmin(2);

        $this->delete(route('roles.destroy', $rol->id))->assertForbidden();

        $this->assertDatabaseHas('roles', ['id' => $rol->id, 'deleted_at' => null]);
    }

    public function test_nivel_2_no_puede_administrar_administradores(): void
    {
        $this->actingAsAdmin(2);

        $this->get(route('administradores.index'))->assertForbidden();
        $this->get(route('administradores.create'))->assertForbidden();
    }

    /*
    |--------------------------------------------------------------------------
    | NIVEL 3: acceso total
    |--------------------------------------------------------------------------
    */
    public function test_nivel_3_puede_eliminar(): void
    {
        $rol = $this->crearRol();
        $this->actingAsAdmin(3);

        $this->delete(route('roles.destroy', $rol->id))
            ->assertRedirect(route('roles.index'));

        $this->assertSoftDeleted('roles', ['id' => $rol->id]);
    }

    public function test_nivel_3_puede_administrar_administradores(): void
    {
        $this->actingAsAdmin(3);

        $this->get(route('administradores.index'))->assertOk();
        $this->get(route('administradores.create'))->assertOk();
    }

    /*
    |--------------------------------------------------------------------------
    | El nivel proviene del registro de la BD, no del formulario
    |--------------------------------------------------------------------------
    */
    public function test_store_valida_que_el_nivel_sea_1_2_o_3(): void
    {
        $this->actingAsAdmin(3);

        $this->post(route('administradores.store'), [
            'nombre_completo' => 'Admin inválido',
            'username' => 'admin-invalido',
            'password' => 'secret123',
            'nivel' => 99,
        ])->assertSessionHasErrors('nivel');

        $this->assertDatabaseMissing('administradores', ['username' => 'admin-invalido']);
    }

    public function test_invitado_no_puede_ejecutar_operaciones_protegidas(): void
    {
        $rol = $this->crearRol();

        $this->get(route('roles.index'))->assertRedirect(route('admin.login'));
        $this->post(route('roles.store'), [
            'nombre' => 'Invitado',
            'descripcion' => 'Sin sesión',
            'estado' => 1,
        ])->assertRedirect(route('admin.login'));

        $this->delete(route('roles.destroy', $rol->id))->assertRedirect(route('admin.login'));

        $this->assertDatabaseHas('roles', ['id' => $rol->id, 'deleted_at' => null]);
    }

    public function test_administrador_inactivo_no_tiene_autorizacion(): void
    {
        $rol = $this->crearRol();
        $admin = $this->actingAsAdmin(3);
        $admin->update(['activo' => false]);

        // se reautentica con el registro actualizado (activo = false)
        $this->actingAs($admin->fresh(), 'admin');

        $this->get(route('roles.index'))->assertForbidden();
        $this->delete(route('roles.destroy', $rol->id))->assertForbidden();
    }
}
