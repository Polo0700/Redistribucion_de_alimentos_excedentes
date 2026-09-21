<?php

namespace Tests\Feature;

use App\Models\AccionImportante;
use App\Models\Carrito;
use App\Models\CategoriaAlimento;
use App\Models\CuentaAcceso;
use App\Models\DetalleDonacion;
use App\Models\Donacion;
use App\Models\Entrega;
use App\Models\ListaDeseo;
use App\Models\Rol;
use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplicaCrudTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Helpers de datos
    |--------------------------------------------------------------------------
    */

    private function crearRol(): Rol
    {
        return Rol::create([
            'nombre' => 'Admin',
            'descripcion' => 'Rol de prueba',
            'estado' => 1,
        ]);
    }

    private function crearUsuario(Rol $rol, string $correo = 'usuario@test.com'): Usuario
    {
        return Usuario::create([
            'id_rol' => $rol->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'correo' => $correo,
            'telefono' => '1234567890',
            'direccion' => 'Calle test 123',
            'fecha_registro' => now(),
            'estado' => 1,
        ]);
    }

    private function crearCategoria(): CategoriaAlimento
    {
        return CategoriaAlimento::create([
            'nombre' => 'Frutas',
            'descripcion' => 'Todo tipo de frutas',
            'estado' => 1,
        ]);
    }

    private function crearAlimento(CategoriaAlimento $categoria): \App\Models\Alimento
    {
        return \App\Models\Alimento::create([
            'id_categoria' => $categoria->id_categoria,
            'nombre' => 'Manzana',
            'descripcion' => 'Fruta roja',
            'estado' => 1,
            'imagen' => null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Categorías
    |--------------------------------------------------------------------------
    */
    public function test_flujo_completo_categoria(): void
    {
        $categoria = $this->crearCategoria();

        $this->get(route('categorias.index'))->assertOk()->assertSee('Ver eliminados');
        $this->get(route('categorias.show', $categoria->id_categoria))->assertOk()->assertSee('Detalle de la categoría');

        $this->delete(route('categorias.destroy', $categoria->id_categoria))
            ->assertRedirect(route('categorias.index'))
            ->assertSessionHas('success');

        $this->assertTrue(CategoriaAlimento::withTrashed()->findOrFail($categoria->id_categoria)->trashed());

        $this->get(route('categorias.trashed'))->assertOk()->assertSee('Categorías eliminadas');

        $this->patch(route('categorias.restore', $categoria->id_categoria))
            ->assertRedirect(route('categorias.trashed'))
            ->assertSessionHas('success');

        $this->assertFalse(CategoriaAlimento::withTrashed()->findOrFail($categoria->id_categoria)->trashed());
    }

    public function test_force_categoria_se_bloquea_con_alimentos(): void
    {
        $categoria = $this->crearCategoria();
        $this->crearAlimento($categoria);
        $categoria->delete();

        $this->delete(route('categorias.forceDestroy', $categoria->id_categoria))
            ->assertRedirect(route('categorias.trashed'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categorias_alimento', ['id_categoria' => $categoria->id_categoria]);
    }

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */
    public function test_flujo_completo_rol(): void
    {
        $rol = $this->crearRol();

        $this->get(route('roles.index'))->assertOk()->assertSee('Ver eliminados');
        $this->get(route('roles.show', $rol->id))->assertOk()->assertSee('Detalle del rol');

        $this->delete(route('roles.destroy', $rol->id))
            ->assertRedirect(route('roles.index'))
            ->assertSessionHas('success');

        $this->assertTrue(Rol::withTrashed()->findOrFail($rol->id)->trashed());

        $this->get(route('roles.trashed'))->assertOk();

        $this->patch(route('roles.restore', $rol->id))->assertRedirect(route('roles.trashed'));

        $this->assertFalse(Rol::withTrashed()->findOrFail($rol->id)->trashed());
    }

    public function test_force_rol_se_bloquea_con_usuarios(): void
    {
        $rol = $this->crearRol();
        $this->crearUsuario($rol);
        $rol->delete();

        $this->delete(route('roles.forceDestroy', $rol->id))
            ->assertRedirect(route('roles.trashed'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('roles', ['id' => $rol->id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Usuarios
    |--------------------------------------------------------------------------
    */
    public function test_flujo_completo_usuario(): void
    {
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);

        $this->get(route('usuarios.index'))->assertOk()->assertSee('Ver eliminados');
        $this->get(route('usuarios.show', $usuario->id))->assertOk()->assertSee('Detalle del usuario');

        $this->delete(route('usuarios.destroy', $usuario->id))
            ->assertRedirect(route('usuarios.index'))
            ->assertSessionHas('success');

        $this->assertTrue(Usuario::withTrashed()->findOrFail($usuario->id)->trashed());

        $this->get(route('usuarios.trashed'))->assertOk();

        $this->patch(route('usuarios.restore', $usuario->id))->assertRedirect(route('usuarios.trashed'));

        $this->assertFalse(Usuario::withTrashed()->findOrFail($usuario->id)->trashed());
    }

    public function test_force_usuario_se_bloquea_con_donaciones(): void
    {
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);
        $donacion = Donacion::create([
            'id_usuario' => $usuario->id,
            'fecha_donacion' => now(),
            'fecha_limite' => now()->addDays(3),
            'ubicacion' => 'Test',
            'estado' => 'Pendiente',
            'observaciones' => 'Prueba',
        ]);
        $usuario->delete();

        $this->delete(route('usuarios.forceDestroy', $usuario->id))
            ->assertRedirect(route('usuarios.trashed'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('usuarios', ['id' => $usuario->id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Cuentas de acceso
    |--------------------------------------------------------------------------
    */
    public function test_flujo_completo_cuenta_acceso(): void
    {
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);
        $cuenta = CuentaAcceso::create([
            'id_usuario' => $usuario->id,
            'proveedor' => 'Google',
            'identificador_externo' => 'ext-123',
            'contrasena_hash' => bcrypt('secreta'),
            'fecha_ultimo_acceso' => now(),
        ]);

        $this->get(route('cuentas-acceso.index'))->assertOk()->assertSee('Ver eliminados');
        $this->get(route('cuentas-acceso.show', $cuenta->id_cuenta))->assertOk()->assertSee('Detalle de la cuenta de acceso');

        $this->delete(route('cuentas-acceso.destroy', $cuenta->id_cuenta))
            ->assertRedirect(route('cuentas-acceso.index'))
            ->assertSessionHas('success');

        $this->assertTrue(CuentaAcceso::withTrashed()->findOrFail($cuenta->id_cuenta)->trashed());

        $this->get(route('cuentas-acceso.trashed'))->assertOk();

        $this->patch(route('cuentas-acceso.restore', $cuenta->id_cuenta))->assertRedirect(route('cuentas-acceso.trashed'));

        $this->assertFalse(CuentaAcceso::withTrashed()->findOrFail($cuenta->id_cuenta)->trashed());
    }

    public function test_force_cuenta_acceso_sin_dependencias_se_elimina(): void
    {
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);
        $cuenta = CuentaAcceso::create([
            'id_usuario' => $usuario->id,
            'proveedor' => 'Google',
            'identificador_externo' => 'ext-123',
            'contrasena_hash' => bcrypt('secreta'),
            'fecha_ultimo_acceso' => now(),
        ]);
        $cuenta->delete();

        $this->delete(route('cuentas-acceso.forceDestroy', $cuenta->id_cuenta))
            ->assertRedirect(route('cuentas-acceso.trashed'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('cuentas_acceso', ['id_cuenta' => $cuenta->id_cuenta]);
    }

    /*
    |--------------------------------------------------------------------------
    | Carritos
    |--------------------------------------------------------------------------
    */
    public function test_flujo_completo_carrito(): void
    {
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);
        $carrito = Carrito::create([
            'id_usuario' => $usuario->id,
            'fecha_creacion' => now(),
            'estado' => 'Activo',
        ]);

        $this->get(route('carritos.index'))->assertOk()->assertSee('Ver eliminados');
        $this->get(route('carritos.show', $carrito->id_carrito))->assertOk()->assertSee('Detalle del carrito');

        $this->delete(route('carritos.destroy', $carrito->id_carrito))
            ->assertRedirect(route('carritos.index'))
            ->assertSessionHas('success');

        $this->assertTrue(Carrito::withTrashed()->findOrFail($carrito->id_carrito)->trashed());

        $this->get(route('carritos.trashed'))->assertOk();

        $this->patch(route('carritos.restore', $carrito->id_carrito))->assertRedirect(route('carritos.trashed'));

        $this->assertFalse(Carrito::withTrashed()->findOrFail($carrito->id_carrito)->trashed());
    }

    /*
    |--------------------------------------------------------------------------
    | Listas de deseos
    |--------------------------------------------------------------------------
    */
    public function test_flujo_completo_lista_deseos(): void
    {
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);
        $lista = ListaDeseo::create([
            'id_usuario' => $usuario->id,
            'nombre' => 'Mi lista',
            'fecha_creacion' => now(),
        ]);

        $this->get(route('listas-deseos.index'))->assertOk()->assertSee('Ver eliminados');
        $this->get(route('listas-deseos.show', $lista->id_lista))->assertOk()->assertSee('Detalle de la lista de deseos');

        $this->delete(route('listas-deseos.destroy', $lista->id_lista))
            ->assertRedirect(route('listas-deseos.index'))
            ->assertSessionHas('success');

        $this->assertTrue(ListaDeseo::withTrashed()->findOrFail($lista->id_lista)->trashed());

        $this->get(route('listas-deseos.trashed'))->assertOk();

        $this->patch(route('listas-deseos.restore', $lista->id_lista))->assertRedirect(route('listas-deseos.trashed'));

        $this->assertFalse(ListaDeseo::withTrashed()->findOrFail($lista->id_lista)->trashed());
    }

    /*
    |--------------------------------------------------------------------------
    | Solicitudes
    |--------------------------------------------------------------------------
    */
    public function test_flujo_completo_solicitud(): void
    {
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);
        $solicitud = Solicitud::create([
            'id_usuario' => $usuario->id,
            'fecha_solicitud' => now(),
            'estado' => 'Pendiente',
            'direccion_entrega' => 'Calle entrega 1',
            'observaciones' => 'Sin observaciones',
        ]);

        $this->get(route('solicitudes.index'))->assertOk()->assertSee('Ver eliminados');
        $this->get(route('solicitudes.show', $solicitud->id_solicitud))->assertOk()->assertSee('Detalle de la solicitud');

        $this->delete(route('solicitudes.destroy', $solicitud->id_solicitud))
            ->assertRedirect(route('solicitudes.index'))
            ->assertSessionHas('success');

        $this->assertTrue(Solicitud::withTrashed()->findOrFail($solicitud->id_solicitud)->trashed());

        $this->get(route('solicitudes.trashed'))->assertOk();

        $this->patch(route('solicitudes.restore', $solicitud->id_solicitud))->assertRedirect(route('solicitudes.trashed'));

        $this->assertFalse(Solicitud::withTrashed()->findOrFail($solicitud->id_solicitud)->trashed());
    }

    /*
    |--------------------------------------------------------------------------
    | Donaciones
    |--------------------------------------------------------------------------
    */
    public function test_flujo_completo_donacion(): void
    {
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);
        $donacion = Donacion::create([
            'id_usuario' => $usuario->id,
            'fecha_donacion' => now(),
            'fecha_limite' => now()->addDays(3),
            'ubicacion' => 'Bodega central',
            'estado' => 'Disponible',
            'observaciones' => 'Donación de prueba',
        ]);

        $this->get(route('donaciones.index'))->assertOk()->assertSee('Ver eliminados');
        $this->get(route('donaciones.show', $donacion->id_donacion))->assertOk()->assertSee('Detalle de la donación');

        $this->delete(route('donaciones.destroy', $donacion->id_donacion))
            ->assertRedirect(route('donaciones.index'))
            ->assertSessionHas('success');

        $this->assertTrue(Donacion::withTrashed()->findOrFail($donacion->id_donacion)->trashed());

        $this->get(route('donaciones.trashed'))->assertOk();

        $this->patch(route('donaciones.restore', $donacion->id_donacion))->assertRedirect(route('donaciones.trashed'));

        $this->assertFalse(Donacion::withTrashed()->findOrFail($donacion->id_donacion)->trashed());
    }

    public function test_force_donacion_se_bloquea_con_detalles(): void
    {
        $categoria = $this->crearCategoria();
        $alimento = $this->crearAlimento($categoria);
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);
        $donacion = Donacion::create([
            'id_usuario' => $usuario->id,
            'fecha_donacion' => now(),
            'fecha_limite' => now()->addDays(3),
            'ubicacion' => 'Bodega central',
            'estado' => 'Disponible',
            'observaciones' => 'Donación de prueba',
        ]);
        DetalleDonacion::create([
            'id_donacion' => $donacion->id_donacion,
            'id_alimento' => $alimento->id_alimento,
            'cantidad' => 2.5,
            'observaciones' => 'Detalle de prueba',
        ]);
        $donacion->delete();

        $this->delete(route('donaciones.forceDestroy', $donacion->id_donacion))
            ->assertRedirect(route('donaciones.trashed'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('donaciones', ['id_donacion' => $donacion->id_donacion]);
    }

    /*
    |--------------------------------------------------------------------------
    | Entregas
    |--------------------------------------------------------------------------
    */
    public function test_flujo_completo_entrega(): void
    {
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);
        $solicitud = Solicitud::create([
            'id_usuario' => $usuario->id,
            'fecha_solicitud' => now(),
            'estado' => 'Aprobada',
            'direccion_entrega' => 'Calle entrega 1',
            'observaciones' => null,
        ]);
        $entrega = Entrega::create([
            'id_solicitud' => $solicitud->id_solicitud,
            'fecha_entrega' => now(),
            'responsable' => 'Repartidor 1',
            'estado' => 'En camino',
            'observaciones' => null,
        ]);

        $this->get(route('entregas.index'))->assertOk()->assertSee('Ver eliminados');
        $this->get(route('entregas.show', $entrega->id_entrega))->assertOk()->assertSee('Detalle de la entrega');

        $this->delete(route('entregas.destroy', $entrega->id_entrega))
            ->assertRedirect(route('entregas.index'))
            ->assertSessionHas('success');

        $this->assertTrue(Entrega::withTrashed()->findOrFail($entrega->id_entrega)->trashed());

        $this->get(route('entregas.trashed'))->assertOk();

        $this->patch(route('entregas.restore', $entrega->id_entrega))->assertRedirect(route('entregas.trashed'));

        $this->assertFalse(Entrega::withTrashed()->findOrFail($entrega->id_entrega)->trashed());
    }

    /*
    |--------------------------------------------------------------------------
    | Acciones importantes
    |--------------------------------------------------------------------------
    */
    public function test_flujo_completo_accion_importante(): void
    {
        $rol = $this->crearRol();
        $usuario = $this->crearUsuario($rol);
        $accion = AccionImportante::create([
            'id_usuario' => $usuario->id,
            'accion' => 'Cambio de estado',
            'tabla_afectada' => 'solicitudes',
            'descripcion' => 'Solicitud aprobada',
            'fecha_hora' => now(),
            'ip_origen' => '127.0.0.1',
        ]);

        $this->get(route('acciones.index'))->assertOk()->assertSee('Ver eliminados');
        $this->get(route('acciones.show', $accion->id_accion))->assertOk()->assertSee('Detalle de la acción importante');

        $this->delete(route('acciones.destroy', $accion->id_accion))
            ->assertRedirect(route('acciones.index'))
            ->assertSessionHas('success');

        $this->assertTrue(AccionImportante::withTrashed()->findOrFail($accion->id_accion)->trashed());

        $this->get(route('acciones.trashed'))->assertOk();

        $this->patch(route('acciones.restore', $accion->id_accion))->assertRedirect(route('acciones.trashed'));

        $this->assertFalse(AccionImportante::withTrashed()->findOrFail($accion->id_accion)->trashed());
    }
}