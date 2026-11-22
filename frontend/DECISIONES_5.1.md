# Decisiones no repetitivas de la Actividad 5.1

## 1. Alcance: qué fue talacha

Sí, casi todo fue talacha. De los 27 archivos creados para los 9 recursos restantes:

- Los **9 servicios** (`src/services/*.ts`) son el mismo archivo con 3 parametros cambiados:
  el tipo importado, el segmento del endpoint y el tipo del `Promise`.
- Las **9 paginas** (`src/pages/*.tsx`) repiten el mismo esqueleto de
  `AlimentosPage.tsx` lineas 35-97: dos `useState`, un `useEffect`, cuatro ramas
  de estado y un `map` de tarjetas.

Eso es deliberado. Cada pagina queda autocontegra y se lee igual que la de
Alimentos, que es la que se uso como plantilla.

Lo que **no** se pudo copiar fue la definicion de los tipos. Esa se derivo de los
`JsonResource` del backend y del JSON real de la API. A continuacion estan esos
fragmentos, por que se escribieron asi y que areas afectan.

---

## 2. `nivel` es numero, no texto

**Archivo:** `src/types/administrador.ts` lineas 9-10

```ts
// Verificado en vivo: llega como numero (nivel = 3), no como texto.
nivel: number;
```

**Por que.** La API devuelve `"nivel": 3` (numero). En cambio el `CAMPOS` de
`src/pages/AdministradoresPage.tsx` linea 11 dice `type: "texto"`. Ese valor no
es un tipo de TypeScript: `PageShell.tsx` linea 99 solo lo pinta como una
etiqueta en la tabla de campos. Copiar la etiqueta como tipo habria sido un error
silencioso, porque el servicio hace `cuerpo.data as Administrador[]` y TypeScript
no valida el JSON en tiempo de ejecucion.

**Areas afectadas.** El tipo de `types/administrador.ts`, el servicio
`services/administradores.ts` y la linea 83 de `AdministradoresPage.tsx`, que
muestra `{administrador.nivel}`.

---

## 3. `estado` es texto en Donaciones, Solicitudes y Entregas

**Archivos y lineas:**

- `src/types/donacion.ts` lineas 21-22
- `src/types/solicitud.ts` linea 20
- `src/types/solicitud.ts` lineas 10-11 (`DetalleSolicitud`)
- `src/types/entrega.ts` lineas 9-10

```ts
// Crudo, sin castear a booleano (verificado: "Disponible").
estado: string;
```

**Por que.** Los Resources de esos tres dominios no hacen casteo:

- `app/Http/Resources/DonacionResource.php` linea 18: `'estado' => $this->estado`
- `app/Http/Resources/SolicitudResource.php` linea 16: `'estado' => $this->estado`
- `app/Http/Resources/EntregaResource.php` linea 17: `'estado' => $this->estado`

En cambio `RolResource.php` linea 16, `CategoriaAlimentoResource.php` linea 16 y
`UsuarioResource.php` linea 21 si hacen `(bool) $this->estado`. El JSON en vivo
confirma la diferencia: donaciones devuelve `"estado": "Disponible"`.

Copiar el `boolean` de Alimentos habria producido un bug visual: con un ternario
`estado ? "Activo" : "Inactivo"`, cualquier texto no vacio da siempre "Activo",
sin error de compilacion.

**Areas afectadas.** Cuatro interfaces de tipos, los tres servicios
correspondientes y las tarjetas de `DonacionesPage.tsx`, `SolicitudesPage.tsx` y
`EntregasPage.tsx`, que muestran el texto crudo sin ternario.

---

## 4. `detalles` es opcional porque `whenLoaded` no lo trae

**Archivo:** `src/types/donacion.ts` lineas 25-26 (igual en `solicitud.ts` 24-25)

```ts
// No viene en la respuesta actual: whenLoaded('detalles') no se cargo.
detalles?: DetalleDonacion[];
```

**Por que.** `DonacionResource.php` linea 21 y `SolicitudResource.php` linea 20
envuelven la relacion en `whenLoaded('detalles')`. Verificado en vivo:
`GET /api/v1/admin/donaciones/2` no incluye la clave `detalles` ni en el listado
ni en el detalle. Declararlo obligatorio habria sido mentirle al tipo, y un
`donacion.detalles.map(...)` sin `?.` reventaria en cuanto se renderizara.

**Areas afectadas.** `types/donacion.ts`, `types/solicitud.ts` y, mas adelante,
la vista de detalle: si se quiere mostrar la lista de alimentos de una donacion,
hay que usar `donacion.detalles?.map(...)` o cargar la relacion en el backend.

---

## 5. La foto de administrador usa el mismo placeholder que Alimentos

**Archivo:** `src/pages/AdministradoresPage.tsx` lineas 5 y 65-74

```tsx
import { IMAGEN_PLACEHOLDER } from "../config";
```

```tsx
<img
    src={administrador.foto_url ?? IMAGEN_PLACEHOLDER}
    onError={(e) => {
        e.currentTarget.src = IMAGEN_PLACEHOLDER;
    }}
    className="h-16 w-16 rounded-full object-cover"
    alt={administrador.nombre_completo}
/>
```

**Por que.** `AdministradorResource.php` linea 16 genera
`asset('storage/'.$this->foto) : null`, igual que `AlimentoResource.php` linea 18.
En vivo, los 5 administradores tienen `foto_url: null`, asi que sin el `??` no se
pintaria ninguna imagen. Es el unico recurso ademas de Alimentos que expone una
URL de imagen.

**Areas afectadas.** `src/config.ts` linea 13 (constante SVG local, sin red),
`types/administrador.ts` linea 7 y la tarjeta de `AdministradoresPage.tsx`.

---

## 6. Tres estados vacios reales, no errores

**Paginas:** `SolicitudesPage.tsx`, `EntregasPage.tsx` y `AccionesPage.tsx`.

Verificado en vivo: los tres endpoints devuelven `HTTP 200` con `data: []` y
`meta.total = 0`. Sus tablas estan vacias. Por eso esas paginas entran en la rama
`estado === "vacio"` y muestran "No hay ... disponibles." en vez de tarjetas.

**Por que importa.** No hay que "arreglar" nada: es el comportamiento correcto y
sirve para demostrar la vista de contingencia del enunciado sin tener que borrar
datos.

---

## 7. Verificacion realizada

```
tsc -b          -> 0 errores
oxlint          -> 0 advertencias
vite build      -> OK (2160 modulos)

10 URLs extraidas de src/services/ -> HTTP 200 en las 10
```

Las rutas del cliente (4.4), la autenticacion de `routes/api.php` y los README de
`src/types/` y `src/services/` no se tocaron.

---

## 8. Lo que queda

La vista de detalle: conectar `getRecursosById` (declarado en los 10 servicios,
incluido `services/alimentos.ts` lineas 12 y 22) con un clic en las tarjetas.
Ahi si hay que tomar decisiones de diseno y no alcanza con cambiar parametros.
