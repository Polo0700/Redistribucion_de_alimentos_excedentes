<?php

namespace Tests\Feature;

use App\Models\CuentaAcceso;
use App\Models\Rol;
use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Actividad 4.4: módulo del cliente y autorización de datos propios.
 *
 * Valida:
 *  1. El login devuelve un token válido de Sanctum.
 *  2. Las rutas protegidas devuelven 401 sin token.
 *  3. Las rutas protegidas funcionan (200) enviando el token.
 *  4. Propiedad de datos: el Cliente B recibe 403 al consultar o
 *     cancelar registros del Cliente A (Policy).
 */
class ClientePropiedadDatosTest extends TestCase
{
    use RefreshDatabase;

    private function crearCliente(string $correo): CuentaAcceso
    {
        $rol = Rol::create([
            'nombre' => 'Cliente',
            'descripcion' => 'Cliente registrado',
            'estado' => 1,
        ]);

        $usuario = Usuario::create([
            'id_rol' => $rol->id,
            'nombre' => 'Cliente',
            'apellido' => 'Prueba',
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

    private function crearAlimento(): int
    {
        $categoria = \App\Models\CategoriaAlimento::create([
            'nombre' => 'Frutas',
            'descripcion' => 'Frutas varias',
            'estado' => 1,
        ]);

        $alimento = \App\Models\Alimento::create([
            'id_categoria' => $categoria->id_categoria,
            'nombre' => 'Manzana',
            'descripcion' => 'Manzana roja',
            'estado' => 1,
        ]);

        return $alimento->id_alimento;
    }

    // obtiene un token REAL pasando por POST /api/v1/cliente/login
    private function tokenDeLogin(string $correo, string $password = '12345678'): string
    {
        $respuesta = $this->postJson('/api/v1/cliente/login', [
            'correo' => $correo,
            'password' => $password,
        ]);

        $respuesta->assertStatus(200);

        return $respuesta->json('token');
    }

    // crea una solicitud propia mediante la API y devuelve su id
    private function crearSolicitudPropia(string $token, int $idAlimento): int
    {
        $respuesta = $this->withToken($token)
            ->postJson('/api/v1/cliente/mis-registros', [
                'direccion_entrega' => 'Calle propia 42',
                'id_alimento' => $idAlimento,
                'cantidad' => 2,
            ])
            ->assertStatus(201);

        return $respuesta->json('solicitud.id_solicitud');
    }

    /*
    |--------------------------------------------------------------------------
    | 1. REGISTRO Y LOGIN: token de Sanctum válido
    |--------------------------------------------------------------------------
    */
    public function test_registro_publico_crea_cliente_con_rol_y_token(): void
    {
        $respuesta = $this->postJson('/api/v1/cliente/register', [
            'nombre' => 'Luis',
            'apellido' => 'Ramirez',
            'correo' => 'luis.nuevo@test.com',
            'telefono' => '3344556677',
            'direccion' => 'Calle Nueva 9',
            'password' => '12345678',
        ]);

        $respuesta->assertStatus(201)
            ->assertJsonPath('usuario.rol', 'Cliente');

        $this->assertDatabaseHas('usuarios', [
            'correo' => 'luis.nuevo@test.com',
            'nombre' => 'Luis',
        ]);

        // el token devuelto funciona sobre una ruta protegida
        $this->withToken($respuesta->json('token'))
            ->getJson('/api/v1/cliente/perfil')
            ->assertOk()
            ->assertJsonPath('usuario.correo', 'luis.nuevo@test.com');
    }

    public function test_login_devuelve_un_token_de_sanctum_valido(): void
    {
        $this->crearCliente('cliente.a@test.com');

        $respuesta = $this->postJson('/api/v1/cliente/login', [
            'correo' => 'cliente.a@test.com',
            'password' => '12345678',
        ]);

        $respuesta->assertStatus(200);

        // formato plainTextToken de Sanctum: "id|cadena_alfanumerica"
        $token = $respuesta->json('token');
        $this->assertMatchesRegularExpression('/^\d+\|[A-Za-z0-9]+$/', $token);
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. SIN TOKEN: las rutas protegidas devuelven 401
    |--------------------------------------------------------------------------
    */
    public function test_rutas_protegidas_devuelven_401_sin_token(): void
    {
        $this->crearCliente('sin.token@test.com');
        $this->crearAlimento();

        $this->getJson('/api/v1/cliente/perfil')->assertStatus(401);

        $this->patchJson('/api/v1/cliente/perfil', ['nombre' => 'x'])->assertStatus(401);

        $this->getJson('/api/v1/cliente/mis-registros')->assertStatus(401);

        $this->postJson('/api/v1/cliente/mis-registros', [
            'direccion_entrega' => 'Calle 1',
            'id_alimento' => 1,
            'cantidad' => 1,
        ])->assertStatus(401);

        $this->getJson('/api/v1/cliente/mis-registros/1')->assertStatus(401);

        $this->deleteJson('/api/v1/cliente/mis-registros/1')->assertStatus(401);

        $this->getJson('/api/v1/cliente/solicitudes')->assertStatus(401);

        $this->getJson('/api/v1/cliente/donaciones')->assertStatus(401);

        $this->postJson('/api/v1/cliente/logout')->assertStatus(401);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. CON TOKEN: las rutas protegidas funcionan (200)
    |--------------------------------------------------------------------------
    */
    public function test_rutas_protegidas_con_token_devuelven_200(): void
    {
        $cuenta = $this->crearCliente('con.token@test.com');
        $idAlimento = $this->crearAlimento();
        $token = $this->tokenDeLogin('con.token@test.com');

        $this->withToken($token)->getJson('/api/v1/cliente/perfil')->assertOk();

        $this->withToken($token)
            ->getJson('/api/v1/cliente/mis-registros')
            ->assertOk()
            ->assertJsonStructure(['solicitudes' => ['data']]);

        $this->withToken($token)
            ->postJson('/api/v1/cliente/mis-registros', [
                'direccion_entrega' => 'Calle consigo 5',
                'id_alimento' => $idAlimento,
                'cantidad' => 3,
            ])->assertStatus(201);

        $this->withToken($token)
            ->getJson('/api/v1/cliente/donaciones')
            ->assertOk()
            ->assertJsonStructure(['donaciones' => ['data']]);
    }

    /*
    |--------------------------------------------------------------------------
    | 4. CATÁLOGO PÚBLICO: no requiere token
    |--------------------------------------------------------------------------
    */
    public function test_catalogo_publico_no_requiere_token(): void
    {
        $idAlimento = $this->crearAlimento();

        $this->getJson('/api/v1/cliente/catalogo')
            ->assertOk()
            ->assertJsonStructure(['catalogo' => ['data']]);

        $this->getJson('/api/v1/cliente/catalogo/'.$idAlimento)
            ->assertOk()
            ->assertJsonPath('alimento.nombre', 'Manzana');
    }

    /*
    |--------------------------------------------------------------------------
    | 5. PROPIEDAD DE DATOS: Cliente B vs registro de Cliente A (403)
    |--------------------------------------------------------------------------
    */
    public function test_cliente_b_no_puede_ver_el_registro_de_cliente_a(): void
    {
        $cuenta = $this->crearCliente('cliente.a@test.com');
        $this->crearCliente('cliente.b@test.com');
        $idAlimento = $this->crearAlimento();

        $tokenA = $this->tokenDeLogin('cliente.a@test.com');
        $tokenB = $this->tokenDeLogin('cliente.b@test.com');

        $idSolicitud = $this->crearSolicitudPropia($tokenA, $idAlimento);
        $this->app['auth']->forgetGuards();

        $this->withToken($tokenB)
            ->getJson('/api/v1/cliente/mis-registros/'.$idSolicitud)
            ->assertStatus(403);
    }

    public function test_cliente_b_no_puede_cancelar_el_registro_de_cliente_a(): void
    {
        $this->crearCliente('cliente.a@test.com');
        $this->crearCliente('cliente.b@test.com');
        $idAlimento = $this->crearAlimento();

        $tokenA = $this->tokenDeLogin('cliente.a@test.com');
        $tokenB = $this->tokenDeLogin('cliente.b@test.com');

        $idSolicitud = $this->crearSolicitudPropia($tokenA, $idAlimento);
        $this->app['auth']->forgetGuards();

        $this->withToken($tokenB)
            ->deleteJson('/api/v1/cliente/mis-registros/'.$idSolicitud)
            ->assertStatus(403);

        // el registro sigue intacto y pendiente
        $this->assertDatabaseHas('solicitudes', [
            'id_solicitud' => $idSolicitud,
            'estado' => 'pendiente',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 6. PROPIETARIO: Cliente A consulta y cancela su propio registro
    |--------------------------------------------------------------------------
    */
    public function test_cliente_a_puede_ver_y_cancelar_su_registro(): void
    {
        $this->crearCliente('cliente.a@test.com');
        $idAlimento = $this->crearAlimento();

        $tokenA = $this->tokenDeLogin('cliente.a@test.com');
        $idSolicitud = $this->crearSolicitudPropia($tokenA, $idAlimento);

        $this->withToken($tokenA)
            ->getJson('/api/v1/cliente/mis-registros/'.$idSolicitud)
            ->assertOk()
            ->assertJsonPath('solicitud.id_solicitud', $idSolicitud)
            ->assertJsonPath('solicitud.estado', 'pendiente');

        $this->withToken($tokenA)
            ->deleteJson('/api/v1/cliente/mis-registros/'.$idSolicitud)
            ->assertOk()
            ->assertJsonPath('solicitud.estado', 'cancelada');

        $this->assertDatabaseHas('solicitudes', [
            'id_solicitud' => $idSolicitud,
            'estado' => 'cancelada',
        ]);
    }

    public function test_no_se_puede_cancelar_un_registro_no_cancelable(): void
    {
        $this->crearCliente('cliente.a@test.com');
        $idAlimento = $this->crearAlimento();

        $tokenA = $this->tokenDeLogin('cliente.a@test.com');
        $idSolicitud = $this->crearSolicitudPropia($tokenA, $idAlimento);

        // la solicitud ya no está en estado cancelable
        Solicitud::where('id_solicitud', $idSolicitud)
            ->update(['estado' => 'entregada']);

        $this->withToken($tokenA)
            ->deleteJson('/api/v1/cliente/mis-registros/'.$idSolicitud)
            ->assertStatus(403);
    }

    /*
    |--------------------------------------------------------------------------
    | 7. LISTA EXCLUSIVA: solo se ven los registros propios
    |--------------------------------------------------------------------------
    */
    public function test_la_lista_mis_registros_solo_muestra_lo_propio(): void
    {
        $this->crearCliente('cliente.a@test.com');
        $this->crearCliente('cliente.b@test.com');
        $idAlimento = $this->crearAlimento();

        $tokenA = $this->tokenDeLogin('cliente.a@test.com');
        $tokenB = $this->tokenDeLogin('cliente.b@test.com');

        $this->crearSolicitudPropia($tokenA, $idAlimento);
        $this->app['auth']->forgetGuards();

        $respuesta = $this->withToken($tokenB)
            ->getJson('/api/v1/cliente/mis-registros')
            ->assertOk();

        // el Cliente B no ve la solicitud creada por el Cliente A
        $this->assertCount(0, $respuesta->json('solicitudes.data'));

        $this->app['auth']->forgetGuards();

        $respuesta = $this->withToken($tokenA)
            ->getJson('/api/v1/cliente/mis-registros')
            ->assertOk();

        $this->assertCount(1, $respuesta->json('solicitudes.data'));
        $this->assertSame('Calle propia 42', $respuesta->json('solicitudes.data.0.direccion_entrega'));
    }

    /*
    |--------------------------------------------------------------------------
    | 8. LOGOUT: el token revocado deja de autorizar (401)
    |--------------------------------------------------------------------------
    */
    public function test_logout_revoca_el_token_y_deja_de_autorizar(): void
    {
        $this->crearCliente('cliente.logout@test.com');
        $token = $this->tokenDeLogin('cliente.logout@test.com');

        $this->withToken($token)
            ->postJson('/api/v1/cliente/logout')
            ->assertOk();

        $this->app['auth']->forgetGuards();

        $this->withToken($token)
            ->getJson('/api/v1/cliente/perfil')
            ->assertStatus(401);
    }
}
