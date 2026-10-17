<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Administrador;

class AdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Administrador::create([
            'nombre_completo' => 'Administrador Principal',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'activo' => true,
        ]);
        Administrador::create([
            'nombre_completo' => 'Cuenta Deshabilitada',
            'username' => 'inactivo',
            'password' => Hash::make('admin123'),
            'activo' => false,
        ]);
    }
}
