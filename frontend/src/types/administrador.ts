// Fuente: app/Http/Resources/AdministradorResource.php
export interface Administrador {
    id: number;
    nombre_completo: string;
    username: string;
    // asset('storage/...') o null si no tiene foto.
    foto_url: string | null;
    activo: boolean;
    // Verificado en vivo: llega como numero (nivel = 3), no como texto.
    nivel: number;
    created_at: string | null;
    updated_at: string | null;
}
