# Contrato de Diseño de API — Actividad 4.1

**Proyecto:** Redistribución de Alimentos Excedentes  
**Versión del contrato:** 1.0  
**Fecha:** 1 de noviembre de 2026  
**Base implementada:** Actividad 4.2, commits `22a238d` a `9e1d337`  
**Responsable de completar este contrato:** integrante encargado de la Actividad 4.1

---

## Propósito

Este documento es la referencia oficial de arquitectura para completar la Actividad 4.1 sobre la API ya construida en la Actividad 4.2.

La Actividad 4.2 dejó funcionando los recursos **Categorías** y **Alimentos**, sin autenticación, con validación mediante `Validator::make()`, búsqueda con `findOrFail()` y `try-catch` sobre `ModelNotFoundException`, y respuestas JSON estandarizadas.

La Actividad 4.1 debe agregar **Sanctum**, separar el **área administrativa** del **área cliente** y aplicar autorización por identidad, sin rehacer los controladores ni cambiar el contrato JSON existente.

---

## 1. Arquitectura de endpoints y rutas

### 1.1 Convenio de nombres

| Regla | Aplicación en el proyecto |
| --- | --- |
| Prefijo de versión | `/api/v1/` |
| Prefijo de área | `/api/v1/admin/...` o `/api/v1/cliente/...` |
| Sustantivos en plural | `categorias`, `alimentos`, `solicitudes`, `donaciones` |
| Sin verbos en la URL | Se usa `POST /categorias`, no `POST /crearCategoria` |
| Identificador | `{id}` numérico, restringido con `->whereNumber('id')` |
| Método HTTP | Define la operación, nunca la URL |
| Nombres de ruta | `api.v1.<recurso>.<accion>` |
| Nesting | Solo cuando existe dependencia real: `/alimentos/{id}/imagen` |

Prefijo real: Laravel agrega `/api` desde `bootstrap/app.php`; el prefijo `v1` se declara en `routes/api.php`.

### 1.2 Recurso Categorías

**Controlador:** `App\Http\Controllers\Api\V1\CategoriaController`  
**Modelo:** `App\Models\CategoriaAlimento`  
**Tabla:** `categorias_alimento`  
**Clave primaria:** `id_categoria`

| Método | Endpoint 4.2 | Operación | Destino 4.1 |
| --- | --- | --- | --- |
| GET | `/api/v1/categorias` | Listar categorías | Público o admin |
| GET | `/api/v1/categorias/{id}` | Consultar categoría | Público o admin |
| POST | `/api/v1/categorias` | Crear categoría | Mover a `/api/v1/admin/categorias` |
| PUT | `/api/v1/categorias/{id}` | Reemplazar categoría | Mover a `/api/v1/admin/categorias/{id}` |
| PATCH | `/api/v1/categorias/{id}` | Actualizar parcialmente | Mover a `/api/v1/admin/categorias/{id}` |
| DELETE | `/api/v1/categorias/{id}` | Eliminar categoría | Mover a `/api/v1/admin/categorias/{id}` |

### 1.3 Recurso Alimentos

**Controlador:** `App\Http\Controllers\Api\V1\AlimentoController`  
**Modelo:** `App\Models\Alimento`  
**Tabla:** `alimentos`  
**Clave primaria:** `id_alimento`

| Método | Endpoint 4.2 | Operación | Destino 4.1 |
| --- | --- | --- | --- |
| GET | `/api/v1/alimentos` | Listar alimentos con categoría | Público o admin |
| GET | `/api/v1/alimentos/{id}` | Consultar alimento e imagen | Público o admin |
| POST | `/api/v1/alimentos` | Crear alimento | Mover a `/api/v1/admin/alimentos` |
| PUT | `/api/v1/alimentos/{id}` | Reemplazar alimento | Mover a `/api/v1/admin/alimentos/{id}` |
| PATCH | `/api/v1/alimentos/{id}` | Actualizar parcialmente | Mover a `/api/v1/admin/alimentos/{id}` |
| DELETE | `/api/v1/alimentos/{id}` | Eliminar alimento | Mover a `/api/v1/admin/alimentos/{id}` |
| POST | `/api/v1/alimentos/{id}/imagen` | Asociar o reemplazar imagen | Mover a `/api/v1/admin/alimentos/{id}/imagen` |
| DELETE | `/api/v1/alimentos/{id}/imagen` | Retirar imagen | Mover a `/api/v1/admin/alimentos/{id}/imagen` |

