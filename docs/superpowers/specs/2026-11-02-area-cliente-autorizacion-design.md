# Spec — Área cliente y autorización de la API (A3_4)

**Fecha:** 2026-11-02
**Actividad:** A3_4 — Diseño y Configuración de una API RESTful y sus endpoints para las áreas administrativa y cliente en Laravel
**Estado:** Diseño aprobado por el usuario (opción C)

## 1. Objetivo

Completar el bloque técnico pendiente de la A3_4:

1. Área cliente funcional bajo `/api/v1/cliente/` con autenticación independiente.
2. Autorización real: tipo de token en rutas admin, niveles de administrador, propiedad de registros del cliente.
3. Tests que cubran los escenarios 3-7 del enunciado (y niveles admin).

## 2. Decisiones de diseño (aprobadas)

| Decisión | Elección |
|---|---|
| Identidad del cliente | `CuentaAcceso` (tabla real de credenciales: `contrasena_hash`, `proveedor`, relación `id_usuario` → `Usuario`) |
| Recursos del área cliente | `perfil`, `solicitudes` propias, `donaciones` propias, `alimentos` (catálogo solo lectura) |
| Niveles de administrador | 3 jerárquicos: nivel 3 = CRUD completo; nivel 2 = leer/crear/editar sin eliminar; nivel 1 = solo lectura |
| Mecanismo de autorización | **Opción C (mixta):** middleware tipo-de-token + Gates para reglas generales; Policies para registros con dueño (Solicitud, Donacion) |

Restricciones del usuario: **sin comentarios en el código**; **tocar solo los archivos necesarios**.

## 3. Autenticación del área cliente

- Mecanismo: **Sanctum tokens personales** (`Authorization: Bearer {token}`), igual que el admin pero sobre otro modelo.
- Modelo tokenable: `CuentaAcceso` + `HasApiTokens`.
- Guard nuevo `cliente` en `config/auth.php`:
  ```php
  'cliente' => ['driver' => 'session', 'provider' => 'cuentas_acceso'],
  'cuentas_acceso' => ['driver' => 'eloquent', 'model' => CuentaAcceso::class],
  ```
- Contraseñas: `Hash::check` contra `contrasena_hash`; nunca en texto plano, nunca en respuestas (`$hidden` ya lo incluye).

### Endpoints de auth

| Método | URL | Función | Éxito | Errores |
|---|---|---|---|---|
| POST | `/api/v1/cliente/registro` | Crea `Usuario` + `CuentaAcceso` (proveedor `Correo`), devuelve token | 201 `{token, usuario}` | 422 validación (correo duplicado = regla `unique` en 422) |
| POST | `/api/v1/cliente/login` | Valida credenciales, emite token | 200 `{token, usuario}` | 401 credenciales, 403 cuenta inactiva, 422 vacío, 405 en GET |
| POST | `/api/v1/cliente/logout` | Revoca token actual | 200 | 401 sin token |
| GET | `/api/v1/cliente/perfil` | Devuelve usuario autenticado (+ datos de cuenta sin hash) | 200 | 401 |
| PATCH | `/api/v1/cliente/perfil` | Actualiza campos permitidos: `nombre`, `apellido`, `telefono`, `direccion` | 200 | 401, 422 |

- `id_usuario` de las operaciones **siempre del token**, nunca del body.
- Login solo acepta proveedor `Correo` (las cuentas `Google` no tienen contraseña local).

## 4. Recursos del área cliente

### Solicitudes (propias)

| Método | URL | Regla | Éxito | Errores |
|---|---|---|---|---|
| GET | `/api/v1/cliente/solicitudes` | `where id_usuario = token`, paginado | 200 lista | 401 |
| POST | `/api/v1/cliente/solicitudes` | `id_usuario` del token; valida `direccion_entrega`, `detalles` | 201 | 401, 422 |
| GET | `/api/v1/cliente/solicitudes/{id}` | Policy `view` → si no es suya: **404** (no exponer que existe) | 200 | 401, 404 |
| DELETE | `/api/v1/cliente/solicitudes/{id}` | Policy `delete` → solo propia (borrado lógico, sin chequeo de estado) | 204 | 401, 404 si no es suya |

