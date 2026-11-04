# Guía de cambios 4.2 — replicación exacta

Esta guía permite reconstruir la Actividad 4.2 desde el estado anterior del repositorio, indicando **en qué archivo, en qué parte y qué se agrega, modifica o elimina**.

No es un resumen: es el procedimiento de replicación con el código completo de los archivos nuevos y los bloques exactos que se insertan en los archivos existentes.

---

## 0. Datos de la implementación

| Dato | Valor |
| --- | --- |
| Estado inicial | Commit `9e631b6` (`creacion de crud administrador`) |
| Rama de trabajo | `apiv2` |
| Framework | Laravel 12.67 |
| Base de datos | MySQL, base `redistribucion_alimentos` |
| Prefijo de la API | `/api/v1` |
| Recursos | Categorías y Alimentos |
| Autenticación | Ninguna en esta actividad |
| Resultado de pruebas | 28 requests, 28 test-scripts y 224 aserciones aprobadas |

### 0.1 Cómo leer los números de línea

- **Línea del archivo original:** se refiere al archivo antes de aplicar la 4.2.
- **Línea del archivo final:** se refiere al archivo tal como quedó en el commit `9e1d337`.
- Si se aplican los cambios en el orden de esta guía, las líneas finales coinciden.
- Si se aplican en otro orden, usar siempre el texto de referencia (`antes` / `después`) y no solo el número.

### 0.2 Orden obligatorio de aplicación

1. `bootstrap/app.php`
2. `app/Models/Alimento.php`
3. `app/Models/CategoriaAlimento.php`
4. Crear `CategoriaController.php`
5. Crear `AlimentoController.php`
6. `routes/api.php`
7. Ejecutar `php artisan storage:link`
8. Crear la colección Postman
9. Verificar con `route:list` y con Postman

---

## 1. Mapa exacto de cambios

| # | Acción | Archivo | Ubicación exacta | Resultado |
| --- | --- | --- | --- | --- |
| 1 | MODIFICAR | `bootstrap/app.php` | Insertar imports después de la línea 5 original; reemplazar `//` de la línea 15 original | Archivo final de 64 líneas |
| 2 | MODIFICAR | `app/Models/Alimento.php` | Insertar bloque después de la línea 26 original; eliminar líneas en blanco antes de la llave final | Archivo final de 59 líneas |
| 3 | MODIFICAR | `app/Models/CategoriaAlimento.php` | Insertar bloque después de la línea 23 original | Archivo final de 37 líneas |
| 4 | CREAR | `app/Http/Controllers/Api/V1/CategoriaController.php` | Carpeta nueva `Api/V1` | Archivo de 160 líneas |
| 5 | CREAR | `app/Http/Controllers/Api/V1/AlimentoController.php` | Carpeta nueva `Api/V1` | Archivo de 287 líneas |
| 6 | MODIFICAR | `routes/api.php` | Insertar 2 imports después de la línea 5 original; insertar bloque después de la línea 16 original | Archivo final de 357 líneas |
| 7 | EJECUTAR | `public/storage` | Raíz del proyecto | Enlace simbólico público |
| 8 | CREAR | `postman/collections/actividad-4.2-api-v1/` | Carpeta `postman/collections` | 28 requests y 3 archivos de prueba |
| 9 | CREAR | `GUIA_CAMBIOS_4.2.md` y `CONTRATO_DISEÑO_API_4.1.md` | Raíz del proyecto | Documentación |

### 1.1 Lo que se elimina

No se elimina ningún archivo, ruta, controlador, modelo, migración, tabla ni columna.

Lo único que se elimina son **líneas en blanco con espacios** al final de `app/Models/Alimento.php` (detalle en la sección 3.4).

### 1.2 Lo que no se debe tocar

- Los controladores existentes en `app/Http/Controllers/Api/` (sin `V1`).
- Las rutas existentes de `routes/api.php` que están después del bloque nuevo.
- Las migraciones y la base de datos.
- `routes/web.php`.
- La configuración de autenticación `config/auth.php`.

---

## 2. Preparación del entorno

Desde la raíz del proyecto:

```bash
git checkout apiv2
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

Si el archivo `.env` ya existe, no se debe sobrescribir.

Verificar que el servidor responda en:

```text
http://127.0.0.1:8000
```

Si se cambió el archivo `.env` o las rutas no aparecen:

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

---

## 3. Cambio 1 — MODIFICAR `bootstrap/app.php`

**Ruta exacta:** `bootstrap/app.php`  
**Tipo:** modificación  
**Ubicación:** imports al inicio y bloque `withExceptions` al final.

### 3.1 Estado original del archivo (16 líneas)

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

### 3.2 Qué se agrega y en qué línea

**A. Imports:** insertar después de la línea 5 original (`use Illuminate\Foundation\Configuration\Middleware;`) y antes de la línea 7 (`return Application...`).

Se agregan exactamente estas tres líneas:

```php
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
```

En el archivo final quedan en las líneas **6, 7 y 8**.

No se agrega `use Throwable;` porque `Throwable` está en el espacio global de PHP y se resuelve sin importación.

**B. Bloque de excepciones:** reemplazar la línea 15 original, que contiene únicamente `//`, por el bloque completo que se muestra en 3.3.

En el archivo final, el bloque ocupa las líneas **20 a 64**.

### 3.3 Archivo final completo (64 líneas)

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->is('api/v1', 'api/v1/*')) {
                return null;
            }

            if ($e instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Los datos enviados no son válidos',
                    'data' => null,
                    'errors' => $e->errors(),
                ], 422);
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            if ($status >= 500) {
                report($e);
            }

            $mensajes = [
                400 => 'La solicitud no es válida',
                401 => 'Debe autenticarse para acceder a este recurso',
                403 => 'No tiene autorización para realizar esta operación',
                404 => 'El recurso solicitado no existe',
                405 => 'El método HTTP no está permitido para este endpoint',
                413 => 'El archivo o la solicitud supera el tamaño permitido',
                415 => 'El tipo de contenido enviado no está permitido',
                422 => 'Los datos enviados no son válidos',
                429 => 'Se realizaron demasiadas solicitudes',
            ];

            return response()->json([
                'success' => false,
                'message' => $mensajes[$status] ?? 'Ocurrió un error interno al procesar la solicitud',
                'data' => null,
                'errors' => null,
            ], $status);
        });
    })->create();