### 1.4 Regla de separación de áreas

- El catálogo de solo lectura puede quedar público: `GET /api/v1/categorias` y `GET /api/v1/alimentos`.
- Las operaciones de escritura deben vivir bajo `/api/v1/admin/...`.
- Los recursos privados del cliente deben vivir bajo `/api/v1/cliente/...`.
- El prefijo `admin` o `cliente` **no protege por sí solo**: la protección real la aplica el middleware.
- No deben quedar dos rutas públicas de escritura apuntando a los mismos controladores.

---

## 2. Estructura estándar de respuesta JSON

### 2.1 Plantilla obligatoria

Todas las respuestas con cuerpo, exitosas o de error, conservan exactamente estos cuatro campos:

```json
{
  "success": true,
  "message": "Mensaje descriptivo",
  "data": {},
  "errors": null
}
```

| Campo | Tipo | Regla |
| --- | --- | --- |
| `success` | boolean | `true` si la operación se completó; `false` si falló |
| `message` | string | Mensaje claro, sin datos internos ni trazas |
| `data` | array, object o null | Resultado de la operación; `null` cuando no hay datos |
| `errors` | array, object o null | Errores por campo en validación; `null` en los demás casos |

### 2.2 Códigos HTTP mapeados

| Código | Cuándo se usa en el código actual | Cuerpo |
| --- | --- | --- |
| 200 | Consulta, actualización o eliminación correcta | JSON estándar con `data` o `null` |
| 201 | Recurso creado con `POST` | JSON estándar con el registro creado |
| 404 | `findOrFail()` no encuentra el registro; ruta inexistente | `success: false`, `data: null`, `errors: null` |
| 422 | `Validator::make()` falla | `success: false`, `errors` con mensajes por campo |
| 500 | Excepción no controlada | `success: false`, mensaje genérico, sin detalles |
| 401 | Falta token o credenciales inválidas (4.1) | JSON estándar |
| 403 | Token válido sin permiso (4.1) | JSON estándar |
| 405 | Método HTTP no permitido | JSON estándar |
| 413 | Archivo o solicitud demasiado grande | JSON estándar |

El manejo global está en `bootstrap/app.php` y aplica a `/api/v1` y `/api/v1/*`.

### 2.3 Ejemplo de error 404 por `ModelNotFoundException`

```json
{
  "success": false,
  "message": "El alimento solicitado no existe",
  "data": null,
  "errors": null
}
```

### 2.4 Ejemplo de error 422 por validación

```json
{
  "success": false,
  "message": "Los datos enviados no son válidos",
  "data": null,
  "errors": {
    "nombre": [
      "El nombre del alimento es obligatorio."
    ],
    "imagen": [
      "La imagen debe ser de tipo jpg, jpeg, png o webp."
    ]
  }
}
```

### 2.5 Reglas que no deben romperse en la 4.1

- No devolver HTML ni redirecciones desde endpoints de API.
- No cambiar los nombres `success`, `message`, `data` ni `errors`.
- No exponer contraseñas, tokens ajenos, trazas, SQL ni rutas internas.
- Las validaciones de autenticación también deben usar `Validator::make()` y responder 422.
- Los errores 401 y 403 deben respetar la misma plantilla.

---

## 3. Estructura de controladores y almacenamiento

### 3.1 Ubicación de controladores

