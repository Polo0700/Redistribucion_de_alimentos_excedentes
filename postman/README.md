# Guía: colección de pruebas de la API

Colección de Postman para los 9 recursos vigentes del proyecto. Vive en el repo
(`postman/collections/`) para que viaje con el código y se revise en los PRs.

| Qué | Dónde |
| --- | --- |
| 109 requests en 10 carpetas (9 admin + `Cliente`) | `collections/redistribucion-alimentos-api/` |
| Ambiente `Local` | `environments/local.environment.yaml` |
| Vínculo con el workspace | `../.postman/resources.yaml` |

---

## 0. Requisitos

- PHP 8.2+ y Composer
- MySQL (XAMPP o instalado aparte)
- Node.js y npm (para instalar la CLI de Postman)
- Git
- Cuenta de Postman (solo necesaria si vas a sincronizar con la nube)

---

## 1. Código

Si ya tienes el proyecto:

```bash
git pull
```

No hace falta "importar" nada: la colección son archivos normales del repo.

---

## 2. Base de datos (solo la primera vez)

El proyecto usa MySQL en el puerto **3306**, base `redistribucion_alimentos`.

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS redistribucion_alimentos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed
```

Si usas XAMPP, arráncalo desde su panel de control antes de esto.

---

## 3. Arrancar el backend

Revisa que `.env` tenga esto (no está versionado, cada quien lo tiene local):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=redistribucion_alimentos
DB_USERNAME=root
DB_PASSWORD=
```

Y levanta el servidor:

```bash
php artisan serve
```

La API queda en `http://127.0.0.1:8000/api` (ese es el `baseUrl` del ambiente `Local`).

---

## 4. Correr la colección

Con la CLI de Postman instalada (`npm install -g postman-cli` o `npx postman`):

```bash
postman collection run postman/collections/redistribucion-alimentos-api \
  --environment postman/environments/local.environment.yaml
```

Sin cuenta de Postman también funciona: la CLI solo necesita el backend arriba.

### Qué se puede correr sin tocar datos

| Grupo | Requests | ¿Escribe en la DB? |
| --- | --- | --- |
| Lectura | `index`, `show`, `trashed`, `create`, `edit` | No |
| Escritura | `store`, `update`, `destroy`, `restore`, `force-destroy` | Sí |

Los 9 requests **Crear** (`store`) sí insertan un registro de prueba. Los de
`update`, `destroy`, `restore` y `force-destroy` usan la variable `idPrueba`,
que está **vacía a propósito**: hasta que pongas ahí el id de un registro que
puedas sacrificar, esos requests no tocan nada.

Recomendación: corre primero `index` y `create`; para los de escritura, crea un
registro, copia su id en `idPrueba` y entonces corre el resto en orden.

---

## 5. Qué valida cada request

Cada request trae sus propias aserciones (`pm.test`):

- `index` y `trashed`: la respuesta trae la clave del listado (`roles`, `usuarios`, …).
- `show`: 200 con la clave del registro, o 404 con `message`.
- `store`: 201 y `message`.
- `update`: 200 y `message`.
- `destroy` y `restore`: `message`.
- `force-destroy`: sin aserción de cuerpo; si el registro tiene dependencias
  responde 409 con `error` (eso es correcto, no es una falla del request).

La colección agrega `Accept: application/json` en un script de nivel colección:
sin eso, Laravel devuelve HTML en los 404 y redirecciones 302 en las validaciones.

---

## 6. Lint

Antes de subir cambios a la colección:

```bash
postman collection lint postman/collections/redistribucion-alimentos-api
```

Debe decir `No issues found` (109 requests escaneados).

---

## 7. Sincronizar con el workspace (opcional)

Solo si tienes sesión iniciada en la CLI (`postman login`) y permisos en el
workspace `Polo0700/Redistribucion_de_alimentos_excedentes`:

```bash
postman collection push postman/collections/redistribucion-alimentos-api
postman environment push postman/environments/local.environment.yaml
```

El `push` agrega (o actualiza) el bloque `cloudResources` de
`../.postman/resources.yaml`. Si no vas a sincronizar, ese archivo se queda solo
con el `workspace`, como está ahora.

---

## 8. Recursos incluidos

| Carpeta | URI base |
| --- | --- |
| Roles | `/api/roles` |
| Usuarios | `/api/usuarios` |
| Cuentas de acceso | `/api/cuentas-acceso` |
| Categorias de alimento | `/api/categorias` |
| Alimentos | `/api/alimentos` |
| Donaciones | `/api/donaciones` |
| Solicitudes | `/api/solicitudes` |
| Entregas | `/api/entregas` |
| Acciones importantes | `/api/acciones-importantes` |
| Cliente (datos propios) | `/api/v1/cliente` |

Quedan fuera de la API, por decisión del dominio actual: carritos, listas de
deseos y sus detalles. Siguen existiendo como tablas, pero no tienen rutas,
controladores ni requests.

---

## 9. Colección `administrador`

Colección aparte, solo del CRUD de la tabla `administradores`:

| Qué | Dónde |
| --- | --- |
| 10 requests en 1 carpeta (`Administradores`) | `collections/administrador/` |
| Ambiente `Local` | `environments/local.environment.yaml` |

```bash
postman collection run postman/collections/administrador \
  --environment postman/environments/local.environment.yaml
```