```

### 3.4 Qué hace cada parte

| Parte | Función |
| --- | --- |
| `shouldRenderJsonWhen` | Fuerza respuestas JSON en `api/*` y cuando el cliente envía `Accept: application/json` |
| `if (! $request->is('api/v1', 'api/v1/*'))` | El manejador solo actúa sobre la API v1; el resto de la aplicación conserva su comportamiento |
| `ValidationException` | Devuelve 422 con `errors` por campo, sin cambiar el resto del contrato |
| `HttpExceptionInterface` | Conserva el código real de errores 404, 405, 413, 415, 429, etc. |
| `$status >= 500` + `report()` | Registra el error en los logs sin exponerlo al cliente |
| Arreglo `$mensajes` | Traduce códigos HTTP a mensajes claros en español |
| `data => null` y `errors => null` | Mantiene la estructura única también en errores |

### 3.5 Advertencia para la Actividad 4.1

Este bloque **no** convierte `AuthenticationException` en 401 con la estructura estándar. Laravel la respondería como `{"message": "Unauthenticated."}`.

Cuando se agregue Sanctum, el compañero debe insertar este bloque **antes** de calcular `$status`:

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

Ese paso pertenece a la Actividad 4.1, no a la 4.2.

### 3.6 Verificación

```bash
php -l bootstrap/app.php
php artisan route:clear
```

Debe responder `No syntax errors detected`.

Probar un 404 de ruta:

```http
GET http://127.0.0.1:8000/api/v1/ruta-inexistente
Accept: application/json
```

Debe devolver JSON con `success: false`, `message`, `data` y `errors`, no HTML.

---

## 4. Cambio 2 — MODIFICAR `app/Models/Alimento.php`

**Ruta exacta:** `app/Models/Alimento.php`  
**Tipo:** modificación  
**Ubicación:** después del arreglo `$fillable` y antes de la relación `categoria()`.

### 4.1 Estado original (fragmento relevante)

```php
    protected $fillable = [
        'id_categoria',
        'nombre',
        'descripcion',
        'estado',
        'imagen',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(
            CategoriaAlimento::class,
            'id_categoria',
            'id_categoria'
        );
    }
```

### 4.2 Qué se agrega y en qué línea

Insertar el siguiente bloque **después de la línea 26 original** (`    ];` que cierra `$fillable`) y **antes de la línea 28 original** (`public function categoria`).

```php
    protected $appends = [
        'imagen_url',
    ];

    protected $casts = [
        'id_categoria' => 'integer',
        'estado' => 'boolean',
    ];

    public function getImagenUrlAttribute(): ?string
    {
        return $this->imagen ? asset('storage/'.$this->imagen) : null;
    }
```

En el archivo final, el bloque ocupa las líneas **28 a 40**.

### 4.3 Archivo final completo (59 líneas)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alimento extends Model
{
    use SoftDeletes;

    protected $table = 'alimentos';

    protected $primaryKey = 'id_alimento';

    public $timestamps = false;

    protected $fillable = [
        'id_categoria',
        'nombre',
        'descripcion',
        'estado',
        'imagen',
    ];

    protected $appends = [
        'imagen_url',
    ];

    protected $casts = [
        'id_categoria' => 'integer',
        'estado' => 'boolean',
    ];

    public function getImagenUrlAttribute(): ?string
    {
        return $this->imagen ? asset('storage/'.$this->imagen) : null;
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(
            CategoriaAlimento::class,
            'id_categoria',
            'id_categoria'
        );
    }

    public function detallesDonacion(): HasMany
    {
        return $this->hasMany(
            DetalleDonacion::class,
            'id_alimento',
            'id_alimento'
        );
    }
}
```

### 4.4 Qué se elimina exactamente

Al final del archivo original existían líneas en blanco con espacios antes de la llave `}` de cierre:

```php
    }

   
    
}
```

Se eliminan esas líneas vacías y se deja:

```php
    }
}
```

No se elimina ninguna propiedad, relación ni método.

### 4.5 Por qué se agrega cada cosa

| Elemento | Motivo |
| --- | --- |
| `$appends = ['imagen_url']` | Hace que `imagen_url` aparezca automáticamente en todas las respuestas JSON del modelo, sin agregarlo manualmente en cada controlador |
| `getImagenUrlAttribute()` | Convierte la ruta relativa `imagen` en una URL pública absoluta |
| `asset('storage/'.$this->imagen)` | Genera la URL pública completa a partir de la app y el enlace `public/storage`; funciona en local y en producción sin fijar manualmente el host |
| `?string` | Devuelve `null` cuando el alimento no tiene imagen |
| Cast `id_categoria => integer` | Evita que el id de categoría se devuelva como texto |
| Cast `estado => boolean` | Devuelve `true` o `false`, no `1` o `0` |

### 4.6 Verificación

```bash
php -l app/Models/Alimento.php
```

Probar después con un alimento que tenga imagen:

```http
GET http://127.0.0.1:8000/api/v1/alimentos/{id}
Accept: application/json
```

Debe incluir:

```json
{
  "imagen": "alimentos/archivo.png",
  "imagen_url": "http://127.0.0.1:8000/storage/alimentos/archivo.png"
}
```

---

## 5. Cambio 3 — MODIFICAR `app/Models/CategoriaAlimento.php`

**Ruta exacta:** `app/Models/CategoriaAlimento.php`  
**Tipo:** modificación  
**Ubicación:** después del arreglo `$fillable` y antes de la relación `alimentos()`.

### 5.1 Qué se agrega y en qué línea

Insertar **después de la línea 23 original** (`    ];` que cierra `$fillable`) y **antes de la línea 25 original** (`public function alimentos`).

```php
    protected $casts = [
        'estado' => 'boolean',
    ];
```

En el archivo final, el bloque ocupa las líneas **25 a 27**.

### 5.2 Archivo final completo (37 líneas)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaAlimento extends Model
{
    use SoftDeletes;

    protected $table = 'categorias_alimento';

    protected $primaryKey = 'id_categoria';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function alimentos(): HasMany
    {
        return $this->hasMany(
            Alimento::class,
            'id_categoria',
            'id_categoria'
        );
    }
}
```

### 5.3 Por qué

Sin el cast, MySQL devuelve `estado` como `1` o `0` y el JSON no es consistente con el tipo booleano que exige el contrato. Con el cast, todas las respuestas devuelven `true` o `false`.

### 5.4 Qué se elimina

Nada. Este archivo solo recibe la inserción del bloque `$casts`.

### 5.5 Verificación

```bash
php -l app/Models/CategoriaAlimento.php
```

Consultar una categoría y comprobar que `estado` sea booleano:

```http
GET http://127.0.0.1:8000/api/v1/categorias/1
```

---

## 6. Cambio 4 — CREAR `CategoriaController.php`

**Ruta exacta:** `app/Http/Controllers/Api/V1/CategoriaController.php`  
**Tipo:** archivo nuevo  
**Carpeta:** `app/Http/Controllers/Api/V1/` (no existía; crearla)  
**Namespace:** `App\Http\Controllers\Api\V1`  
**Total:** 159 líneas.

El código completo está en el **Anexo A** de esta guía. Copiarlo tal cual.

### 6.1 Mapa de métodos y líneas finales

| Método | Líneas | Verbo y ruta | Respuesta |
| --- | --- | --- | --- |
| Cabecera, imports y clase | 1-12 | — | — |
| `index(Request $request)` | 13-31 | `GET /categorias` | 200 con colección completa |
| `show($id)` | 33-52 | `GET /categorias/{id}` | 200 o 404 |
| `store(Request $request)` | 54-79 | `POST /categorias` | 201 o 422 |
| `update(Request $request, $id)` | 81-121 | `PUT` o `PATCH /categorias/{id}` | 200, 404 o 422 |
| `destroy($id)` | 123-144 | `DELETE /categorias/{id}` | 200 o 404 |
| `mensajes()` | 146-157 | — | Arreglo de mensajes |
| Cierre de clase | 159 | — | — |

### 6.2 `index` — líneas 13 a 31

Qué hace:

1. Aplica filtro `buscar` sobre `nombre` con `LIKE`.
2. Aplica filtro `estado` cuando viene en la query.
3. Ordena por `id_categoria`.
4. Devuelve la colección completa con `get()`, sin paginar, dentro de `data`.

La respuesta 200 tiene esta forma:

```json
{
  "success": true,
  "message": "Categorías consultadas correctamente",
  "data": [
    {
      "id_categoria": 1,
      "nombre": "Frutas",
      "descripcion": "frutas de buena calidad",
      "estado": true,
      "deleted_at": null
    }
  ],
  "errors": null
}
```

### 6.3 `show` — líneas 33 a 52

Regla obligatoria de la actividad:

```php
try {
    $categoria = CategoriaAlimento::findOrFail($id);
} catch (ModelNotFoundException $e) {
    return response()->json([
        'success' => false,
        'message' => 'La categoría solicitada no existe',
        'data' => null,
        'errors' => null,
    ], 404);
}
```

Se usa `findOrFail()` dentro de `try-catch` con `Illuminate\Database\Eloquent\ModelNotFoundException`; el JSON 404 lo emite el controlador, nunca una página de error de Laravel.

### 6.4 `store` — líneas 54 a 79

Validación con `Validator::make()`:

| Campo | Regla |
| --- | --- |
| `nombre` | `required|string|max:80` |
| `descripcion` | `required|string|max:200` |
| `estado` | `required|boolean` |

Si falla, responde 422 con `errors`. Si pasa, usa `$validator->validated()` para crear y responde 201.

### 6.5 `update` — líneas 81 a 121

1. Busca con `findOrFail($id)` dentro de `try-catch` con `ModelNotFoundException`; si no existe, responde 404 con las cuatro llaves.
2. Detecta el método con `$request->isMethod('patch')`.
3. En `PUT` exige todos los campos.
4. En `PATCH` usa `sometimes|required` para cada campo.
5. Valida con `Validator::make()`.
6. Actualiza con `$validator->validated()`.
7. Responde 200 con `$categoria->fresh()`.

Reglas exactas:

| Campo | PUT | PATCH |
| --- | --- | --- |
| `nombre` | `required|string|max:80` | `sometimes|required|string|max:80` |
| `descripcion` | `required|string|max:200` | `sometimes|required|string|max:200` |
| `estado` | `required|boolean` | `sometimes|required|boolean` |

### 6.6 `destroy` — líneas 123 a 144

1. Busca con `findOrFail($id)` dentro de `try-catch` con `ModelNotFoundException`; si no existe, responde 404 con las cuatro llaves.
2. Ejecuta `$categoria->delete()`.
3. Responde 200 con `data: null`.

El modelo usa `SoftDeletes`, por lo que el registro queda con `deleted_at` y no se borra físicamente.

### 6.7 `mensajes` — líneas 146 a 157

Devuelve mensajes en español para cada regla. Se pasa como tercer argumento a `Validator::make()`.

### 6.8 Verificación

```bash
php -l app/Http/Controllers/Api/V1/CategoriaController.php
```

---

## 7. Cambio 5 — CREAR `AlimentoController.php`

**Ruta exacta:** `app/Http/Controllers/Api/V1/AlimentoController.php`  
**Tipo:** archivo nuevo  
**Namespace:** `App\Http\Controllers\Api\V1`  
**Total:** 286 líneas.

El código completo está en el **Anexo B**. Copiarlo tal cual.

### 7.1 Mapa de métodos y líneas finales

| Método | Líneas | Verbo y ruta | Respuesta |
| --- | --- | --- | --- |
| Cabecera, imports y clase | 1-14 | — | — |
| `index(Request $request)` | 15-36 | `GET /alimentos` | 200 con colección completa |
| `show($id)` | 38-57 | `GET /alimentos/{id}` | 200 o 404 |
| `store(Request $request)` | 59-98 | `POST /alimentos` | 201 o 422 |
| `update(Request $request, $id)` | 100-160 | `PUT` o `PATCH /alimentos/{id}` | 200, 404 o 422 |
| `destroy($id)` | 162-187 | `DELETE /alimentos/{id}` | 200 o 404 |
| `storeImagen(Request $request, $id)` | 189-230 | `POST /alimentos/{id}/imagen` | 200, 404 o 422 |
| `destroyImagen($id)` | 232-263 | `DELETE /alimentos/{id}/imagen` | 200 o 404 |
| `mensajes()` | 265-284 | — | Arreglo de mensajes |
| Cierre de clase | 286 | — | — |

### 7.2 `index` — líneas 15 a 36

1. Carga la relación `categoria` con `with('categoria')`.
2. Filtra por `buscar` sobre `nombre`.
3. Filtra por `id_categoria`.
4. Filtra por `estado`.
5. Ordena por `id_alimento`.
6. Devuelve la colección completa con `get()`, sin paginar, dentro de `data`.

Cada alimento incluye su categoría y, por el accesor del modelo, también `imagen_url`.

### 7.3 `show` — líneas 38 a 57

```php
try {
    $alimento = Alimento::with('categoria')->findOrFail($id);
} catch (ModelNotFoundException $e) {
    return response()->json([
        'success' => false,
        'message' => 'El alimento solicitado no existe',
        'data' => null,
        'errors' => null,
    ], 404);
}
```

### 7.4 `store` — líneas 59 a 98

Validación:

| Campo | Regla |
| --- | --- |
| `id_categoria` | `required`, `integer`, `Rule::exists('categorias_alimento', 'id_categoria')->whereNull('deleted_at')` |
| `nombre` | `required|string|max:80` |
| `descripcion` | `required|string|max:200` |
| `estado` | `required|boolean` |
| `imagen` | `nullable|image|mimes:jpg,jpeg,png,webp|max:2048` |

Flujo:

1. Valida.
2. Copia los datos validados a `$datos`.
3. Quita `imagen` de `$datos` con `unset($datos['imagen'])`, porque no es una columna de texto.
4. Crea el alimento.
5. Si `hasFile('imagen')`, guarda el archivo con `store('alimentos', 'public')`.
6. Actualiza la columna `imagen` con la ruta relativa.
7. Responde 201 con `$alimento->load('categoria')`.

La regla `exists` con `whereNull('deleted_at')` impide asociar una categoría eliminada lógicamente.

### 7.5 `update` — líneas 100 a 160

1. Busca con `findOrFail($id)` dentro de `try-catch` con `ModelNotFoundException`; si no existe, responde 404 con las cuatro llaves.
2. Detecta `PATCH` con `$request->isMethod('patch')`.
3. En `PUT` exige `id_categoria`, `nombre`, `descripcion` y `estado`.
4. En `PATCH` usa `sometimes` en `id_categoria` y `sometimes|required` en los campos de texto.
5. Valida con `Validator::make()`.
6. Quita `imagen` de `$datos` y actualiza el resto.
7. Si llega una imagen nueva:
   - guarda la ruta anterior en `$imagenAnterior`;
   - guarda la nueva con `store('alimentos', 'public')`;
   - actualiza la columna;
   - elimina el archivo anterior con `Storage::disk('public')->delete($imagenAnterior)`.
8. Responde 200 con `$alimento->fresh()->load('categoria')`.

### 7.6 `destroy` — líneas 162 a 187

1. Busca con `findOrFail($id)` dentro de `try-catch` con `ModelNotFoundException`; si no existe, responde 404 con las cuatro llaves.
2. Si tiene imagen, elimina el archivo con `Storage::disk('public')->delete(...)`.
3. Ejecuta `$alimento->delete()` (borrado lógico).
4. Responde 200 con `data: null`.

### 7.7 `storeImagen` — líneas 189 a 230

Este endpoint existe porque **PHP no procesa archivos en solicitudes PUT**. Para reemplazar una imagen se usa siempre `POST /alimentos/{id}/imagen`.

Flujo:

1. Busca el alimento y responde 404 si no existe.
2. Valida `imagen` como `required|image|mimes:jpg,jpeg,png,webp|max:2048`.
3. Guarda la ruta anterior.
4. Guarda la nueva imagen en `alimentos/` del disco `public`.
5. Actualiza la columna `imagen`.
6. Elimina el archivo anterior si existía.
7. Responde 200 con `$alimento->fresh()`.

### 7.8 `destroyImagen` — líneas 232 a 263

1. Busca el alimento y responde 404 si no existe.
2. Si no tiene imagen, responde 404 con el mensaje `El alimento no tiene una imagen asociada`.
3. Elimina el archivo del disco.
4. Deja `imagen` en `null`.
5. Responde 200 con `data: null`.

### 7.9 `mensajes` — líneas 265 a 284

Incluye mensajes para categoría, nombre, descripción, estado e imagen. El mensaje `imagen.uploaded` cubre el caso en que PHP rechaza la carga por tamaño.

### 7.10 Verificación

```bash
php -l app/Http/Controllers/Api/V1/AlimentoController.php
```

---

## 8. Cambio 6 — MODIFICAR `routes/api.php`

**Ruta exacta:** `routes/api.php`  
**Tipo:** modificación  
**Ubicación:** imports al inicio y bloque nuevo antes de la sección `Dashboard`.

### 8.1 Imports que se agregan

Insertar **después de la línea 5 original** (`use App\Http\Controllers\AlimentoController;`) y **antes de la línea 6 original** (`use App\Http\Controllers\AuthAdminController;`):

```php
use App\Http\Controllers\Api\V1\AlimentoController as AlimentoV1Controller;
use App\Http\Controllers\Api\V1\CategoriaController as CategoriaV1Controller;
```

En el archivo final quedan en las líneas **6 y 7**.

Se usan alias porque ya existe un `AlimentoController` legacy en `App\Http\Controllers`. Sin alias, los dos nombres chocan.

### 8.2 Bloque que se inserta

Insertar **después de la línea 16 original** (`use Illuminate\Support\Facades\Route;`) y **antes de la línea 18 original** (`/* Dashboard */`).

El bloque ocupa las líneas **18 a 61** del archivo final:

```php
/*
|--------------------------------------------------------------------------
| API v1 (Actividad 4.2, sin autenticación)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Categorías
    |--------------------------------------------------------------------------
    */

    Route::get('/categorias', [CategoriaV1Controller::class, 'index'])->name('api.v1.categorias.index');

    Route::post('/categorias', [CategoriaV1Controller::class, 'store'])->name('api.v1.categorias.store');

    Route::get('/categorias/{id}', [CategoriaV1Controller::class, 'show'])->whereNumber('id')->name('api.v1.categorias.show');

    Route::match(['put', 'patch'], '/categorias/{id}', [CategoriaV1Controller::class, 'update'])->whereNumber('id')->name('api.v1.categorias.update');

    Route::delete('/categorias/{id}', [CategoriaV1Controller::class, 'destroy'])->whereNumber('id')->name('api.v1.categorias.destroy');

    /*
    |--------------------------------------------------------------------------
    | Alimentos
    |--------------------------------------------------------------------------
    */

    Route::get('/alimentos', [AlimentoV1Controller::class, 'index'])->name('api.v1.alimentos.index');

    Route::post('/alimentos', [AlimentoV1Controller::class, 'store'])->name('api.v1.alimentos.store');

    Route::post('/alimentos/{id}/imagen', [AlimentoV1Controller::class, 'storeImagen'])->whereNumber('id')->name('api.v1.alimentos.imagen.store');

    Route::delete('/alimentos/{id}/imagen', [AlimentoV1Controller::class, 'destroyImagen'])->whereNumber('id')->name('api.v1.alimentos.imagen.destroy');

    Route::get('/alimentos/{id}', [AlimentoV1Controller::class, 'show'])->whereNumber('id')->name('api.v1.alimentos.show');

    Route::match(['put', 'patch'], '/alimentos/{id}', [AlimentoV1Controller::class, 'update'])->whereNumber('id')->name('api.v1.alimentos.update');

    Route::delete('/alimentos/{id}', [AlimentoV1Controller::class, 'destroy'])->whereNumber('id')->name('api.v1.alimentos.destroy');
});
```

### 8.3 Rutas resultantes

| # | Método | Ruta completa | Nombre | Controlador y método |
| --- | --- | --- | --- | --- |
| 1 | GET | `/api/v1/categorias` | `api.v1.categorias.index` | `CategoriaV1Controller@index` |
| 2 | POST | `/api/v1/categorias` | `api.v1.categorias.store` | `CategoriaV1Controller@store` |
| 3 | GET | `/api/v1/categorias/{id}` | `api.v1.categorias.show` | `CategoriaV1Controller@show` |
| 4 | PUT / PATCH | `/api/v1/categorias/{id}` | `api.v1.categorias.update` | `CategoriaV1Controller@update` |
| 5 | DELETE | `/api/v1/categorias/{id}` | `api.v1.categorias.destroy` | `CategoriaV1Controller@destroy` |
| 6 | GET | `/api/v1/alimentos` | `api.v1.alimentos.index` | `AlimentoV1Controller@index` |
| 7 | POST | `/api/v1/alimentos` | `api.v1.alimentos.store` | `AlimentoV1Controller@store` |
| 8 | POST | `/api/v1/alimentos/{id}/imagen` | `api.v1.alimentos.imagen.store` | `AlimentoV1Controller@storeImagen` |
| 9 | DELETE | `/api/v1/alimentos/{id}/imagen` | `api.v1.alimentos.imagen.destroy` | `AlimentoV1Controller@destroyImagen` |
| 10 | GET | `/api/v1/alimentos/{id}` | `api.v1.alimentos.show` | `AlimentoV1Controller@show` |
| 11 | PUT / PATCH | `/api/v1/alimentos/{id}` | `api.v1.alimentos.update` | `AlimentoV1Controller@update` |
| 12 | DELETE | `/api/v1/alimentos/{id}` | `api.v1.alimentos.destroy` | `AlimentoV1Controller@destroy` |

Son 12 rutas registradas y 14 operaciones HTTP porque dos rutas aceptan `PUT` y `PATCH`.

### 8.4 Decisiones de orden y sintaxis

| Decisión | Motivo |
| --- | --- |
| `Route::prefix('v1')` | Laravel ya agrega `/api`; así la URL final es `/api/v1/...` |
| `Route::match(['put', 'patch'], ...)` | Una sola declaración atiende los dos verbos y el controlador distingue con `isMethod('patch')` |
| `->whereNumber('id')` | Un id no numérico responde 404 JSON antes de llegar al controlador |
| Rutas de imagen antes de `/{id}` | Evita que `/alimentos/1/imagen` se interprete como un `show` con id inválido |
| `->name('api.v1...')` | Permite generar URLs y probar con `route()` sin ambigüedad |
| Grupo separado del resto | En la 4.1 se puede envolver o subdividir sin tocar las rutas legacy |

### 8.5 Qué NO se modifica en este archivo

- No se tocan las rutas que están después del bloque nuevo.
- No se eliminan rutas legacy.
- No se agrega middleware en esta actividad.

La Actividad 4.1 moverá las rutas de escritura a `/api/v1/admin/...` y protegerá los grupos. Ese procedimiento está en `CONTRATO_DISEÑO_API_4.1.md`.

### 8.6 Verificación

```bash
php -l routes/api.php
php artisan route:clear
php artisan route:list --path=api/v1
```

Deben aparecer las 12 rutas de la tabla 8.3.

---

## 9. Cambio 7 — EJECUTAR el enlace de almacenamiento

**Comando:**

```bash
php artisan storage:link
```

**Qué crea:** un enlace simbólico en `public/storage` que apunta a `storage/app/public`.

**Por qué:** las imágenes se guardan en `storage/app/public/alimentos`, pero el navegador solo puede acceder a `public/`. Sin el enlace, `imagen_url` devuelve 404.

**Importante:** `public/storage` está en `.gitignore`. No se versiona. Cada integrante debe ejecutar el comando después de clonar el proyecto.

Verificar:

```bash
php artisan storage:link
```

Debe responder:

```text
The [public/storage] link has been connected to [storage/app/public].
```

Si ya existe, Laravel indica que el enlace ya está creado. No es un error.

---

## 10. Cambio 8 — CREAR la colección Postman

**Ruta exacta:** `postman/collections/actividad-4.2-api-v1/`

### 10.1 Árbol de archivos

```text
postman/collections/actividad-4.2-api-v1/
├── .resources/
│   └── definition.yaml
├── Categorias/
│   ├── .resources/
│   │   └── definition.yaml
│   ├── actualizar-parcial.request.yaml
│   ├── actualizar-inexistente.request.yaml
│   ├── actualizar.request.yaml
│   ├── crear.request.yaml
│   ├── eliminar-inexistente.request.yaml
│   ├── eliminar.request.yaml
│   ├── error-500.request.yaml
│   ├── inexistente.request.yaml
│   ├── listar.request.yaml
│   ├── metodo-no-permitido.request.yaml
│   ├── obtener.request.yaml
│   └── validacion.request.yaml
└── Alimentos/
    ├── .resources/
    │   └── definition.yaml
    ├── actualizar-imagen.request.yaml
    ├── actualizar-inexistente.request.yaml
    ├── actualizar-parcial.request.yaml
    ├── actualizar.request.yaml
    ├── archivo-invalido.request.yaml
    ├── crear-categoria-apoyo.request.yaml
    ├── crear.request.yaml
    ├── eliminar-categoria-apoyo.request.yaml
    ├── eliminar-imagen.request.yaml
    ├── eliminar-inexistente.request.yaml
    ├── eliminar.request.yaml
    ├── imagen-grande.png
    ├── imagen-grande.request.yaml
    ├── imagen.png
    ├── inexistente.request.yaml
    ├── listar.request.yaml
    ├── no-imagen.txt
    ├── obtener.request.yaml
    └── validacion.request.yaml