```text
app/Http/Controllers/
├── Api/
│   ├── ...controladores de la entrega anterior (no modificar)
│   └── V1/
│       ├── CategoriaController.php
│       └── AlimentoController.php
└── ...controladores web existentes
```

Convenciones:

| Elemento | Convención |
| --- | --- |
| Namespace | `App\Http\Controllers\Api\V1` |
| Nombre | `<Recurso>Controller` |
| Métodos CRUD | `index`, `show`, `store`, `update`, `destroy` |
| Métodos de imagen | `storeImagen`, `destroyImagen` |
| Validación | `Validator::make()` dentro del controlador |
| Búsqueda individual | `Modelo::findOrFail($id)` dentro de `try-catch` con `ModelNotFoundException`; el catch responde el 404 de las cuatro llaves |
| Respuesta | `response()->json([...], $codigo)` |
| Lógica | En el controlador; sin DTOs ni Form Requests |

La Actividad 4.1 puede agregar controladores de autenticación en:

```text
app/Http/Controllers/Api/V1/Auth/AdminAuthController.php
app/Http/Controllers/Api/V1/Auth/ClienteAuthController.php
```

### 3.2 Flujo de una petición

```text
routes/api.php
    -> middleware (cuando se agregue en 4.1)
    -> Controlador Api\V1
        -> Validator::make()
        -> findOrFail() / create() / update() / delete()
        -> response()->json()
```

### 3.3 Gestión de imágenes de Alimento

| Aspecto | Implementación actual |
| --- | --- |
| Disco | `public` |
| Carpeta | `storage/app/public/alimentos` |
| Nombre del archivo | Generado por Laravel con `store('alimentos', 'public')` |
| Columna en base de datos | `alimentos.imagen` guarda la ruta relativa, por ejemplo `alimentos/abc123.png` |
| URL pública | Se genera con el accesor `imagen_url` del modelo |
| Enlace público | `php artisan storage:link` crea `public/storage` |
| Validación | `image`, formatos `jpg`, `jpeg`, `png`, `webp`, máximo 2048 KB |
| Reemplazo | Se guarda la nueva imagen y se elimina la anterior |
| Eliminación | Se borra el archivo y se deja `imagen` en `null` |

En las respuestas, el recurso Alimento incluye ambos valores:

```json
{
  "imagen": "alimentos/abc123.png",
  "imagen_url": "http://127.0.0.1:8000/storage/alimentos/abc123.png"
}
```

`imagen` es la ruta interna que se guarda en la base de datos.  
`imagen_url` es la URL pública que consume el cliente.

El accesor está en `app/Models/Alimento.php`:

```php
protected $appends = ['imagen_url'];

public function getImagenUrlAttribute(): ?string
{
    return $this->imagen ? asset('storage/'.$this->imagen) : null;
}
```

---

## 4. Instrucciones para la Actividad 4.1

### 4.1 Estado de partida

Ya existen y no deben rehacerse:

- Rutas CRUD de Categorías y Alimentos en `routes/api.php`.
- Controladores `CategoriaController` y `AlimentoController` en `Api/V1`.
- Validaciones, `findOrFail()` con `try-catch`, respuestas JSON y manejo global de errores.
- Gestión de imágenes y accesor `imagen_url`.
- Modelos `CategoriaAlimento` y `Alimento`.

La Actividad 4.1 debe agregar:

1. Sanctum sobre los modelos que inician sesión.
2. Endpoints de login, logout y perfil para administrador y cliente.
3. Middleware `auth:sanctum` sobre las rutas protegidas.
4. Separación `/admin` y `/cliente`.
5. Autorización por rol, nivel o pertenencia del recurso.
6. Pruebas de 401, 403 y acceso a datos de otro cliente.

### 4.2 Integración de Sanctum

#### Paso 1 — Verificar dependencia y migración

El proyecto ya tiene `laravel/sanctum` en `composer.json` y la migración `create_personal_access_tokens_table`. Verificar:

