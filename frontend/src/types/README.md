# Tipos (modelos de datos)

Aqui van las interfaces que representan los objetos que devuelve la API.
Separar los tipos de los componentes es parte del punto de «transformacion
de modelos de datos en el cliente» del enunciado.

---

## Alimento

Fuente: `app/Http/Resources/AlimentoResource.php` del backend.

```ts
// src/types/alimento.ts

/** Categoria anidada dentro de un alimento (cuando viene cargada). */
export interface Categoria {
  id_categoria: number
  nombre: string
}

/** Un registro del catálogo de alimentos. */
export interface Alimento {
  id_alimento: number
  id_categoria: number
  nombre: string
  descripcion: string | null
  /** true = activo, false = inactivo. */
  estado: boolean
  /** URL publica: http://localhost:8000/storage/alimentos/...
   *  Puede ser null si el alimento no tiene imagen. */
  imagen_url: string | null
  /** Solo aparece si el backend hizo ->with('categoria'). */
  categoria?: Categoria
}
```

---

## Respuesta paginada

El backend pagina de 5 en 5, asi que el listado llega envuelto:

```ts
// src/types/api.ts

/** Envelope paginado de Laravel (JsonResource sobre un paginator). */
export interface RespuestaPaginada<T> {
  data: T[]
  links: {
    first: string | null
    last: string | null
    prev: string | null
    next: string | null
  }
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number | null
    to: number | null
    path: string
  }
}

/** Respuesta de un recurso unico. */
export interface RespuestaRecurso<T> {
  data: T
}

/** Error estandarizado por bootstrap/app.php. */
export interface RespuestaError {
  success: false
  message: string
  data: null
  errors: Record<string, string[]> | null
}
```

---

## Uso tipado en el servicio

```ts
import type { Alimento } from '../types/alimento'
import type { RespuestaPaginada } from '../types/api'

export async function getRecursos(): Promise<Alimento[]> {
  const { data } = await api.get<RespuestaPaginada<Alimento>>('/alimentos')
  return data.data
}

export async function getRecursoById(id: number): Promise<Alimento> {
  const { data } = await api.get<RespuestaRecurso<Alimento>>(`/alimentos/${id}`)
  return data.data
}
```

Con `api.get<T>()` el generico tipa `data` automaticamente, y si la API
cambia de forma TypeScript te avisa en compilacion.

---

## Para los demas recursos

Copiar el mismo patron: crear `src/types/<recurso>.ts` con su interfaz,
leyendo los campos desde el `Resource` correspondiente del backend
(`app/Http/Resources/`).
