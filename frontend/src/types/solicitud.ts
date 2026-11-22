import type { Alimento } from "./alimento";
import type { Usuario } from "./usuario";

// Fuente: app/Http/Resources/DetalleSolicitudResource.php
export interface DetalleSolicitud {
    id_detalle_solicitud: number;
    id_solicitud: number;
    id_alimento: number;
    cantidad: number;
    // Crudo, sin castear a booleano.
    estado: string;
    alimento?: Alimento;
}

// Fuente: app/Http/Resources/SolicitudResource.php
export interface Solicitud {
    id_solicitud: number;
    id_usuario: number;
    fecha_solicitud: string;
    estado: string;
    direccion_entrega: string;
    observaciones: string | null;
    usuario?: Usuario;
    // No viene en la respuesta actual: whenLoaded('detalles') no se cargo.
    detalles?: DetalleSolicitud[];
}
