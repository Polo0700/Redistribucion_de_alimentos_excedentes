<?php

namespace Tests\Concerns;

use App\Models\CuentaAcceso;
use App\Models\Rol;
use App\Models\Usuario;

trait AutenticaComoAdmin
{
    /**
     * Crea un administrador mínimo y autentica el request como él (guard admin).
     */
    protected function actingAsAdmin(): CuentaAcceso
    {
        $rol = Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Acceso al panel administrativo',
            'estado' => 1,
        ]);

        $usuario = Usuario::create([
            'id_rol' => $rol->id,
            'nombre' => 'Admin',
            'apellido' => 'Sistema',
            'correo' => 'admin-sistema@test.com',
            'telefono' => '0000000000',
            'direccion' => 'Direccion test',
            'fecha_registro' => now(),
            'estado' => 1,
        ]);

        $cuenta = CuentaAcceso::create([
            'id_usuario' => $usuario->id,
            'proveedor' => 'Correo',
            'identificador_externo' => 'admin-sistema@test.com',
            'contrasena_hash' => bcrypt('12345678'),
            'fecha_ultimo_acceso' => now(),
        ]);

        $this->actingAs($cuenta, 'admin');

        return $cuenta;
    }
}