```bash
composer show laravel/sanctum
php artisan migrate
```

Si la tabla no existe:

```bash
php artisan vendor:publish --tag=sanctum-migrations
php artisan migrate
```

#### Paso 2 — Agregar `HasApiTokens` a los modelos

**Administrador** (`app/Models/Administrador.php`):

```php
use Laravel\Sanctum\HasApiTokens;

class Administrador extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
}
```

**Usuario** (`app/Models/Usuario.php`):

```php
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Model
{
    use HasApiTokens, SoftDeletes;
}
```

El cliente no inicia sesión con `Usuario`, porque la contraseña vive en `CuentaAcceso`. El login valida `CuentaAcceso` y emite el token sobre el `Usuario` relacionado.

**CuentaAcceso** no necesita `HasApiTokens`; solo se usa para verificar credenciales.

#### Paso 3 — Registrar alias de middleware para abilities

En `bootstrap/app.php`, dentro de `withMiddleware`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->redirectGuestsTo(fn () => route('admin.login'));

    $middleware->alias([
        'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
        'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
    ]);
})
```

#### Paso 4 — Estandarizar el 401

En el manejador de excepciones de `bootstrap/app.php`, antes de calcular `$status`, agregar:

```php
if ($e instanceof \Illuminate\Auth\AuthenticationException) {
    return response()->json([
        'success' => false,
        'message' => 'Debe autenticarse para acceder a este recurso',
        'data' => null,
        'errors' => null,
    ], 401);
}
```

Sin este bloque, Laravel responde `{"message":"Unauthenticated."}` y se rompe el contrato JSON.

#### Paso 5 — Configuración de guards y cookies

- `auth:sanctum` usa el guard `sanctum` que registra el paquete.
- No es necesario convertir el guard `admin` de sesión en guard de tokens.
- `config/sanctum.php` con `'guard' => ['web']` se mantiene si se usan tokens Bearer.
- Si se optara por cookies de SPA, habría que configurar `SANCTUM_STATEFUL_DOMAINS` y el guard `admin`; para esta actividad se recomienda **token personal Bearer**, que es más simple de probar en Postman.

#### Paso 6 — Emitir tokens con abilities

```php
$token = $administrador->createToken('admin', ['admin'])->plainTextToken;
```

```php
$token = $usuario->createToken('cliente', ['cliente'])->plainTextToken;
```

La habilidad permite separar áreas:

- Token de administrador: `['admin']`.
- Token de cliente: `['cliente']`.

### 4.3 Separación de rutas: público, admin y cliente

Reorganizar el grupo `v1` de `routes/api.php` con esta forma:

```php
Route::prefix('v1')->group(function () {

    Route::post('/admin/login', [AdminAuthController::class, 'login']);
    Route::post('/cliente/login', [ClienteAuthController::class, 'login']);
    Route::post('/cliente/registro', [ClienteAuthController::class, 'registro']);

    Route::get('/categorias', [CategoriaV1Controller::class, 'index']);
    Route::get('/categorias/{id}', [CategoriaV1Controller::class, 'show'])->whereNumber('id');
    Route::get('/alimentos', [AlimentoV1Controller::class, 'index']);
    Route::get('/alimentos/{id}', [AlimentoV1Controller::class, 'show'])->whereNumber('id');

    Route::prefix('admin')->middleware(['auth:sanctum', 'ability:admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/perfil', [AdminAuthController::class, 'perfil']);

        Route::post('/categorias', [CategoriaV1Controller::class, 'store']);
        Route::match(['put', 'patch'], '/categorias/{id}', [CategoriaV1Controller::class, 'update'])->whereNumber('id');
        Route::delete('/categorias/{id}', [CategoriaV1Controller::class, 'destroy'])->whereNumber('id');

        Route::post('/alimentos', [AlimentoV1Controller::class, 'store']);
        Route::match(['put', 'patch'], '/alimentos/{id}', [AlimentoV1Controller::class, 'update'])->whereNumber('id');
        Route::delete('/alimentos/{id}', [AlimentoV1Controller::class, 'destroy'])->whereNumber('id');
        Route::post('/alimentos/{id}/imagen', [AlimentoV1Controller::class, 'storeImagen'])->whereNumber('id');
        Route::delete('/alimentos/{id}/imagen', [AlimentoV1Controller::class, 'destroyImagen'])->whereNumber('id');
    });

    Route::prefix('cliente')->middleware(['auth:sanctum', 'ability:cliente'])->group(function () {
        Route::post('/logout', [ClienteAuthController::class, 'logout']);
        Route::get('/perfil', [ClienteAuthController::class, 'perfil']);

        Route::get('/solicitudes', [SolicitudClienteController::class, 'index']);
        Route::post('/solicitudes', [SolicitudClienteController::class, 'store']);
        Route::get('/solicitudes/{id}', [SolicitudClienteController::class, 'show'])->whereNumber('id');
    });
});
```

Si se sigue literalmente la indicación de «envolver las rutas de `routes/api.php` con `auth:sanctum`», la forma mínima sería:

```php
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // todas las rutas de la v1
});
```

Esa forma funciona, pero protege también el catálogo público: un cliente sin token no podría consultar categorías ni alimentos. Por eso este contrato recomienda la separación por subgrupos que se muestra arriba: `auth:sanctum` se aplica a `admin` y `cliente`, no al catálogo de solo lectura.

Reglas de esta reorganización:

1. Las rutas de escritura de la Actividad 4.2 que estaban en `/api/v1/categorias` y `/api/v1/alimentos` **se eliminan del grupo público** y se vuelven a declarar bajo `/admin`.
2. Los controladores no se duplican: las mismas clases atienden las rutas administrativas.
3. `POST /admin/login` y `POST /cliente/login` quedan fuera de `auth:sanctum`.
4. `logout` y `perfil` quedan dentro del grupo protegido.
5. Toda ruta nueva de escritura debe declararse dentro del grupo correcto.
6. Las rutas de `SolicitudClienteController` son un ejemplo de recurso futuro del cliente; si ese controlador no existe todavía, se omiten hasta implementarlo.

### 4.4 Endpoints de autenticación que debe construir el compañero

El enunciado de la actividad pide `POST /login`, `POST /logout` y `GET /perfil`. Como la API separa áreas, esos tres endpoints se materializan dos veces:

| Enunciado | Área administrativa | Área cliente |
| --- | --- | --- |
| `POST /login` | `POST /api/v1/admin/login` | `POST /api/v1/cliente/login` |
| `POST /logout` | `POST /api/v1/admin/logout` | `POST /api/v1/cliente/logout` |
| `GET /perfil` | `GET /api/v1/admin/perfil` | `GET /api/v1/cliente/perfil` |

El registro de cliente (`POST /api/v1/cliente/registro`) es opcional; si la actividad no lo exige, se puede omitir.

#### Área administrativa

| Método | Endpoint | Acceso | Controlador sugerido |
| --- | --- | --- | --- |
| POST | `/api/v1/admin/login` | Público | `Api\V1\Auth\AdminAuthController@login` |
| POST | `/api/v1/admin/logout` | `auth:sanctum`, `ability:admin` | `Api\V1\Auth\AdminAuthController@logout` |
| GET | `/api/v1/admin/perfil` | `auth:sanctum`, `ability:admin` | `Api\V1\Auth\AdminAuthController@perfil` |

**Login de administrador**

Entrada:

```json
{
  "username": "admin",
  "password": "contraseña"
}
```

Validación:

```php
$validator = Validator::make($request->all(), [
    'username' => 'required|string|max:30',
    'password' => 'required|string',
]);
```

Lógica:

1. Buscar `Administrador` por `username`.
2. Verificar la contraseña con `Hash::check()`.
3. Rechazar con 401 si las credenciales no coinciden.
4. Rechazar con 403 si `activo` es falso.
5. Crear el token con `createToken('admin', ['admin'])`.
6. Responder 200 con el token y los datos públicos del administrador.

Respuesta:

```json
{
  "success": true,
  "message": "Inicio de sesión correcto",
  "data": {
    "token": "1|token-generado",
    "token_type": "Bearer",
    "administrador": {
      "id": 1,
      "nombre_completo": "Administrador",
      "username": "admin",
      "nivel": 3
    }
  },
  "errors": null
}
```

#### Área cliente

| Método | Endpoint | Acceso | Controlador sugerido |
| --- | --- | --- | --- |
| POST | `/api/v1/cliente/login` | Público | `Api\V1\Auth\ClienteAuthController@login` |
| POST | `/api/v1/cliente/registro` | Público, opcional | `Api\V1\Auth\ClienteAuthController@registro` |
| POST | `/api/v1/cliente/logout` | `auth:sanctum`, `ability:cliente` | `Api\V1\Auth\ClienteAuthController@logout` |
| GET | `/api/v1/cliente/perfil` | `auth:sanctum`, `ability:cliente` | `Api\V1\Auth\ClienteAuthController@perfil` |

**Login de cliente**

Entrada:

```json
{
  "identificador": "cliente@correo.com",
  "password": "contraseña"
}
```

Lógica:

1. Buscar `CuentaAcceso` por `identificador_externo`; si hay varios proveedores, filtrar por `proveedor`.
2. Verificar `contrasena_hash` con `Hash::check()`.
3. Rechazar con 401 si no coincide.
4. Verificar que `cuenta->usuario->estado` sea verdadero; si no, 403.
5. Actualizar `fecha_ultimo_acceso`.
6. Crear el token sobre el usuario: `$cuenta->usuario->createToken('cliente', ['cliente'])`.
7. Responder 200 con el token y los datos del usuario.

Respuesta:

```json
{
  "success": true,
  "message": "Inicio de sesión correcto",
  "data": {
    "token": "2|token-generado",
    "token_type": "Bearer",
    "usuario": {
      "id": 1,
      "nombre": "Cliente",
      "apellido": "Ejemplo",
      "correo": "cliente@correo.com"
    }
  },
  "errors": null
}
```

#### Logout

```php
$request->user()->currentAccessToken()->delete();

