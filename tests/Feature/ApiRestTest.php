<?php

namespace Tests\Feature;

use App\Models\Administrador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiRestTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdmin(bool $activo = true): Administrador
    {
        return Administrador::create([
            'nombre_completo' => 'QA Tester',
            'username' => 'qa_admin',
            'password' => Hash::make('password123'),
            'activo' => $activo,
            'nivel' => 1,
        ]);
    }

    // obtiene un token real pasando por POST /api/login
    private function login(): string
    {
        $respuesta = $this->postJson('/api/login', [
            'username' => 'qa_admin',
            'password' => 'password123',
        ]);

        $respuesta->assertStatus(200);

        return $respuesta->json('token');
    }

    /*
    |--------------------------------------------------------------------------
    | 1. LOGIN: POST /api/login devuelve 200 con el token de Sanctum
    |--------------------------------------------------------------------------
    */
    public function test_login_devuelve_un_token_de_sanctum(): void
    {
        $this->crearAdmin();

        $respuesta = $this->postJson('/api/login', [
            'username' => 'qa_admin',
            'password' => 'password123',
        ]);

        $respuesta->assertStatus(200)
            ->assertJsonStructure(['message', 'token']);

        // el formato de un plainTextToken de Sanctum es "id|cadena_alfanumerica"
        $token = $respuesta->json('token');
        $this->assertMatchesRegularExpression('/^\d+\|[A-Za-z0-9]+$/', $token);

        // el token quedó registrado en la tabla de Sanctum
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. SIN TOKEN: GET /api/acciones-importantes devuelve 401
    |--------------------------------------------------------------------------
    */
    public function test_index_sin_token_devuelve_401(): void
    {
        $this->getJson('/api/acciones-importantes')
            ->assertStatus(401)
            ->assertJsonStructure(['message']);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. CON TOKEN VÁLIDO: GET /api/acciones-importantes devuelve 200
    |--------------------------------------------------------------------------
    */
    public function test_index_con_token_valido_devuelve_200(): void
    {
        $this->crearAdmin();
        $token = $this->login();

        // crea una acción importante para verificar el mapeo del Resource
        \App\Models\AccionImportante::create([
            'id_usuario' => null,
            'accion' => 'PRUEBA_INDEX',
            'tabla_afectada' => 'usuarios',
            'descripcion' => 'Accion creada por la prueba automatizada',
            'fecha_hora' => now()->toDateTimeString(),
            'ip_origen' => '127.0.0.1',
        ]);

        $this->withToken($token)
            ->getJson('/api/acciones-importantes')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id_accion', 'accion', 'tabla_afectada']],
                'links',
                'meta',
            ])
            ->assertJsonPath('data.0.accion', 'PRUEBA_INDEX');
    }

    /*
    |--------------------------------------------------------------------------
    | 4. VALIDACIÓN: POST /api/acciones-importantes con datos vacíos da 422
    |--------------------------------------------------------------------------
    */
    public function test_store_con_datos_vacios_devuelve_422(): void
    {
        $this->crearAdmin();
        $token = $this->login();

        $this->withToken($token)
            ->postJson('/api/acciones-importantes', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['accion', 'tabla_afectada', 'fecha_hora', 'ip_origen']);
    }
}
