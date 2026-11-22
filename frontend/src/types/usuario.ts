import type { Rol } from "./rol";

// Fuente: app/Http/Resources/UsuarioResource.php
export interface Usuario {
    id: number;
    id_rol: number;
    nombre: string;
    apellido: string;
    correo: string;
    telefono: string | null;
    direccion: string | null;
    fecha_registro: string;
    estado: boolean;
    // Solo aparece si el backend hizo ->with('rol').
    rol?: Rol;
}
