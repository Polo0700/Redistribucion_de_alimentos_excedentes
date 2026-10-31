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
        // admin principal historico (nivel 3)
        Administrador::updateOrCreate(
            ['username' => 'admin'],
            [
                'nombre_completo' => 'Administrador Principal',
                'password' => Hash::make('admin123'),
                'activo' => true,
                'nivel' => 3,
            ]
        );

        // nivel 1: solo consulta
        Administrador::updateOrCreate(
            ['username' => 'admin1'],
            [
                'nombre_completo' => 'Admin Nivel 1 (Consulta)',
                'password' => Hash::make('admin123'),
                'activo' => true,
                'nivel' => 1,
            ]
        );

        // nivel 2: consulta, crear y editar
        Administrador::updateOrCreate(
            ['username' => 'admin2'],
            [
                'nombre_completo' => 'Admin Nivel 2 (Editor)',
                'password' => Hash::make('admin123'),
                'activo' => true,
                'nivel' => 2,
            ]
        );

        // nivel 3: acceso total
        Administrador::updateOrCreate(
            ['username' => 'admin3'],
            [
                'nombre_completo' => 'Admin Nivel 3 (Superadmin)',
                'password' => Hash::make('admin123'),
                'activo' => true,
                'nivel' => 3,
            ]
        );

        // cuenta inactiva para pruebas
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
