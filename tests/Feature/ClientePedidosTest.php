<?php

namespace Tests\Feature;

use App\Models\CuentaAcceso;
use App\Models\Rol;
use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientePedidosTest extends TestCase
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

    private function tokenDe(CuentaAcceso $cuenta): string
    {
        return $cuenta->createToken('test')->plainTextToken;
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

    public function test_crear_solicitud_propia_devuelve_201(): void
    {
        $cuenta = $this->crearCliente('pedidos@test.com');
        $idAlimento = $this->crearAlimento();

        $this->withToken($this->tokenDe($cuenta))
            ->postJson('/api/v1/cliente/solicitudes', [
                'direccion_entrega' => 'Calle entrega 10',
                'observaciones' => 'Llamar antes',
                'id_alimento' => $idAlimento,
                'cantidad' => 2,
            ])
            ->assertStatus(201)
            ->assertJsonPath('solicitud.estado', 'pendiente')
            ->assertJsonPath('solicitud.detalles.0.id_alimento', $idAlimento);

        $this->assertDatabaseHas('solicitudes', [
            'id_usuario' => $cuenta->id_usuario,
            'direccion_entrega' => 'Calle entrega 10',
        ]);
    }

    public function test_crear_solicitud_ignora_id_usuario_del_body(): void
    {
        $cuenta = $this->crearCliente('propio@test.com');
        $otro = $this->crearCliente('ajeno@test.com');
        $idAlimento = $this->crearAlimento();

        $this->withToken($this->tokenDe($cuenta))
            ->postJson('/api/v1/cliente/solicitudes', [
                'direccion_entrega' => 'Calle entrega 20',
                'id_alimento' => $idAlimento,
                'cantidad' => 1,
                'id_usuario' => $otro->id_usuario,
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('solicitudes', [
            'id_usuario' => $cuenta->id_usuario,
            'direccion_entrega' => 'Calle entrega 20',
        ]);
        $this->assertDatabaseMissing('solicitudes', [
            'id_usuario' => $otro->id_usuario,
            'direccion_entrega' => 'Calle entrega 20',
        ]);
    }

    public function test_crear_solicitud_con_datos_invalidos_devuelve_422(): void
    {
        $cuenta = $this->crearCliente('invalida@test.com');

        $this->withToken($this->tokenDe($cuenta))
            ->postJson('/api/v1/cliente/solicitudes', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['direccion_entrega', 'id_alimento', 'cantidad']);
    }

    public function test_listar_solicitudes_solo_las_propias(): void
    {
        $cuenta = $this->crearCliente('dueño@test.com');
        $otro = $this->crearCliente('otro@test.com');
        $idAlimento = $this->crearAlimento();

        $this->withToken($this->tokenDe($otro))
            ->postJson('/api/v1/cliente/solicitudes', [
                'direccion_entrega' => 'Direccion ajena',
                'id_alimento' => $idAlimento,
                'cantidad' => 1,
            ])->assertStatus(201);

        $this->app['auth']->forgetGuards();

        $respuesta = $this->withToken($this->tokenDe($cuenta))
            ->getJson('/api/v1/cliente/solicitudes')
            ->assertOk();

        $ids = collect($respuesta->json('solicitudes.data'))->pluck('direccion_entrega');
        $this->assertFalse($ids->contains('Direccion ajena'));
    }

    public function test_crear_donacion_propia_devuelve_201(): void
    {
        $cuenta = $this->crearCliente('donador@test.com');
        $idAlimento = $this->crearAlimento();

        $this->withToken($this->tokenDe($cuenta))
            ->postJson('/api/v1/cliente/donaciones', [
                'fecha_limite' => now()->addDays(3)->toDateString(),
                'ubicacion' => 'Bodega central',
                'observaciones' => 'Donación de prueba',
                'id_alimento' => $idAlimento,
                'cantidad' => 5,
            ])
            ->assertStatus(201)
            ->assertJsonPath('donacion.estado', 'Disponible');

        $this->assertDatabaseHas('donaciones', [
            'id_usuario' => $cuenta->id_usuario,
            'ubicacion' => 'Bodega central',
            'estado' => 'Disponible',
        ]);
    }

    public function test_crear_donacion_con_fecha_pasada_devuelve_422(): void
    {
        $cuenta = $this->crearCliente('fecha-mala@test.com');
        $idAlimento = $this->crearAlimento();

        $this->withToken($this->tokenDe($cuenta))
            ->postJson('/api/v1/cliente/donaciones', [
                'fecha_limite' => now()->subDay()->toDateString(),
                'ubicacion' => 'Bodega',
                'observaciones' => 'Texto',
                'id_alimento' => $idAlimento,
                'cantidad' => 1,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['fecha_limite']);
    }

    public function test_listar_donaciones_solo_las_propias(): void
    {
        $cuenta = $this->crearCliente('propia-don@test.com');
        $otro = $this->crearCliente('ajena-don@test.com');
        $idAlimento = $this->crearAlimento();

        $this->withToken($this->tokenDe($otro))
            ->postJson('/api/v1/cliente/donaciones', [
                'fecha_limite' => now()->addDay()->toDateString(),
                'ubicacion' => 'Ubicacion ajena',
                'observaciones' => 'Ajena',
                'id_alimento' => $idAlimento,
                'cantidad' => 1,
            ])->assertStatus(201);

        $this->app['auth']->forgetGuards();

        $respuesta = $this->withToken($this->tokenDe($cuenta))
            ->getJson('/api/v1/cliente/donaciones')
            ->assertOk();

        $ubicaciones = collect($respuesta->json('donaciones.data'))->pluck('ubicacion');
        $this->assertFalse($ubicaciones->contains('Ubicacion ajena'));
    }

    public function test_solicitudes_sin_token_devuelven_401(): void
    {
        $this->crearCliente('sin-token@test.com');

        $this->getJson('/api/v1/cliente/solicitudes')->assertStatus(401);
        $this->getJson('/api/v1/cliente/donaciones')->assertStatus(401);
        $this->postJson('/api/v1/cliente/solicitudes', [])->assertStatus(401);
    }

    public function test_admin_no_puede_crear_solicitudes_de_cliente(): void
    {
        $admin = \App\Models\Administrador::create([
            'nombre_completo' => 'Admin API',
            'username' => 'admin.api',
            'password' => Hash::make('12345678'),
            'activo' => true,
            'nivel' => 3,
        ]);

        $this->withToken($admin->createToken('test')->plainTextToken)
            ->postJson('/api/v1/cliente/solicitudes', [])
            ->assertStatus(403);
    }

    public function test_flujo_completo_de_pedido_y_donacion(): void
    {
        $cuenta = $this->crearCliente('flujo@test.com');
        $idAlimento = $this->crearAlimento();
        $token = $this->tokenDe($cuenta);

        $this->withToken($token)
            ->postJson('/api/v1/cliente/solicitudes', [
                'direccion_entrega' => 'Flujo 1',
                'id_alimento' => $idAlimento,
                'cantidad' => 3,
            ])->assertStatus(201);

        $this->withToken($token)
            ->postJson('/api/v1/cliente/donaciones', [
                'fecha_limite' => now()->addDays(2)->toDateString(),
                'ubicacion' => 'Flujo 2',
                'observaciones' => 'Flujo',
                'id_alimento' => $idAlimento,
                'cantidad' => 4,
            ])->assertStatus(201);

        $pedidos = $this->withToken($token)->getJson('/api/v1/cliente/solicitudes')->assertOk();
        $donaciones = $this->withToken($token)->getJson('/api/v1/cliente/donaciones')->assertOk();

        $this->assertCount(1, $pedidos->json('solicitudes.data'));
        $this->assertCount(1, $donaciones->json('donaciones.data'));
        $this->assertSame(1, Solicitud::count());
    }
}
