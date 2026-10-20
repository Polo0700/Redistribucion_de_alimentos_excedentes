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
