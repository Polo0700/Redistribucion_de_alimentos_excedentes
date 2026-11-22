// Modelos de datos del recurso Alimento.
//
// Fuente: app/Http/Resources/AlimentoResource.php
//         app/Http/Resources/CategoriaAlimentoResource.php
//
// Regla: el tipo es una foto exacta del JSON que devuelve la API.
// Si difiere, TypeScript compila igual y falla en silencio.

// Categoria anidada dentro de un alimento.
export interface Categoria {
    id_categoria: number;
    nombre: string;
    descripcion: string | null;
    // Llega como boolean: true = activa, false = inactiva.
    estado: boolean;
}

// Un registro del catalogo de alimentos.
export interface Alimento {
    id_alimento: number;
    id_categoria: number;
    nombre: string;
    // Puede venir null si el alimento no tiene descripcion.
    descripcion: string | null;
    // true = activo, false = inactivo.
    estado: boolean;
    // URL publica: http://localhost:8000/storage/alimentos/...
    // Llega null cuando el alimento no tiene imagen cargada.
    imagen_url: string | null;
    // Solo aparece si el backend hizo ->with('categoria').
    categoria?: Categoria;
}
