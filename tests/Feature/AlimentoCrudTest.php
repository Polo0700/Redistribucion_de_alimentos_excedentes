<?php

namespace Tests\Feature;

use App\Models\CategoriaAlimento;
use App\Models\DetalleDonacion;
use App\Models\Donacion;
use App\Models\Usuario;
use App\Models\Alimento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlimentoCrudTest extends TestCase
{
    use RefreshDatabase;

    private function crearCategoria(): CategoriaAlimento
    {
        return CategoriaAlimento::create([
            'nombre' => 'Frutas',
            'descripcion' => 'Categoría de prueba',
            'estado' => 1,
        ]);
    }

    private function crearAlimento(string $nombre = 'Manzana', int $estado = 1): Alimento
    {
        $categoria = $this->crearCategoria();

        return Alimento::create([
            'id_categoria' => $categoria->id_categoria,
            'nombre' => $nombre,
            'descripcion' => 'Fruta roja',
            'estado' => $estado,
            'imagen' => null,
        ]);
    }

    private function crearDependencia(Alimento $alimento): void
    {
        $rol = \App\Models\Rol::create([
            'nombre' => 'Admin',
            'descripcion' => 'Rol de prueba',
            'estado' => 1,
        ]);

        $usuario = Usuario::create([
            'id_rol' => $rol->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'correo' => 'juan@test.com',
            'telefono' => '1234567890',
            'direccion' => 'Calle test 123',
            'fecha_registro' => now(),
            'estado' => 1,
        ]);

        $donacion = Donacion::create([
            'id_usuario' => $usuario->id,
            'fecha_donacion' => now(),
            'fecha_limite' => now()->addDays(3),
            'ubicacion' => 'Test',
            'estado' => 'Pendiente',
            'observaciones' => 'Donación temporal',
        ]);

        DetalleDonacion::create([
            'id_donacion' => $donacion->id_donacion,
            'id_alimento' => $alimento->id_alimento,
            'cantidad' => 2.5,
            'observaciones' => 'Dependencia de prueba',
        ]);
    }

    public function test_index_solo_muestra_registros_activos(): void
    {
        $activo = $this->crearAlimento('Activa');
        $eliminado = $this->crearAlimento('Eliminada');
        $eliminado->delete();

        $respuesta = $this->get(route('alimentos.index'));

        $respuesta->assertOk();
        $respuesta->assertSee($activo->nombre);
        $respuesta->assertDontSee($eliminado->nombre);
    }

    public function test_show_muestra_toda_la_informacion_con_su_relacion(): void
    {
        $alimento = $this->crearAlimento();

        $respuesta = $this->get(route('alimentos.show', $alimento->id_alimento));

        $respuesta->assertOk();
        $respuesta->assertSee($alimento->nombre);
        $respuesta->assertSee($alimento->descripcion);
        $respuesta->assertSee('Frutas'); // relación con categoría
        $respuesta->assertDontSee('<form'); // solo lectura
    }

    public function test_show_de_registro_inexistente_devuelve_404(): void
    {
        $this->get(route('alimentos.show', 9999))->assertNotFound();
    }

    public function test_destroy_realiza_borrado_logico(): void
    {
        $alimento = $this->crearAlimento();

        $this->delete(route('alimentos.destroy', $alimento->id_alimento))
            ->assertRedirect(route('alimentos.index'));

        $registro = Alimento::withTrashed()->find($alimento->id_alimento);
        $this->assertTrue($registro->trashed());
        $this->assertDatabaseHas('alimentos', ['id_alimento' => $alimento->id_alimento, 'deleted_at' => $registro->deleted_at]);
        $this->assertSame(0, Alimento::count()); // ya no se cuenta como activo
    }

    public function test_trashed_lista_solo_los_eliminados(): void
    {
        $eliminado = $this->crearAlimento();
        $eliminado->delete();

        $respuesta = $this->get(route('alimentos.trashed'));

        $respuesta->assertOk();
        $respuesta->assertSee($eliminado->nombre);
    }

    public function test_restore_devuelve_el_registro_a_activo(): void
    {
        $alimento = $this->crearAlimento();
        $alimento->delete();

        $this->patch(route('alimentos.restore', $alimento->id_alimento))
            ->assertRedirect(route('alimentos.trashed'));

        $this->assertFalse(Alimento::withTrashed()->find($alimento->id_alimento)->trashed());
        $this->assertSame(1, Alimento::count());
    }

    public function test_force_destroy_se_bloquea_cuando_hay_dependencias(): void
    {
        $alimento = $this->crearAlimento();
        $this->crearDependencia($alimento);
        $alimento->delete();

        $this->delete(route('alimentos.forceDestroy', $alimento->id_alimento))
            ->assertRedirect(route('alimentos.trashed'));

        $respuesta = $this->get(route('alimentos.trashed'));
        $respuesta->assertSee('Detalles de Donación');

        $this->assertInstanceOf(Alimento::class, Alimento::onlyTrashed()->find($alimento->id_alimento));
    }

    public function test_force_destroy_elimina_registro_y_su_imagen(): void
    {
        Storage::fake('public');
        $alimento = $this->crearAlimento();
        $alimento->imagen = 'alimentos/imagen_de_prueba.png';
        $alimento->save();
        Storage::disk('public')->put('alimentos/imagen_de_prueba.png', 'contenido');
        $alimento->delete();

        $this->delete(route('alimentos.forceDestroy', $alimento->id_alimento))
            ->assertRedirect(route('alimentos.trashed'));

        $this->assertNull(Alimento::withTrashed()->find($alimento->id_alimento));
        Storage::disk('public')->assertMissing('alimentos/imagen_de_prueba.png');
    }
}