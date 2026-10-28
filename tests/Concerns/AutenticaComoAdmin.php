<?php

namespace Tests\Concerns;

use App\Models\Administrador;

trait AutenticaComoAdmin
{
    /**
     * Crea un administrador (nivel 3) y autentica el request con el guard 'admin'.
     * La autorización se evalúa sobre el modelo Administrador, no sobre CuentaAcceso.
     */
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