### Donaciones (propias)

| Método | URL | Regla | Éxito | Errores |
|---|---|---|---|---|
| GET | `/api/v1/cliente/donaciones` | `where id_usuario = token`, paginado | 200 lista | 401 |
| POST | `/api/v1/cliente/donaciones` | `id_usuario` del token; valida `fecha_limite`, `ubicacion`, `detalles` | 201 | 401, 422 |
| GET | `/api/v1/cliente/donaciones/{id}` | Policy `view` → 404 si no es suya | 200 | 401, 404 |

### Catálogo

| Método | URL | Regla | Éxito |
|---|---|---|---|
| GET | `/api/v1/cliente/alimentos` | solo lectura, paginado, sin escritura | 200 |
| GET | `/api/v1/cliente/alimentos/{id}` | solo lectura | 200 / 404 |

## 5. Autorización

### 5.1 Tipo de token en rutas admin (middleware)

`EnsureEsAdmin` aplicado al grupo `v1/admin` (además de `auth:sanctum`):

- Si el modelo autenticado **no es** `Administrador` → **403** `{"message": "..."}`.
- Esto impide que un token de `CuentaAcceso` (cliente) pase por `/admin`.

### 5.2 Gates por nivel de administrador

Definidos en **`AppServiceProvider::boot()`** (Laravel 12 no trae `AuthServiceProvider`; además así coincide con la rama de referencia `apiv2_experimental`):

- Nombres de habilidades **idénticos a la referencia del equipo**: `consultar`, `crear`, `editar`, `eliminar`, `administrar-administradores`.
- `Gate::before`: nivel 3 y `activo` → `true` en cualquier habilidad.
- Todas verifican `$user instanceof Administrador && $user->activo && <nivel>`.

| Habilidad | Nivel 1 | Nivel 2 | Nivel 3 |
|---|---|---|---|
| `consultar` | ✅ | ✅ | ✅ |
| `crear` | ❌ | ✅ | ✅ |
| `editar` | ❌ | ✅ | ✅ |
| `eliminar` | ❌ | ❌ | ✅ |
| `administrar-administradores` | ❌ | ❌ | ✅ |

- Aplicación: **middleware `->middleware('can:consultar')` en `routes/api.php`** (patrón de la referencia) — en contexto API, la excepción de autorización se serializa como **403 JSON** automáticamente. **No se tocan los controladores para esto.**
- El nivel sale del registro autenticado (`$request->user()->nivel`), nunca del body.
- Agrupación: `administradores` bajo `can:administrar-administradores`; el resto de recursos CRUD con `can:consultar` en rutas de lectura, `can:crear` en POST, `can:editar` en PUT/PATCH, `can:eliminar` en DELETE.
- Agregar `->whereNumber('id')` a las rutas `{id}` (evita que `/create` caiga en `{id}` → 404), como en la referencia.

### 5.3 Policies (registros con dueño)

| Policy | Acciones | Regla |
|---|---|---|
| `SolicitudPolicy` | `view`, `delete` | `solicitud.id_usuario === cuenta.usuario.id` |
| `DonacionPolicy` | `view` | `donacion.id_usuario === cuenta.usuario.id` |

- Registradas en `AppServiceProvider` (junto a los Gates).
- En controladores cliente: `authorize('view', $solicitud)` → **403** cuando aplica; para `show` se usa **404** vía `findOrFail` filtrado (evita enumeración) y la Policy cubre `delete`.
- Nota: el enunciado permite 403 o 404 para propiedad; se documenta en el catálogo: **404 en consultas** (no revelar existencia), **403 en acciones** con identidad conocida.

### 5.4 Independencia de áreas