```

### 10.2 Variables de colección

En `postman/collections/actividad-4.2-api-v1/.resources/definition.yaml`:

| Variable | Valor inicial | Uso |
| --- | --- | --- |
| `baseUrlV1` | `http://127.0.0.1:8000/api/v1` | Base de todas las solicitudes |
| `categoriaId` | vacío | Id creado por la prueba de categorías |
| `categoriaAlimentoId` | vacío | Categoría de apoyo para las pruebas de alimentos |
| `alimentoId` | vacío | Id creado por la prueba de alimentos |

La colección también agrega automáticamente `Accept: application/json` y valida en cada respuesta:

1. Que el `Content-Type` sea JSON.
2. Que existan exactamente los campos `success`, `message`, `data` y `errors`.
3. Que `success` sea booleano, `message` sea texto, y `data` y `errors` sean objeto, arreglo o `null`.

Esas comprobaciones se ejecutan en las 28 respuestas, por eso cada request aporta 6 aserciones globales además de sus aserciones específicas.

### 10.3 Requests y orden de ejecución

**Categorías — 12 requests**

| # | Archivo | Método | Endpoint | Esperado |
| --- | --- | --- | --- | --- |
| 1 | `listar.request.yaml` | GET | `/categorias` | 200 |
| 2 | `crear.request.yaml` | POST | `/categorias` | 201 y guarda `categoriaId` |
| 3 | `obtener.request.yaml` | GET | `/categorias/{{categoriaId}}` | 200 |
| 4 | `actualizar.request.yaml` | PUT | `/categorias/{{categoriaId}}` | 200 |
| 5 | `actualizar-parcial.request.yaml` | PATCH | `/categorias/{{categoriaId}}` | 200 |
| 6 | `actualizar-inexistente.request.yaml` | PUT | `/categorias/999999` | 404 |
| 7 | `inexistente.request.yaml` | GET | `/categorias/999999` | 404 |
| 8 | `metodo-no-permitido.request.yaml` | DELETE | `/categorias` | 405 |
| 9 | `error-500.request.yaml` | GET | `/categorias?buscar[]=x` | 500 controlado |
| 10 | `validacion.request.yaml` | POST | `/categorias` | 422 |
| 11 | `eliminar.request.yaml` | DELETE | `/categorias/{{categoriaId}}` | 200 |
| 12 | `eliminar-inexistente.request.yaml` | DELETE | `/categorias/999999` | 404 |

