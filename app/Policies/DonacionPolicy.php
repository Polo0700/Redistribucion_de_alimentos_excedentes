<?php

namespace App\Policies;

use App\Models\CuentaAcceso;
use App\Models\Donacion;

/**
 * Policy de propiedad de datos (Actividad 4.4).
 *
 * Compara la identidad del usuario autenticado vía Sanctum
 * ($cuenta->id_usuario) contra el propietario del registro
 * ($donacion->id_usuario): un cliente solo puede ver, actualizar
 * o cancelar sus PROPIAS donaciones.
 */
class DonacionPolicy
{
    /**
     * Consulta del detalle: únicamente el dueño del registro.
     */
    public function view(CuentaAcceso $cuenta, Donacion $donacion): bool
    {
        return $donacion->id_usuario === $cuenta->id_usuario;
    }

    /**
     * Actualización: únicamente el dueño del registro.
     */
    public function update(CuentaAcceso $cuenta, Donacion $donacion): bool
    {
        return $donacion->id_usuario === $cuenta->id_usuario;
    }

    /**
     * Cancelación/eliminación: el cliente debe ser dueño del registro
     * y este debe encontrarse en un estado cancelable.
     */
    public function cancelar(CuentaAcceso $cuenta, Donacion $donacion): bool
    {
        return $donacion->id_usuario === $cuenta->id_usuario
            && $donacion->estado === 'Disponible';
    }

    /**
     * Alias de cancelar para usos genéricos de borrado.
     */
    public function delete(CuentaAcceso $cuenta, Donacion $donacion): bool
    {
        return $this->cancelar($cuenta, $donacion);
    }
}
