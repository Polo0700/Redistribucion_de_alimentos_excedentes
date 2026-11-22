import type { Solicitud } from "./solicitud";

// Fuente: app/Http/Resources/EntregaResource.php
export interface Entrega {
    id_entrega: number;
    id_solicitud: number;
    fecha_entrega: string | null;
    responsable: string;
    // Crudo, sin castear a booleano.
    estado: string;
    observaciones: string | null;
    // Solo aparece si el backend hizo ->with('solicitud').
    solicitud?: Solicitud;
}
