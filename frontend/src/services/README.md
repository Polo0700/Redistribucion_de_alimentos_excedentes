# Servicio HTTP (capa de servicios)

Aqui va la logica de red de la aplicacion. **Los componentes nunca deben
llamar a `fetch` o `axios` directamente** — siempre pasan por este capa.

Motivo (rúbrica, 20 pts): *«Organiza el proyecto de forma modular (servicios
HTTP, modelos, componentes)»*. Si todo el HTTP vive dentro de un solo
componente, la rúbrica lo castiga con 10 pts.

---

## 0. Paso obligatorio en el backend antes de probar

En `routes/api.php` del proyecto Laravel, comentar el middleware de token
para las rutas del modulo administrativo:

```php
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // ^ comentar o retirar 'auth:sanctum' temporalmente para la 5.1
```

Sin esto, toda peticion responde `401`. Es requisito del enunciado y hay
que mostrarlo en el video y en el PDF.

---

## 1. La forma real del JSON (ojo con esto)

El backend pagina los listados de **5 en 5**. El array NO esta en la raiz:

```
GET /api/v1/admin/alimentos
{
  "data": [ ...alimentos... ],          <-- el array esta AQUI
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta":  { "current_page": 1, "last_page": 3, "per_page": 5, "total": 14 }
}

GET /api/v1/admin/alimentos/1
{
  "data": { "id_alimento": 1, "nombre": "...", ... }   <-- objeto unico
}
```

Errores (manejados por `bootstrap/app.php`):

```
{ "success": false, "message": "...", "data": null, "errors": null }
```

**Imagenes**: no son base64, son URL publica.

```json
"imagen_url": "http://localhost:8000/storage/alimentos/Alimento_1_1.jpg"
```

---

## 2. Opcion A — Fetch (nativo, cero dependencias)

```ts
// src/services/alimentos.ts
import { apiUrl } from '../config'

export async function getRecursos() {
  const res = await fetch(apiUrl('alimentos'))

  // Fetch NO lanza error en 404 ni 500. Hay que revisarlo a mano.
  if (!res.ok) {
    throw new Error(`Error HTTP ${res.status}`)
  }

  const cuerpo = await res.json()
  return cuerpo.data as Alimento[]       // <- ojo: doble .data
}

export async function getRecursoById(id: number) {
  const res = await fetch(apiUrl(`alimentos/${id}`))
  if (!res.ok) throw new Error(`Error HTTP ${res.status}`)

  const cuerpo = await res.json()
  return cuerpo.data as Alimento         // <- objeto unico
}
```

- Pro: cero dependencias.
- Contra: chequear `res.ok` a mano, `.json()` a mano, sin interceptor para
  el token de la 5.2.

---

## 3. Opcion B — Axios (recomendada)

```bash
npm install axios
```

```ts
// src/services/alimentos.ts
import axios from 'axios'
import { API_BASE } from '../config'

// Instancia reutilizable: el baseURL se configura UNA sola vez.
const api = axios.create({ baseURL: API_BASE })

export async function getRecursos() {
  const { data } = await api.get('/alimentos')
  return data.data as Alimento[]         // <- ojo: doble .data
}

export async function getRecursoById(id: number) {
  const { data } = await api.get(`/alimentos/${id}`)
  return data.data as Alimento
}
```

- Pro: **revienta solo** en cualquier status fuera de 2xx (404/500 caen al
  `catch` sin chequear nada), `data` ya viene parseado, y para la 5.2
  agregas el token con un interceptor en un solo lugar.

```ts
// Para la Actividad 5.2 (con token)
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})
```

---

## 4. Como usarlo desde un componente

```tsx
// Ejemplo: CatalogView con estados de carga, vacio y error
export default function AlimentosPage() {
  const [datos, setDatos] = useState<Alimento[]>([])
  const [estado, setEstado] = useState<'cargando' | 'ok' | 'error' | 'vacio'>('cargando')

  useEffect(() => {
    getRecursos()
      .then((lista) => {
        setDatos(lista)
        setEstado(lista.length === 0 ? 'vacio' : 'ok')
      })
      .catch(() => setEstado('error'))
  }, [])

  if (estado === 'cargando') return <Spinner />
  if (estado === 'error')    return <MensajeError />
  if (estado === 'vacio')    return <MensajeVacio />

  return <Tabla datos={datos} />
}
```

---

## 5. Imagenes con contingencia

`imagen_url` puede ser `null` o la ruta puede no existir. Usar
`IMAGEN_PLACEHOLDER` de `src/config.ts`:

```tsx
<img
  src={alimento.imagen_url ?? IMAGEN_PLACEHOLDER}
  onError={(e) => { e.currentTarget.src = IMAGEN_PLACEHOLDER }}
  alt={alimento.nombre}
/>
```

`onError` cubre el caso en que la URL esta rota: el enunciado lo pide
explicitamente («imagen por defecto (placeholder) si la ruta devuelta no
es accesible»).

---

## 6. CORS

Laravel 12 trae el middleware `HandleCors` activo por defecto (permite
`api/*` desde cualquier origen). No hay que configurar nada mientras el
frontend corra en otro puerto. Si fallara, revisar que las respuestas
lleguen con la cabecera `Access-Control-Allow-Origin`.

Probar siempre con cabecera `Accept: application/json`; sin ella Laravel
redirige a `/login` y devuelve 405, que es un falso positivo.