**Alimentos — 16 requests**

| # | Archivo | Método | Endpoint | Esperado |
| --- | --- | --- | --- | --- |
| 1 | `crear-categoria-apoyo.request.yaml` | POST | `/categorias` | 201 y guarda `categoriaAlimentoId` |
| 2 | `listar.request.yaml` | GET | `/alimentos` | 200 |
| 3 | `crear.request.yaml` | POST | `/alimentos` con `imagen.png` | 201 y guarda `alimentoId` |
| 4 | `obtener.request.yaml` | GET | `/alimentos/{{alimentoId}}` | 200 |
| 5 | `actualizar-imagen.request.yaml` | POST | `/alimentos/{{alimentoId}}/imagen` | 200 |
| 6 | `actualizar.request.yaml` | PUT | `/alimentos/{{alimentoId}}` | 200 |
| 7 | `actualizar-parcial.request.yaml` | PATCH | `/alimentos/{{alimentoId}}` | 200 |
| 8 | `actualizar-inexistente.request.yaml` | PUT | `/alimentos/999999` | 404 |
| 9 | `archivo-invalido.request.yaml` | POST | `/alimentos/{{alimentoId}}/imagen` con `.txt` | 422 |
| 10 | `imagen-grande.request.yaml` | POST | `/alimentos/{{alimentoId}}/imagen` con `imagen-grande.png` | 422 |
| 11 | `validacion.request.yaml` | POST | `/alimentos` inválido | 422 |
| 12 | `eliminar-imagen.request.yaml` | DELETE | `/alimentos/{{alimentoId}}/imagen` | 200 |
| 13 | `eliminar.request.yaml` | DELETE | `/alimentos/{{alimentoId}}` | 200 |
| 14 | `eliminar-inexistente.request.yaml` | DELETE | `/alimentos/999999` | 404 |
| 15 | `inexistente.request.yaml` | GET | `/alimentos/999999` | 404 |
| 16 | `eliminar-categoria-apoyo.request.yaml` | DELETE | `/categorias/{{categoriaAlimentoId}}` | 200 |

