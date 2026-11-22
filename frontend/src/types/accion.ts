import type { Usuario } from "./usuario";

// Fuente: app/Http/Resources/AccionImportanteResource.php
export interface AccionImportante {
    id_accion: number;
    id_usuario: number;
    accion: string;
    tabla_afectada: string;
    descripcion: string | null;
    fecha_hora: string;
    ip_origen: string;
    // Solo aparece si el backend hizo ->with('usuario').
    usuario?: Usuario;
}
