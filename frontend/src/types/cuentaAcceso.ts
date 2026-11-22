import type { Usuario } from "./usuario";

// Fuente: app/Http/Resources/CuentaAccesoResource.php
export interface CuentaAcceso {
    id_cuenta: number;
    id_usuario: number;
    proveedor: string;
    identificador_externo: string | null;
    fecha_ultimo_acceso: string | null;
    // Solo aparece si el backend hizo ->with('usuario').
    usuario?: Usuario;
}
