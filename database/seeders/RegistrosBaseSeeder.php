<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\CategoriaAlimento;
use App\Models\Usuario;

class RegistrosBaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Administrador', 'descripcion' => 'Control total del sistema', 'estado' => true],
            ['nombre' => 'Donador', 'descripcion' => 'Realiza donaciones de alimentos', 'estado' => true],
            ['nombre' => 'Beneficiario', 'descripcion' => 'Solicita y recibe alimentos', 'estado' => true],
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }

        $categorias = [
            ['nombre' => 'Frutas', 'descripcion' => 'Frutas frescas', 'estado' => true],
            ['nombre' => 'Verduras', 'descripcion' => 'Verduras frescas', 'estado' => true],
            ['nombre' => 'Granos', 'descripcion' => 'Granos y cereales', 'estado' => true],
            ['nombre' => 'Lacteos', 'descripcion' => 'Productos lacteos', 'estado' => true],
        ];

        foreach ($categorias as $categoria) {
            CategoriaAlimento::create($categoria);
        }

        $rolAdmin = Rol::where('nombre', 'Administrador')->first();
        $usuarioDemo = Usuario::create([
            'id_rol'         => $rolAdmin->id,
            'nombre'         => 'Juan',
            'apellido'       => 'Perez',
            'correo'         => 'juan@ejemplo.com',
            'telefono'       => '555-0101',
            'direccion'      => 'Calle Principal 123',
            'fecha_registro' => now(),
            'estado'         => true,
        ]);

        $this->command->info('Datos base creados: roles, categorias y usuario demo.');
    }
}