### 10.4 Archivos de prueba

| Archivo | Tipo | Uso |
| --- | --- | --- |
| `Alimentos/imagen.png` | Imagen PNG menor a 2 MB | Crear y reemplazar imagen |
| `Alimentos/imagen-grande.png` | Imagen PNG de 2.171.220 bytes (2.120 KB) | Probar el 422 por tamaño |
| `Alimentos/no-imagen.txt` | Texto | Probar el 422 por formato inválido |

Se referencian con **ruta relativa a la raíz del repositorio**. Por eso el comando de ejecución debe lanzarse desde la raíz del proyecto, no desde la carpeta de la colección.

### 10.5 Comandos

```bash
postman collection lint postman/collections/actividad-4.2-api-v1
postman collection run postman/collections/actividad-4.2-api-v1
```

Resultado esperado:

```text
requests:          28 ejecutados / 0 fallidos
test-scripts:      28 ejecutados / 0 fallidos
assertions:        224 ejecutadas / 0 fallidas
lint:              No issues found (28 requests)
```

### 10.6 Limpieza de datos de prueba

La colección elimina con `DELETE`, por lo que los registros quedan con borrado lógico. No se borran físicamente.

Si se quiere dejar la base sin esos registros de prueba, ejecutar desde `php artisan tinker`:

