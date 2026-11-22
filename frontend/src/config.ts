// Configuracion de conexion con la API de Laravel.
//
// Actividad 5.1: el backend corre con auth:sanctum comentado en
// routes/api.php, por lo que las peticiones no llevan Authorization.

// URL base del modulo administrativo. Cambia el puerto si usas otro.
export const API_BASE = 'http://localhost:8000/api/v1/admin'

// Construye la URL completa para un recurso. Ej: apiUrl('alimentos')
export const apiUrl = (recurso: string): string => `${API_BASE}/${recurso}`

// Imagen de contingencia cuando la ruta devuelta no es accesible.
export const IMAGEN_PLACEHOLDER =
  'data:image/svg+xml;utf8,' +
  encodeURIComponent(
    '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300">' +
      '<rect width="400" height="300" fill="#e5e7eb"/>' +
      '<text x="200" y="150" font-family="sans-serif" font-size="16" ' +
      'fill="#9ca3af" text-anchor="middle">Sin imagen</text>' +
      '</svg>',
  )
