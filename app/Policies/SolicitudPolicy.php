<?php

namespace App\Policies;

use App\Models\CuentaAcceso;
use App\Models\Solicitud;

/**
 * Policy de propiedad de datos (Actividad 4.4).
 *
 * Compara la identidad del usuario autenticado vía Sanctum
 * ($cuenta->id_usuario) contra el propietario del registro
 * ($solicitud->id_usuario): un cliente solo puede ver, actualizar
 * o cancelar sus PROPIAS solicitudes.
 */
class SolicitudPolicy
{
    /**
     * Consulta del detalle: únicamente el dueño del registro.
     */
    public function view(CuentaAcceso $cuenta, Solicitud $solicitud): bool
    {
        return $solicitud->id_usuario === $cuenta->id_usuario;
    }

    /**
     * Actualización: únicamente el dueño del registro.
     */
    public function update(CuentaAcceso $cuenta, Solicitud $solicitud): bool
    {
        return $solicitud->id_usuario === $cuenta->id_usuario;
    }

    /**
     * Cancelación/eliminación: el cliente debe ser dueño del registro
     * y este debe encontrarse en un estado cancelable.
     */
    public function cancelar(CuentaAcceso $cuenta, Solicitud $solicitud): bool
    {
        return $solicitud->id_usuario === $cuenta->id_usuario
            && $solicitud->estado === 'pendiente';
    }

    /**
     * Alias de cancelar para usos genéricos de borrado.
     */
    public function delete(CuentaAcceso $cuenta, Solicitud $solicitud): bool
    {
        return $this->cancelar($cuenta, $solicitud);
    }
}