| Request | Endpoint |
| --- | --- |
| Listar administradores | `GET /api/administradores` |
| Obtener administrador por id | `GET /api/administradores/{id}` |
| Datos para el formulario de creación | `GET /api/administradores/create` |
| Crear administrador | `POST /api/administradores` |
| Datos para el formulario de edición | `GET /api/administradores/{id}/edit` |
| Actualizar administrador | `PUT /api/administradores/{id}` |
| Eliminar administrador | `DELETE /api/administradores/{id}` |
| Listar administradores eliminados (papelera) | `GET /api/administradores/eliminados` |
| Restaurar administrador | `PATCH /api/administradores/{id}/restore` |
| Eliminar administrador definitivamente | `DELETE /api/administradores/{id}/force` |

Notas:

- La colección usa la variable `idAdministrador` (por defecto `1`); el request
  **Crear administrador** la actualiza con el id que acaba de crear, así que el
  resto del flujo se puede correr en orden.
- El `username` de prueba lleva `{{$timestamp}}`, para poder repetir el run sin
  chocar con el índice único.
- Antes de usarla hay que correr `php artisan migrate`: el CRUD necesita las
  columnas `foto`, `nivel` y `deleted_at` de `administradores`.
- Lint: `postman collection lint postman/collections/administrador`
  (debe decir `No issues found`, 10 requests escaneados).

---

## 10. Requests `create` / `edit` (obsoletos, no tocar)

> **Nota para futuras revisiones y conversaciones:** no preguntar por estos
> archivos, ni intentar "arreglarlos" ni borrarlos.

En `collections/redistribucion-alimentos-api/` hay **18 requests** (uno
`create.request.yaml` y uno `edit.request.yaml` por cada carpeta de recurso)
que apuntan a las rutas `GET .../create` y `GET .../{id}/edit`.

Esas rutas **ya no existen**: se eliminaron al pasar el módulo administrativo a
**API pura** en la Actividad 4.3, porque `create()` y `edit()` devolvían datos
para formularios HTML y la API ya no tiene vistas.

Se dejaron los archivos **a propósito**:

- No afectan el resto de la colección.
- No rompen el lint (`postman collection lint` sigue en `No issues found`).
- No se ejecutan en el flujo normal (los de lectura/escritura reales son
  `store`, `update`, `show`, `index`, `destroy`, `restore`, `force-destroy`).

Documentado también en `AGENTS.md` (`<POSTMAN_KNOWN_STATE>`).

---

## 11. Carpeta `Cliente` (Actividad 4.4)

Nueva carpeta con los endpoints del módulo del cliente y los escenarios de
**propiedad de datos** del enunciado:

| Qué | Dónde |
| --- | --- |
| 19 requests en 1 carpeta (`Cliente`) | `collections/redistribucion-alimentos-api/Cliente/` |

```bash
postman collection run postman/collections/redistribucion-alimentos-api \
  --environment postman/environments/local.environment.yaml
```

### Variables propias de la carpeta

| Variable | La llena | Uso |
| --- | --- | --- |
| `tokenClienteA` | `Registro cliente A` o `Login cliente A` | Bearer por defecto de la carpeta (auth de la carpeta usa este token) |
| `tokenClienteB` | `Registro cliente B` o `Login cliente B` | Se usa solo en los requests de intrusión (`-> 403`) |
| `idUsuarioA` | `Login/Registro cliente A` | Verifica que el listado solo traiga registros del dueño |
| `idSolicitudA` | `Crear registro propio` | Id que usan detalle, intrusión y cancelación |
| `idAlimentoCatalogo` | `Catalogo publico` | Alimento con el que se crea el registro propio |

Todos los requests de la carpeta heredan el Bearer `tokenClienteA` de la
definición de la carpeta; los que necesitan otro comportamiento lo sobreescriben
a nivel request (`noauth` o Bearer `tokenClienteB`).

### Orden de ejecución del flujo

1. `Registrar cliente A` y `Registrar cliente B` (si ya existen, responden 422
   por el correo único: en ese caso corre los dos `Login`).
2. `Login cliente A` y `Login cliente B` (guardan ambos tokens).
3. `Catalogo publico` y `Detalle del catalogo`: sin token, deben responder 200.
4. `Perfil del cliente A` y `Actualizar perfil`: enmascaran datos propios.
5. `Crear registro propio` (201, guarda `idSolicitudA`), `Mis registros`,
   `Mis donaciones` y `Detalle de registro propio`.
6. **Pruebas de propiedad de datos:** `Registro de A consultado con token de B`
   y `Cancelar registro de A con token de B` deben responder **403 Forbidden**
   (Policy). Si responden 200, hay una vulnerabilidad.
7. `Crear registro sin datos` responde **422** con los errores por campo.
8. `Cancelar registro propio` (200, estado `cancelada`) y un nuevo intento
   (`Re-cancelar registro ya cancelado`) responde **403**: el estado ya no es
   cancelable.
9. `Ruta protegida SIN token` responde **401**.
10. `Logout cliente A` revoca el token y reintenta `GET /perfil` con el token
    revocado: debe volver a responder 401.

Requisitos del run: backend arriba (`php artisan serve`) y al menos un alimento
activo en la DB (el catálogo público llena `idAlimentoCatalogo`; si está vacío,
crea uno primero desde la carpeta `Alimentos` del área admin).

