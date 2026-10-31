<?php

namespace Tests\Feature;

use App\Models\Administrador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdministradorCrudTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdmin(array $datos = []): Administrador
    {
        return Administrador::create(array_merge([
            'nombre_completo' => 'Admin Base',
            'username' => 'admin.base',
            'password' => Hash::make('password123'),
            'activo' => true,
            'nivel' => 1,
        ], $datos));
    }

    public function test_lista_los_administradores_de_forma_paginada(): void
    {
        $this->crearAdmin();

        $this->getJson('/api/administradores')
            ->assertOk()
            ->assertJsonStructure(['administradores' => ['data']]);
    }

    public function test_crea_un_administrador_con_password_cifrado(): void
    {
        $respuesta = $this->postJson('/api/administradores', [
            'nombre_completo' => 'Nuevo Admin',
            'username' => 'admin.nuevo',
            'password' => 'password123',
            'activo' => true,
            'nivel' => 2,
        ]);

        $respuesta->assertCreated()->assertJsonPath('message', 'Administrador creado correctamente.')
            ->assertJsonPath('data.username', 'admin.nuevo');

        $admin = Administrador::where('username', 'admin.nuevo')->firstOrFail();
        $this->assertTrue(Hash::check('password123', $admin->password));
        $this->assertArrayNotHasKey('password', $respuesta->json('data'));
    }

    public function test_valida_username_repetido_y_nivel_no_permitido(): void
    {
        $this->crearAdmin(['username' => 'admin.repetido']);

        $this->postJson('/api/administradores', [
            'nombre_completo' => 'Otro Admin',
            'username' => 'admin.repetido',
            'password' => 'password123',
            'nivel' => 4,
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'nivel']);
    }

    public function test_muestra_un_administrador_o_responde_404_en_json(): void
    {
        $admin = $this->crearAdmin();

        $this->getJson("/api/administradores/{$admin->id}")
            ->assertOk()
            ->assertJsonPath('administrador.id', $admin->id);

        $this->getJson('/api/administradores/999999')
            ->assertNotFound()
            ->assertJsonStructure(['message']);
    }

    public function test_actualiza_sin_cambiar_la_password_si_no_se_envia(): void
    {
        $admin = $this->crearAdmin();
        $passwordOriginal = $admin->password;

        $this->putJson("/api/administradores/{$admin->id}", [
            'nombre_completo' => 'Admin Editado',
            'username' => 'admin.base',
            'activo' => false,
            'nivel' => 3,
        ])->assertOk()->assertJsonPath('data.nombre_completo', 'Admin Editado');

        $admin->refresh();
        $this->assertSame('Admin Editado', $admin->nombre_completo);
        $this->assertSame(3, $admin->nivel);
        $this->assertFalse($admin->activo);
        $this->assertSame($passwordOriginal, $admin->password);
    }

    public function test_flujo_de_borrado_logico_papelera_restauracion_y_borrado_fisico(): void
    {
        $admin = $this->crearAdmin();

        $this->deleteJson("/api/administradores/{$admin->id}")->assertOk();
        $this->assertSoftDeleted('administradores', ['id' => $admin->id]);

        $this->getJson('/api/administradores/eliminados')
            ->assertOk()
            ->assertJsonStructure(['administradores' => ['data']]);

        $this->patchJson("/api/administradores/{$admin->id}/restore")->assertOk();
        $this->assertNotSoftDeleted('administradores', ['id' => $admin->id]);

        // sin borrado lógico previo no se permite el borrado físico
        $this->deleteJson("/api/administradores/{$admin->id}/force")->assertStatus(422);

        $admin->delete();
        $this->deleteJson("/api/administradores/{$admin->id}/force")->assertOk();
        $this->assertDatabaseMissing('administradores', ['id' => $admin->id]);
    }
}
