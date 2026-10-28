<?php

namespace Database\Seeders;

use App\Models\Administrador;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrador Principal histórico (Nivel 3)
        Administrador::updateOrCreate(
            ['username' => 'admin'],
            [
                'nombre_completo' => 'Administrador Principal',
                'password' => Hash::make('admin123'),
                'activo' => true,
                'nivel' => 3,
            ]
        );

        // Nivel 1: Solo consulta (No crea, no edita, no elimina)
        Administrador::updateOrCreate(
            ['username' => 'admin1'],
            [
                'nombre_completo' => 'Admin Nivel 1 (Consulta)',
                'password' => Hash::make('admin123'),
                'activo' => true,
                'nivel' => 1,
            ]
        );

        // Nivel 2: Consulta, Crear y Editar (No elimina, no administra cuentas)
        Administrador::updateOrCreate(
            ['username' => 'admin2'],
            [
                'nombre_completo' => 'Admin Nivel 2 (Editor)',
                'password' => Hash::make('admin123'),
                'activo' => true,
                'nivel' => 2,
            ]
        );

        // Nivel 3: Acceso total (Consulta, Crear, Editar, Eliminar y Gestión de Admins)
        Administrador::updateOrCreate(
            ['username' => 'admin3'],
            [
                'nombre_completo' => 'Admin Nivel 3 (Superadmin)',
                'password' => Hash::make('admin123'),
                'activo' => true,
                'nivel' => 3,
            ]
        );

        // Cuenta inactiva para pruebas de estado
        Administrador::updateOrCreate(
            ['username' => 'inactivo'],
            [
                'nombre_completo' => 'Cuenta Deshabilitada',
                'password' => Hash::make('admin123'),
                'activo' => false,
                'nivel' => 1,
            ]
        );
    }
}
