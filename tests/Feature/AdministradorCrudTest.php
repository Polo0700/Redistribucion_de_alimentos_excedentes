<?php

namespace Tests\Feature;

use App\Models\Administrador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\AutenticaComoAdmin;
use Tests\TestCase;

class AdministradorCrudTest extends TestCase
{
    use AutenticaComoAdmin, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin(3);
    }

    /**
     * Datos tal como se envían desde un formulario (contraseña en texto plano).
     */
    private function datosFormulario(string $username = 'nuevo-admin', int $nivel = 2, string $password = 'secret123'): array
    {
        return [
            'nombre_completo' => 'Admin Nuevo',
            'username' => $username,
            'password' => $password,
            'activo' => 1,
            'nivel' => $nivel,
        ];
    }

    /**
     * Crea el registro directamente en la BD (contraseña ya hasheada).
     */
    private function crearAdminBD(string $username = 'admin-bd', string $password = 'secret123'): Administrador
    {
        $datos = $this->datosFormulario($username, 2, $password);
        $datos['password'] = Hash::make($password);

        return Administrador::create($datos);
    }

    public function test_index_lista_los_administradores(): void
    {
        $this->crearAdminBD('visible-admin');

        $respuesta = $this->get(route('administradores.index'));

        $respuesta->assertOk();
        $respuesta->assertSee('visible-admin');
        $respuesta->assertDontSee('no-existe-admin');
    }

    public function test_store_crea_administrador_con_password_hasheado(): void
    {
        $this->post(route('administradores.store'), $this->datosFormulario())
            ->assertRedirect(route('administradores.index'));

        $this->assertDatabaseHas('administradores', [
            'username' => 'nuevo-admin',
            'nivel' => 2,
            'activo' => 1,
        ]);

        $creado = Administrador::where('username', 'nuevo-admin')->first();
        $this->assertTrue(Hash::check('secret123', $creado->password));
        $this->assertNotSame('secret123', $creado->password);
    }

    public function test_store_rechaza_username_duplicado(): void
    {
        $this->crearAdminBD('duplicado');

        $this->post(route('administradores.store'), $this->datosFormulario('duplicado'))
            ->assertSessionHasErrors('username');
    }

    public function test_store_rechaza_nivel_fuera_de_los_permitidos(): void
    {
        $this->post(route('administradores.store'), $this->datosFormulario('nivel-malo', 7))
            ->assertSessionHasErrors('nivel');

        $this->assertDatabaseMissing('administradores', ['username' => 'nivel-malo']);
    }

    public function test_show_muestra_el_detalle(): void
    {
        $admin = $this->crearAdminBD('admin-show');

        $this->get(route('administradores.show', $admin->id))
            ->assertOk()
            ->assertSee('admin-show');
    }

    public function test_update_modifica_datos_y_mantiene_password_si_no_se_envia(): void
    {
        $admin = $this->crearAdminBD('admin-editar', 'clave-original');

        $this->put(route('administradores.update', $admin->id), [
            'nombre_completo' => 'Admin Editado',
            'username' => 'admin-editado',
            'activo' => 1,
            'nivel' => 1,
        ])->assertRedirect(route('administradores.index'));

        $admin->refresh();
        $this->assertSame('admin-editado', $admin->username);
        $this->assertSame(1, $admin->nivel);
        $this->assertTrue(Hash::check('clave-original', $admin->password), 'La contraseña debió conservarse');
    }

    public function test_update_cambia_la_password_cuando_se_envia(): void
    {
        $admin = $this->crearAdminBD('admin-pass', 'clave-vieja');

        $this->put(route('administradores.update', $admin->id), [
            'nombre_completo' => 'Admin Nuevo Pass',
            'username' => 'admin-pass',
            'password' => 'nueva-clave-123',
            'activo' => 1,
            'nivel' => 1,
        ]);

        $this->assertTrue(Hash::check('nueva-clave-123', $admin->refresh()->password));
    }

    public function test_no_puede_eliminarse_a_si_mismo(): void
    {
        $yo = auth('admin')->user();

        $this->delete(route('administradores.destroy', $yo->id))
            ->assertRedirect(route('administradores.index'));

        $this->assertNull($yo->fresh()->deleted_at);
    }

    public function test_no_puede_desactivarse_a_si_mismo(): void
    {
        $yo = auth('admin')->user();

        $this->put(route('administradores.update', $yo->id), [
            'nombre_completo' => $yo->nombre_completo,
            'username' => $yo->username,
            'activo' => 0,
            'nivel' => $yo->nivel,
        ])->assertRedirect(route('administradores.index'));

        $this->assertTrue($yo->fresh()->activo);
    }

    public function test_destroy_realiza_borrado_logico(): void
    {
        $admin = $this->crearAdminBD('admin-eliminar');

        $this->delete(route('administradores.destroy', $admin->id))
            ->assertRedirect(route('administradores.index'));

        $this->assertSoftDeleted('administradores', ['id' => $admin->id]);
    }

    public function test_trashed_restore_y_force_destroy(): void
    {
        $admin = $this->crearAdminBD('admin-papelera');
        $admin->delete();

        // papelera
        $this->get(route('administradores.trashed'))->assertOk()->assertSee('admin-papelera');

        // restaurar
        $this->patch(route('administradores.restore', $admin->id))
            ->assertRedirect(route('administradores.trashed'));
        $this->assertFalse($admin->fresh()->trashed());

        // no se puede borrar definitivamente sin borrado lógico previo
        $this->delete(route('administradores.forceDestroy', $admin->id))
            ->assertRedirect(route('administradores.trashed'));
        $this->assertNotNull(Administrador::withTrashed()->find($admin->id));

        // borrado lógico y luego definitivo
        $admin->delete();
        $this->delete(route('administradores.forceDestroy', $admin->id))
            ->assertRedirect(route('administradores.trashed'));
        $this->assertNull(Administrador::withTrashed()->find($admin->id));
    }
}