return response()->json([
    'success' => true,
    'message' => 'Sesión cerrada correctamente',
    'data' => null,
    'errors' => null,
], 200);
```

#### Perfil

```php
return response()->json([
    'success' => true,
    'message' => 'Perfil consultado correctamente',
    'data' => $request->user(),
    'errors' => null,
], 200);
```

El token debe enviarse en el encabezado:

```http
Authorization: Bearer 1|token-generado
Accept: application/json
```

### 4.5 Autorización y separación real de áreas

| Regla | Implementación |
| --- | --- |
| Catálogo público | Solo `GET` de categorías y alimentos |
| CRUD de catálogo | `auth:sanctum` + `ability:admin` |
| Recursos del cliente | `auth:sanctum` + `ability:cliente` |
| Cliente no accede a admin | La ability `admin` no existe en su token |
| Admin no accede a recursos de cliente | La ability `cliente` no existe en su token |
| Cliente solo ve sus registros | Filtrar por `$request->user()->id` en el controlador o Policy |
| Nivel administrativo | Aplicar Gates o Policies según `administradores.nivel` |

Niveles administrativos ya previstos en la base de datos:

| Nivel | Permisos propuestos |
| --- | --- |
| 1 | Consulta |
| 2 | Consulta, creación y edición |
| 3 | Todas las operaciones, incluido eliminar |

La autorización por nivel debe validarse en el servidor. No se debe confiar en un `nivel` o `id` enviado por el frontend.

### 4.6 Pruebas obligatorias de la Actividad 4.1

| Escenario | Resultado esperado |
| --- | --- |
| Acceso a `/api/v1/admin/categorias` sin token | 401 JSON estándar |
| Login de administrador correcto | 200 con token |
| Login con contraseña incorrecta | 401 JSON estándar |
| Administrador consulta su perfil | 200 con sus datos |
| Administrador crea una categoría | 201 |
| Cliente consulta su perfil | 200 |
| Cliente consulta sus solicitudes | 200 solo con sus registros |
| Cliente intenta consultar una solicitud ajena | 403 o 404, sin exponer datos |
| Cliente intenta crear una categoría en `/admin` | 403 |
| Token de administrador en ruta de cliente | 403 |
| Logout con token válido | 200 y token revocado |
| Reutilizar el token después del logout | 401 |
| Validación incompleta en login | 422 con `errors` |
| Recurso inexistente con token válido | 404 JSON estándar |

Todas las pruebas deben ejecutarse sin sesiones web y enviando `Accept: application/json`.

---

## 5. Checklist de entrega de la Actividad 4.1

- [ ] `HasApiTokens` agregado a `Administrador` y `Usuario`.
- [ ] Alias `ability` y `abilities` registrados en `bootstrap/app.php`.
- [ ] `AuthenticationException` devuelve 401 con la plantilla estándar.
- [ ] `POST /api/v1/admin/login` funcionando con token y ability `admin`.
- [ ] `POST /api/v1/cliente/login` funcionando con token y ability `cliente`.
- [ ] `POST /api/v1/admin/logout` y `POST /api/v1/cliente/logout` revocan el token actual.
- [ ] `GET /api/v1/admin/perfil` y `GET /api/v1/cliente/perfil` devuelven la identidad autenticada.
- [ ] Rutas de escritura movidas a `/api/v1/admin/...` y protegidas.
- [ ] Rutas de cliente bajo `/api/v1/cliente/...` y protegidas.
- [ ] Catálogo público limitado a lectura.
- [ ] Permisos por nivel administrativo aplicados en el servidor.
- [ ] Cliente solo accede a sus propios registros.
- [ ] Pruebas 401, 403, 404, 422 y 200 documentadas en Postman.
- [ ] Ningún token, contraseña o dato sensible en el repositorio.

---

## 6. Archivos que el compañero debe tocar

| Archivo | Acción en la 4.1 |
| --- | --- |
| `routes/api.php` | Reorganizar grupos público, admin y cliente; agregar login/logout/perfil |
| `bootstrap/app.php` | Alias de abilities y respuesta 401 estándar |
| `app/Models/Administrador.php` | Agregar `HasApiTokens` |
| `app/Models/Usuario.php` | Agregar `HasApiTokens` |
| `app/Http/Controllers/Api/V1/Auth/AdminAuthController.php` | Crear |
| `app/Http/Controllers/Api/V1/Auth/ClienteAuthController.php` | Crear |
| `app/Http/Controllers/Api/V1/CategoriaController.php` | Reutilizar; no duplicar |
| `app/Http/Controllers/Api/V1/AlimentoController.php` | Reutilizar; no duplicar |
| `config/auth.php` | Sin cambios para tokens Bearer; solo revisar el guard de sesión existente |
| `config/sanctum.php` | Sin cambios si se usan tokens Bearer |
| `postman/collections/` | Agregar requests de login, logout, perfil y pruebas 401/403 |

---

## 7. Referencias internas

- Guía de implementación de la 4.2: `GUIA_CAMBIOS_4.2.md`
- Rutas: `routes/api.php`
- Manejo de excepciones: `bootstrap/app.php`
- Controladores: `app/Http/Controllers/Api/V1/`
- Modelos: `app/Models/`
- Colección de pruebas: `postman/collections/actividad-4.2-api-v1/`
- Configuración de autenticación: `config/auth.php`
- Configuración de Sanctum: `config/sanctum.php`