- Un token cliente en `/api/v1/admin/*` → 403 (`EnsureEsAdmin`).
- Un token admin en `/api/v1/cliente/*` protegido → 403 (`EnsureEsCliente`, aplicado al grupo `v1/cliente` autenticado, simétrico al admin).
- Los niveles/nunca se envían en el body: salen del registro autenticado.

## 6. Archivos a crear/tocar

**Crear:**
- `app/Http/Controllers/AuthClienteController.php` (registro, login, logout, perfil get/patch)
- `app/Http/Controllers/ClienteSolicitudController.php`
- `app/Http/Controllers/ClienteDonacionController.php`
- `app/Http/Controllers/ClienteAlimentoController.php` (solo index/show)
- `app/Http/Middleware/EnsureEsAdmin.php`
- `app/Http/Middleware/EnsureEsCliente.php`
- `app/Policies/SolicitudPolicy.php`
- `app/Policies/DonacionPolicy.php`
- `tests/Feature/ClienteAuthTest.php`
- `tests/Feature/ClienteRecursosTest.php`
- `tests/Feature/AdminAutorizacionTest.php` (niveles + token cruzado)

**Modificar:**
- `routes/api.php` — grupo `v1/cliente` + middleware `EnsureEsAdmin` en grupo admin + `can:<habilidad>` y `whereNumber('id')` en rutas admin
- `config/auth.php` — guard `cliente` + provider `cuentas_acceso`
- `app/Models/CuentaAcceso.php` — `HasApiTokens`
- `app/Providers/AppServiceProvider.php` — Gates + Policies

**No tocar:** migraciones existentes, seeder, modelos fuera de la lista, vistas (eliminadas), **controladores admin** (la autorización entra por middleware de ruta).

## 7. Respuestas JSON (convención uniforme)

- Éxito con recurso: `200`/`201` con el recurso (o `{token, usuario}` en auth).
- Éxito sin cuerpo: `204` en DELETE.
- Errores: `{"message": "..."}` y, en validación, `{"message": "...", "errors": {...}}` (ya es el formato del proyecto).
- Códigos usados: 200, 201, 204, 401, 403, 404, 405, 422 (todos dentro de la lista del enunciado; sin 409/400).

## 8. Pruebas (11 escenarios de la actividad)

| # | Escenario | Test |
|---|---|---|
| 1 | No autenticado → endpoint protegido | `AdminAuthTest` ✅ existente |
| 2 | Admin login + operación autorizada | `AdminAuthTest` ✅ existente |
| 3 | Admin excede permisos (nivel 1 crea/borra) | `AdminAutorizacionTest` nuevo (adaptar `AutorizacionNivelesTest` de `apiv2_experimental`: mismos casos pero `assertForbidden()` JSON en vez de redirects) |
| 4 | Cliente login + perfil | `ClienteAuthTest` nuevo |
| 5 | Cliente consulta sus registros | `ClienteRecursosTest` nuevo |
| 6 | Cliente accede a registro de otro | `ClienteRecursosTest` nuevo (404) |
| 7 | Cliente ejecuta operación admin | `ClienteAuthTest` nuevo (403 en `/admin`) |
| 8 | Datos inválidos | `ClienteAuthTest`/`ClienteRecursosTest` (422) |
| 9 | Recurso inexistente | `ClienteRecursosTest` (404) |
| 10 | Métodos HTTP | `ClienteAuthTest` (405 GET login) |
| 11 | JSON + códigos coherentes | asserts en todos |

Cuentas de prueba: nivel 1, 2, 3 (crear/seedear), cliente dueño, cliente ajeno.

## 9. Fuera de alcance (esta fase)

- Catálogo `CATALOGO_ENDPOINTS.md` completo y PDF (bloque documental aparte).
- Arreglar los 18 tests preexistentes con aserciones HTML (se hace después, aparte).
- Filtros/ordenamiento avanzados, 400/500 personalizados.
- Registro por `Google` (OAuth).