```php
App\Models\Alimento::onlyTrashed()->where('nombre', 'like', 'Alimento%')->forceDelete();
App\Models\CategoriaAlimento::onlyTrashed()->where('nombre', 'like', '%prueba%')->forceDelete();
```

Revisar antes los nombres exactos usados por la colección. No ejecutar un `forceDelete` masivo sin filtro.

---

## 11. Qué se elimina exactamente

### 11.1 Eliminaciones funcionales

Ninguna.

No se eliminaron:

- archivos;
- rutas;
- controladores;
- modelos;
- migraciones;
- tablas;
- columnas;
- registros de producción.

### 11.2 Eliminaciones de formato

Solo en `app/Models/Alimento.php` se quitaron líneas en blanco con espacios antes de la llave final. Es una limpieza de Pint, no un cambio funcional.

### 11.3 Archivos que parecen duplicados y no deben borrarse

| Archivo | Por qué se conserva |
| --- | --- |
| `app/Http/Controllers/AlimentoController.php` | Controlador legacy usado por rutas web existentes |
| `app/Http/Controllers/Api/CategoriaAlimentoController.php` | Controlador legacy de la entrega anterior |
| `routes/api.php` (rutas después de la línea 61) | Rutas web/legacy que no pertenecen a la 4.2 |

Los controladores nuevos viven en `Api/V1/` precisamente para no romper los anteriores.

---

## 12. Verificación final

### 12.1 Sintaxis

```bash
php -l bootstrap/app.php
php -l app/Models/Alimento.php
php -l app/Models/CategoriaAlimento.php
php -l app/Http/Controllers/Api/V1/CategoriaController.php
php -l app/Http/Controllers/Api/V1/AlimentoController.php
php -l routes/api.php
```

Todos deben responder `No syntax errors detected`.

### 12.2 Rutas

```bash
php artisan route:list --path=api/v1
```

Comprobar:

- 12 rutas registradas.
- Prefijo `api/v1`.
- Nombres `api.v1.*`.
- Restricción numérica en `{id}`.
- Sin middleware de autenticación.

### 12.3 Pruebas rápidas con `curl`

Listar categorías:

```bash
curl -H "Accept: application/json" http://127.0.0.1:8000/api/v1/categorias
```

Crear categoría:

```bash
curl -X POST http://127.0.0.1:8000/api/v1/categorias \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d "{\"nombre\":\"Categoria curl\",\"descripcion\":\"Prueba\",\"estado\":1}"
```

Consultar inexistente:

```bash
curl -H "Accept: application/json" http://127.0.0.1:8000/api/v1/categorias/999999
```

Debe devolver 404 con el JSON estándar.

### 12.4 Verificación en base de datos

```sql
SELECT id_categoria, nombre, estado, deleted_at
FROM categorias_alimento
ORDER BY id_categoria DESC
LIMIT 5;

SELECT id_alimento, id_categoria, nombre, imagen, estado, deleted_at
FROM alimentos
ORDER BY id_alimento DESC
LIMIT 5;
```

