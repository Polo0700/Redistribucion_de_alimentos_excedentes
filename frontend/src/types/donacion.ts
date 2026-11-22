import type { Alimento } from "./alimento";
import type { Usuario } from "./usuario";

// Fuente: app/Http/Resources/DetalleDonacionResource.php
export interface DetalleDonacion {
    id_detalle_donacion: number;
    id_donacion: number;
    id_alimento: number;
    cantidad: number;
    observaciones: string | null;
    alimento?: Alimento;
}

// Fuente: app/Http/Resources/DonacionResource.php
export interface Donacion {
    id_donacion: number;
    id_usuario: number;
    fecha_donacion: string;
    fecha_limite: string | null;
    ubicacion: string;
    // Crudo, sin castear a booleano (verificado: "Disponible").
    estado: string;
    observaciones: string | null;
    usuario?: Usuario;
    // No viene en la respuesta actual: whenLoaded('detalles') no se cargo.
    detalles?: DetalleDonacion[];
}
