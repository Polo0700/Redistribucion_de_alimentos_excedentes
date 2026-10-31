<?php

namespace Tests\Concerns;

use App\Models\Administrador;

trait AutenticaComoAdmin
{
    // crea un admin nivel 3 y lo autentica con el guard admin
    protected function actingAsAdmin(int $nivel = 3): Administrador
    {
        $administrador = Administrador::create([
            'nombre_completo' => 'Admin de Prueba',
            'username' => 'admin-test-' . uniqid(),
            'password' => bcrypt('12345678'),
            'activo' => true,
            'nivel' => $nivel,
        ]);

        $this->actingAs($administrador, 'admin');

        return $administrador;
    }
}