Comprobar:

- `estado` se guarda como `1` o `0`.
- `imagen` contiene una ruta como `alimentos/xxxx.png`.
- `deleted_at` se llena después de un `DELETE`.

### 12.5 Verificación del enlace público

Abrir en el navegador la `imagen_url` devuelta por la API. Debe mostrar la imagen, no un 404.

Si devuelve 404:

```bash
php artisan storage:link
```

### 12.6 Verificación de la colección

```bash
postman collection run postman/collections/actividad-4.2-api-v1
```

Debe terminar con 28 requests y 224 aserciones aprobadas.

---

## 13. Commits de la implementación

Los commits originales, en orden, fueron:

| Commit | Mensaje | Contenido |
| --- | --- | --- |
| `22a238d` | `feat: estandarizar errores json de la api v1` | `bootstrap/app.php` |
| `2899fde` | `feat: agregar url publica de imagen y tipos a los modelos` | `Alimento.php`, `CategoriaAlimento.php` |
| `e5d26f3` | `feat: crear controladores de categorias y alimentos de la api v1` | Dos controladores nuevos |
| `a472823` | `feat: registrar rutas de la api v1` | `routes/api.php` |
| `e6ec51f` | `test: agregar coleccion postman de la api v1` | Colección Postman |
| `64f1d3b` | `fix: cubrir la raiz de la api v1 en el manejo json` | Ajuste de `api/v1` sin barra final |
| `9e1d337` | `docs: agregar guia de cambios de la actividad 4.2` | Esta guía |

Si se replica desde cero, se pueden agrupar así:

```bash
git add bootstrap/app.php
git commit -m "feat: estandarizar errores json de la api v1"

git add app/Models/Alimento.php app/Models/CategoriaAlimento.php
git commit -m "feat: agregar url publica de imagen y tipos a los modelos"

git add app/Http/Controllers/Api/V1
git commit -m "feat: crear controladores de categorias y alimentos de la api v1"

git add routes/api.php
git commit -m "feat: registrar rutas de la api v1"

git add postman/collections/actividad-4.2-api-v1
git commit -m "test: agregar coleccion postman de la api v1"

git add GUIA_CAMBIOS_4.2.md CONTRATO_DISEÑO_API_4.1.md
git commit -m "docs: documentar la api 4.2 y el contrato de la 4.1"
```

---

## 14. Problemas comunes y solución

| Síntoma | Causa | Solución |
| --- | --- | --- |
| La imagen devuelve 404 | Falta el enlace público | `php artisan storage:link` |
| La imagen se guarda pero no se ve | Se guardó en `storage/app/public` sin enlace | Verificar `public/storage` |
| Error 419 o redirección al login | Se usó una ruta web o falta `Accept: application/json` | Enviar el encabezado y usar `/api/v1` |
| Error 405 en Postman | Se ejecutaron requests fuera de orden | Ejecutar la colección completa en orden |
| No encuentra `imagen.png` | Se ejecutó Postman fuera de la raíz | Ejecutar desde la raíz del repositorio |
| Las rutas nuevas no aparecen | Caché de rutas | `php artisan route:clear` |
| El `.env` no se lee | Caché de configuración | `php artisan config:clear` |
| Imagen mayor a 2 MB | Regla `max:2048` | Usar una imagen menor o ajustar la regla |
| Archivo mayor a `post_max_size` | Límite de PHP | Ajustar `php.ini`; la API responde 413 |
| `PUT` no reemplaza la imagen | PHP no procesa archivos en `PUT` | Usar `POST /alimentos/{id}/imagen` |
| `estado` llega como `1` | Falta el cast | Verificar `$casts` en los modelos |
| `imagen_url` no aparece | Falta `$appends` | Verificar `app/Models/Alimento.php` |

---

## 15. Entrega de la Actividad 4.1

La 4.2 deja la base lista, pero **no implementa autenticación**. El compañero debe leer y ejecutar:

```text
CONTRATO_DISEÑO_API_4.1.md
```

Ese documento contiene:

1. Integración de Sanctum.
2. `HasApiTokens` en `Administrador` y `Usuario`.
3. Alias `ability` y `abilities`.
4. Respuesta 401 estandarizada.
5. Separación `/api/v1/admin/...` y `/api/v1/cliente/...`.
6. Endpoints `POST /login`, `POST /logout` y `GET /perfil`.
7. Pruebas obligatorias de 401, 403 y acceso a datos ajenos.

---

## 16. Ajustes finales aplicados al refuerzo

Estos ajustes consolidan el estado final antes de la Actividad 4.1:

| Ajuste | Archivo | Detalle |
| --- | --- | --- |
| Colecciones limpias en `data` | `app/Http/Controllers/Api/V1/CategoriaController.php` y `AlimentoController.php` | `index()` ya no usa `paginate()` ni `por_pagina`; devuelve el arreglo completo con `get()` |
| 404 por excepción | Ambos controladores, en `show`, `update`, `destroy`, `storeImagen` y `destroyImagen` | Se usa `findOrFail($id)` dentro de `try-catch` con `ModelNotFoundException`; el 404 con las cuatro llaves lo emite el controlador |
| URL de imagen con `asset()` | `app/Models/Alimento.php` | El accesor `getImagenUrlAttribute()` usa `asset('storage/'.$this->imagen)` |
| Contrato verificado en Postman | Colección `actividad-4.2-api-v1` | 28 requests y 224 aserciones aprobadas; los `listar` validan que `data` sea arreglo |

El prefijo `api/v1` queda único y sin duplicación; `php artisan route:list --path=api/v1` muestra exactamente 12 rutas y `--path=api/api` no devuelve ninguna.

---

# Anexo A — `CategoriaController.php` completo

**Archivo:** `app/Http/Controllers/Api/V1/CategoriaController.php`  
**Líneas:** 159

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CategoriaAlimento;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $categorias = CategoriaAlimento::query()
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $query->where('nombre', 'like', '%'.$request->query('buscar').'%');
            })
            ->when($request->filled('estado'), function ($query) use ($request) {
                $query->where('estado', $request->query('estado'));
            })
            ->orderBy('id_categoria')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Categorías consultadas correctamente',
            'data' => $categorias,
            'errors' => null,
        ], 200);
    }

    public function show($id)
    {
        try {
            $categoria = CategoriaAlimento::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La categoría solicitada no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Categoría consultada correctamente',
            'data' => $categoria,
            'errors' => null,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
        ], $this->mensajes());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos',
                'data' => null,
                'errors' => $validator->errors(),
            ], 422);
        }

        $categoria = CategoriaAlimento::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada correctamente',
            'data' => $categoria,
            'errors' => null,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $categoria = CategoriaAlimento::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La categoría solicitada no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        $parcial = $request->isMethod('patch');

        $reglas = [
            'nombre' => ($parcial ? 'sometimes|required' : 'required').'|string|max:80',
            'descripcion' => ($parcial ? 'sometimes|required' : 'required').'|string|max:200',
            'estado' => ($parcial ? 'sometimes|required' : 'required').'|boolean',
        ];

        $validator = Validator::make($request->all(), $reglas, $this->mensajes());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos',
                'data' => null,
                'errors' => $validator->errors(),
            ], 422);
        }

        $categoria->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada correctamente',
            'data' => $categoria->fresh(),
            'errors' => null,
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $categoria = CategoriaAlimento::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La categoría solicitada no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada correctamente',
            'data' => null,
            'errors' => null,
        ], 200);
    }

    private function mensajes(): array
    {
        return [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.string' => 'El nombre de la categoría debe ser texto.',
            'nombre.max' => 'El nombre de la categoría no debe superar los 80 caracteres.',
            'descripcion.required' => 'La descripción de la categoría es obligatoria.',
            'descripcion.string' => 'La descripción de la categoría debe ser texto.',
            'descripcion.max' => 'La descripción de la categoría no debe superar los 200 caracteres.',
            'estado.required' => 'El estado de la categoría es obligatorio.',
            'estado.boolean' => 'El estado de la categoría debe ser verdadero o falso.',
        ];
    }
}
```

---

# Anexo B — `AlimentoController.php` completo

**Archivo:** `app/Http/Controllers/Api/V1/AlimentoController.php`  
**Líneas:** 286

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Alimento;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AlimentoController extends Controller
{
    public function index(Request $request)
    {
        $alimentos = Alimento::with('categoria')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $query->where('nombre', 'like', '%'.$request->query('buscar').'%');
            })
            ->when($request->filled('id_categoria'), function ($query) use ($request) {
                $query->where('id_categoria', $request->query('id_categoria'));
            })
            ->when($request->filled('estado'), function ($query) use ($request) {
                $query->where('estado', $request->query('estado'));
            })
            ->orderBy('id_alimento')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Alimentos consultados correctamente',
            'data' => $alimentos,
            'errors' => null,
        ], 200);
    }

    public function show($id)
    {
        try {
            $alimento = Alimento::with('categoria')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento solicitado no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alimento consultado correctamente',
            'data' => $alimento,
            'errors' => null,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_categoria' => [
                'required',
                'integer',
                Rule::exists('categorias_alimento', 'id_categoria')->whereNull('deleted_at'),
            ],
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], $this->mensajes());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos',
                'data' => null,
                'errors' => $validator->errors(),
            ], 422);
        }

        $datos = $validator->validated();
        unset($datos['imagen']);

        $alimento = Alimento::create($datos);

        if ($request->hasFile('imagen')) {
            $ruta = $request->file('imagen')->store('alimentos', 'public');
            $alimento->update(['imagen' => $ruta]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alimento creado correctamente',
            'data' => $alimento->load('categoria'),
            'errors' => null,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $alimento = Alimento::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento solicitado no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        $parcial = $request->isMethod('patch');

        $reglas = [
            'id_categoria' => [
                $parcial ? 'sometimes' : 'required',
                'integer',
                Rule::exists('categorias_alimento', 'id_categoria')->whereNull('deleted_at'),
            ],
            'nombre' => ($parcial ? 'sometimes|required' : 'required').'|string|max:80',
            'descripcion' => ($parcial ? 'sometimes|required' : 'required').'|string|max:200',
            'estado' => ($parcial ? 'sometimes|required' : 'required').'|boolean',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $validator = Validator::make($request->all(), $reglas, $this->mensajes());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos',
                'data' => null,
                'errors' => $validator->errors(),
            ], 422);
        }

        $datos = $validator->validated();
        unset($datos['imagen']);

        $alimento->update($datos);

        if ($request->hasFile('imagen')) {
            $imagenAnterior = $alimento->imagen;
            $ruta = $request->file('imagen')->store('alimentos', 'public');

            $alimento->update(['imagen' => $ruta]);

            if ($imagenAnterior) {
                Storage::disk('public')->delete($imagenAnterior);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Alimento actualizado correctamente',
            'data' => $alimento->fresh()->load('categoria'),
            'errors' => null,
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $alimento = Alimento::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento solicitado no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        if ($alimento->imagen) {
            Storage::disk('public')->delete($alimento->imagen);
        }

        $alimento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alimento eliminado correctamente',
            'data' => null,
            'errors' => null,
        ], 200);
    }

    public function storeImagen(Request $request, $id)
    {
        try {
            $alimento = Alimento::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento solicitado no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], $this->mensajes());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos',
                'data' => null,
                'errors' => $validator->errors(),
            ], 422);
        }

        $imagenAnterior = $alimento->imagen;
        $ruta = $request->file('imagen')->store('alimentos', 'public');

        $alimento->update(['imagen' => $ruta]);

        if ($imagenAnterior) {
            Storage::disk('public')->delete($imagenAnterior);
        }

        return response()->json([
            'success' => true,
            'message' => 'Imagen del alimento actualizada correctamente',
            'data' => $alimento->fresh(),
            'errors' => null,
        ], 200);
    }

    public function destroyImagen($id)
    {
        try {
            $alimento = Alimento::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento solicitado no existe',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        if (! $alimento->imagen) {
            return response()->json([
                'success' => false,
                'message' => 'El alimento no tiene una imagen asociada',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        Storage::disk('public')->delete($alimento->imagen);
        $alimento->update(['imagen' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Imagen del alimento eliminada correctamente',
            'data' => null,
            'errors' => null,
        ], 200);
    }

    private function mensajes(): array
    {
        return [
            'id_categoria.required' => 'La categoría del alimento es obligatoria.',
            'id_categoria.integer' => 'La categoría del alimento debe ser un número entero.',
            'id_categoria.exists' => 'La categoría seleccionada no existe.',
            'nombre.required' => 'El nombre del alimento es obligatorio.',
            'nombre.string' => 'El nombre del alimento debe ser texto.',
            'nombre.max' => 'El nombre del alimento no debe superar los 80 caracteres.',
            'descripcion.required' => 'La descripción del alimento es obligatoria.',
            'descripcion.string' => 'La descripción del alimento debe ser texto.',
            'descripcion.max' => 'La descripción del alimento no debe superar los 200 caracteres.',
            'estado.required' => 'El estado del alimento es obligatorio.',
            'estado.boolean' => 'El estado del alimento debe ser verdadero o falso.',
            'imagen.required' => 'La imagen es obligatoria.',
            'imagen.image' => 'El archivo enviado debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpg, jpeg, png o webp.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            'imagen.uploaded' => 'La imagen no se pudo cargar. Verifique el tamaño permitido por el servidor.',
        ];
    }
}
```
