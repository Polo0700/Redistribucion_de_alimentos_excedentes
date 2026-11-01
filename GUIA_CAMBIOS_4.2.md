# Guía de cambios — Actividad 4.2

Implementación de la API RESTful de la Actividad 4.2 sobre el proyecto **Redistribución de Alimentos Excedentes**, dejando preparada la estructura de diseño de la Actividad 4.1 para que la autenticación se agregue después sin rehacer rutas ni controladores.

---

## 1. Resumen de lo implementado

| Elemento | Resultado |
| --- | --- |
| Recursos implementados | Categorías y Alimentos |
| Prefijo de la API | `/api/v1` |
| Endpoints nuevos | 14 operaciones sobre 12 rutas |
| Autenticación | Ninguna, tal como exige la Actividad 4.2 |
| Validación | `Validator::make()` en creación y actualización |
| Búsqueda individual | `find()` de Eloquent con respuesta 404 cuando devuelve `null` |
| Respuestas | Estructura única `success`, `message`, `data`, `errors` |
| Imágenes | Carga, consulta pública, reemplazo y eliminación en Alimentos |
| Errores globales | JSON estandarizado para 400, 401, 403, 404, 405, 413, 415, 422, 429 y 500 |
| Pruebas | Colección Postman con 21 requests y 84 aserciones, todas aprobadas |

No se eliminó ningún endpoint, controlador, modelo, tabla ni migración existente.

---

## 2. Estructura de diseño base de la Actividad 4.1

### 2.1 Recursos del sistema

| Recurso | Modelo | Tabla | Clave primaria | Estado |
| --- | --- | --- | --- | --- |
| Categorías | `CategoriaAlimento` | `categorias_alimento` | `id_categoria` | Implementado en 4.2 |
| Alimentos | `Alimento` | `alimentos` | `id_alimento` | Implementado en 4.2 |
| Donaciones | `Donacion` | `donaciones` | `id_donacion` | Pendiente para 4.1 |
| Solicitudes | `Solicitud` | `solicitudes` | `id_solicitud` | Pendiente para 4.1 |
| Entregas | `Entrega` | `entregas` | `id_entrega` | Pendiente para 4.1 |
| Usuarios | `Usuario` | `usuarios` | `id_usuario` | Pendiente para 4.1 |
| Administradores | `Administrador` | `administradores` | `id_administrador` | Pendiente para 4.1 |
| Acciones importantes | `AccionImportante` | `acciones_importantes` | `id_accion` | Pendiente para 4.1 |

### 2.2 Convención de URLs y métodos

- Las URLs usan sustantivos en plural: `/api/v1/categorias`, `/api/v1/alimentos`.
- El verbo HTTP define la operación, nunca la URL.
- El prefijo `/api` lo agrega Laravel automáticamente desde `bootstrap/app.php`; el prefijo `v1` se declara en `routes/api.php`.
- Los identificadores se validan con `->whereNumber('id')`; un id no numérico produce un 404 JSON antes de llegar al controlador.

### 2.3 Endpoints de la Actividad 4.2

| Método | Endpoint | Operación | Código esperado |
| --- | --- | --- | --- |
| GET | `/api/v1/categorias` | Listar categorías con paginación y filtros | 200 |
| GET | `/api/v1/categorias/{id}` | Consultar una categoría | 200 / 404 |
| POST | `/api/v1/categorias` | Crear categoría | 201 / 422 |
| PUT | `/api/v1/categorias/{id}` | Reemplazar categoría | 200 / 404 / 422 |
| PATCH | `/api/v1/categorias/{id}` | Actualizar parcialmente | 200 / 404 / 422 |
| DELETE | `/api/v1/categorias/{id}` | Eliminar categoría | 200 / 404 |
| GET | `/api/v1/alimentos` | Listar alimentos con su categoría | 200 |
| GET | `/api/v1/alimentos/{id}` | Consultar un alimento y su imagen | 200 / 404 |
| POST | `/api/v1/alimentos` | Crear alimento con imagen opcional | 201 / 422 |
| PUT | `/api/v1/alimentos/{id}` | Reemplazar alimento | 200 / 404 / 422 |
| PATCH | `/api/v1/alimentos/{id}` | Actualizar parcialmente | 200 / 404 / 422 |
| DELETE | `/api/v1/alimentos/{id}` | Eliminar alimento e imagen | 200 / 404 |
| POST | `/api/v1/alimentos/{id}/imagen` | Asociar o reemplazar imagen | 200 / 404 / 422 |
| DELETE | `/api/v1/alimentos/{id}/imagen` | Retirar imagen asociada | 200 / 404 |

### 2.4 Contrato JSON único

Todas las respuestas con cuerpo usan la misma estructura:

```json
{
  "success": true,
  "message": "Mensaje descriptivo",
  "data": {},
  "errors": null
}
```

- Éxito: `success` en `true`, `data` con la información y `errors` en `null`.
- Error: `success` en `false`, `data` en `null` y `errors` con el detalle o en `null`.
- En validación, `errors` contiene un objeto con un arreglo de mensajes por campo.

### 2.5 Áreas administrativa y cliente (diseño 4.1)

La Actividad 4.1 pide separar el área administrativa del área cliente. La base queda preparada así:

| Área | Prefijo propuesto | Operaciones | Acceso |
| --- | --- | --- | --- |
| Catálogo público | `/api/v1/categorias`, `/api/v1/alimentos` | Consultar categorías y alimentos | Sin autenticación |
| Administración | `/api/v1/admin/...` | CRUD de categorías, alimentos, donaciones, solicitudes, entregas, usuarios y administradores | Token de administrador |
| Cliente | `/api/v1/cliente/...` | Perfil, solicitudes propias, donaciones propias e historial | Token de cliente |
| Autenticación administrativa | `/api/v1/admin/login` | Iniciar sesión de administrador | Público |
| Autenticación cliente | `/api/v1/cliente/registro`, `/api/v1/cliente/login` | Registro e inicio de sesión | Público |

Reglas de autorización previstas:

- Consultar el catálogo puede ser público.
- Crear, actualizar o eliminar catálogo queda reservado a administradores.
- Un cliente solo puede consultar y modificar sus propios registros.
- El rol nunca se toma de un campo enviado por el frontend; se obtiene de la identidad autenticada.
- Las rutas administrativas y de cliente se protegerán con middleware, no solo con el prefijo de la URL.

Cómo agregar la autenticación después, sin rehacer esta actividad:

1. Agregar `Laravel\Sanctum\HasApiTokens` a los modelos que iniciarán sesión.
2. Crear los controladores de autenticación para administrador y cliente.
3. Envolver el grupo `Route::prefix('v1')` o crear subgrupos `admin` y `cliente` con `->middleware('auth:sanctum')`.
4. Separar permisos con Gates o Policies y mantener el catálogo público en su propio grupo.
5. Reutilizar los controladores actuales para las operaciones administrativas, sin duplicar la lógica.

En esta entrega **no se agregó ningún middleware de autenticación**, tal como indica la Actividad 4.2.

---

## 3. Archivos creados y modificados

| Archivo | Tipo | Contenido |
| --- | --- | --- |
| `routes/api.php` | Modificado | Imports con alias y grupo `v1` con las 12 rutas |
| `app/Http/Controllers/Api/V1/CategoriaController.php` | Creado | CRUD de categorías y mensajes de validación |
| `app/Http/Controllers/Api/V1/AlimentoController.php` | Creado | CRUD de alimentos y gestión de imágenes |
| `app/Models/Alimento.php` | Modificado | `imagen_url`, casts y limpieza de espacios finales |
| `app/Models/CategoriaAlimento.php` | Modificado | Cast de `estado` a booleano |
| `bootstrap/app.php` | Modificado | Respuestas JSON estandarizadas para excepciones de `/api/v1` |
| `postman/collections/actividad-4.2-api-v1/` | Creado | Colección Postman de la actividad (21 requests) |
| `GUIA_CAMBIOS_4.2.md` | Creado | Este documento |

Los controladores anteriores de `app/Http/Controllers/Api/` y sus rutas se conservaron intactos para no romper la entrega anterior.

---

## 4. Cambios paso a paso

### 4.1 `bootstrap/app.php`

Qué se agregó:

- Imports de `Illuminate\Http\Request`, `Illuminate\Validation\ValidationException`, `Symfony\Component\HttpKernel\Exception\HttpExceptionInterface` y `Throwable`.
- `shouldRenderJsonWhen()` para que toda solicitud de `/api/*` o con `Accept: application/json` espere JSON.
- Un manejador `render()` que solo actúa sobre `/api/v1` y `/api/v1/*`.

Qué hace el manejador:

1. Si la excepción es `ValidationException`, responde 422 con los errores por campo.
2. Si es una excepción HTTP, usa su código real (404, 405, 413, etc.).
3. Si es cualquier otra excepción, responde 500 y registra el error con `report()`.
4. Nunca devuelve trazas, SQL ni rutas internas.
5. Usa mensajes claros en español según el código HTTP.

Motivo: la actividad exige que incluso los errores de enrutamiento, métodos no permitidos, archivos demasiado grandes y fallos internos se devuelvan en JSON con la estructura estándar.

### 4.2 `routes/api.php`

Qué se agregó:

- Imports con alias para no chocar con los controladores existentes:
  - `AlimentoV1Controller`
  - `CategoriaV1Controller`
- Un grupo `Route::prefix('v1')` con 12 rutas.
- Nombres de ruta con el formato `api.v1.<recurso>.<accion>`.
- Restricción numérica del parámetro `{id}`.

El grupo está separado del resto del archivo para que agregar middleware en la Actividad 4.1 sea tan simple como envolverlo:

```php
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // rutas existentes
});
```

Orden de rutas de alimentos:

1. `GET /alimentos`
2. `POST /alimentos`
3. `POST /alimentos/{id}/imagen`
4. `DELETE /alimentos/{id}/imagen`
5. `GET /alimentos/{id}`
6. `PUT|PATCH /alimentos/{id}`
7. `DELETE /alimentos/{id}`

Las rutas de imagen se declaran antes de `/{id}` para evitar ambigüedades.

### 4.3 `CategoriaController`

Métodos agregados:

| Método | Qué hace |
| --- | --- |
| `index(Request $request)` | Lista paginada con filtros `buscar`, `estado` y `por_pagina` |
| `show($id)` | Busca con `find()`; si es `null`, responde 404 |
| `store(Request $request)` | Valida y crea; responde 201 con el registro |
| `update(Request $request, $id)` | Busca, valida y actualiza; responde 200 |
| `destroy($id)` | Busca y elimina; responde 200 |
| `mensajes()` | Mensajes de validación en español |

Reglas de validación:

| Campo | Crear | PUT | PATCH |
| --- | --- | --- | --- |
| `nombre` | `required|string|max:80` | `required|string|max:80` | `sometimes|required|string|max:80` |
| `descripcion` | `required|string|max:200` | `required|string|max:200` | `sometimes|required|string|max:200` |
| `estado` | `required|boolean` | `required|boolean` | `sometimes|required|boolean` |

Detalles:

- `index` limita `por_pagina` entre 1 y 50.
- Se usa `paginate()`, por lo que `data` incluye `data`, `current_page`, `last_page`, `total` y enlaces.
- `update` distingue PUT de PATCH con `$request->isMethod('patch')`.
- `destroy` usa borrado lógico porque el modelo tiene `SoftDeletes`.
- No se permite eliminar físicamente desde esta API.

### 4.4 `AlimentoController`

Métodos agregados:

| Método | Qué hace |
| --- | --- |
| `index(Request $request)` | Lista alimentos con su categoría y filtros |
| `show($id)` | Busca con `find()` e incluye categoría e imagen |
| `store(Request $request)` | Valida, crea y guarda la imagen opcional |
| `update(Request $request, $id)` | Busca, valida y actualiza datos e imagen |
| `destroy($id)` | Busca, elimina la imagen y aplica borrado lógico |
| `storeImagen(Request $request, $id)` | Asocia o reemplaza la imagen |
| `destroyImagen($id)` | Retira la imagen y borra el archivo |
| `mensajes()` | Mensajes de validación en español |

Reglas de validación:

| Campo | Regla |
| --- | --- |
| `id_categoria` | `required`, `integer` y `exists` en `categorias_alimento` sin `deleted_at` |
| `nombre` | `required`, `string`, máximo 80 |
| `descripcion` | `required`, `string`, máximo 200 |
| `estado` | `required`, `boolean` |
| `imagen` | `nullable`, `image`, `mimes:jpg,jpeg,png,webp`, máximo 2048 KB |

En PATCH, `id_categoria`, `nombre`, `descripcion` y `estado` cambian a `sometimes|required`; la imagen siempre es opcional.

Flujo de imágenes:

1. `store()` guarda la imagen recibida en el disco `public`, dentro de `alimentos/`.
2. Laravel genera un nombre aleatorio y seguro con `store()`.
3. La ruta relativa se guarda en la columna `imagen`.
4. `storeImagen()` reemplaza la imagen y elimina el archivo anterior.
5. `destroyImagen()` elimina el archivo y deja `imagen` en `null`.
6. `destroy()` elimina el archivo antes del borrado lógico.
7. `show()` e `index()` devuelven `imagen` (ruta relativa) e `imagen_url` (URL pública).

Motivo de la regla `exists` con `whereNull('deleted_at')`: no se debe asociar un alimento a una categoría eliminada lógicamente.

### 4.5 Modelos

`app/Models/Alimento.php`:

- Se agregó `protected $appends = ['imagen_url'];`.
- Se agregó el accesor `getImagenUrlAttribute()`.
- Se agregaron casts: `id_categoria` a entero y `estado` a booleano.
- Se eliminaron espacios en blanco al final del archivo (limpieza de Pint).

El accesor arma la URL con `url('storage/'.$this->imagen)`, de modo que responde con el host desde el que se está consumiendo la API y no con un valor fijo de `APP_URL`.

`app/Models/CategoriaAlimento.php`:

- Se agregó el cast de `estado` a booleano para que el JSON devuelva `true` o `false` de forma consistente.

### 4.6 Almacenamiento público

Se ejecutó una sola vez:

```bash
php artisan storage:link
```

Esto crea el enlace `public/storage` hacia `storage/app/public`, necesario para que `imagen_url` sea accesible desde el navegador o Postman.

El enlace no se versiona; cada integrante debe ejecutar el comando después de clonar el proyecto.

### 4.7 Colección Postman

Ubicación:

```text
postman/collections/actividad-4.2-api-v1/
├── .resources/definition.yaml
├── Categorias/          (8 requests)
└── Alimentos/           (13 requests)
```

Variables de colección:

| Variable | Uso |
| --- | --- |
| `baseUrlV1` | `http://127.0.0.1:8000/api/v1` |
| `categoriaId` | Id creado por la prueba de categorías |
| `categoriaAlimentoId` | Categoría de apoyo para las pruebas de alimentos |
| `alimentoId` | Id creado por la prueba de alimentos |

La colección crea sus propios registros y los elimina al final, por lo que se puede ejecutar varias veces sin tocar los datos existentes. Las bajas son lógicas, igual que en el resto del proyecto, por lo que los registros quedan en la papelera de la base de datos.

Los archivos de prueba `imagen.png` y `no-imagen.txt` viven en `Alimentos/` y se referencian con ruta relativa a la raíz del repositorio, por eso el comando debe ejecutarse desde la raíz.

---

## 5. Qué se eliminó

No se eliminó funcionalidad.

- No se borraron rutas, controladores, modelos, migraciones ni archivos de la entrega anterior.
- No se eliminaron columnas ni datos.
- Únicamente se quitaron espacios en blanco al final de `app/Models/Alimento.php` como parte del formato de Pint.

---

## 6. Cómo probar en Postman

### 6.1 Preparación

```bash
php artisan migrate
php artisan storage:link
php artisan serve
```

Servidor esperado: `http://127.0.0.1:8000`.

Si el proyecto ya estaba corriendo, limpiar la caché de rutas:

```bash
php artisan route:clear
php artisan config:clear
```

### 6.2 Ejecución automática de la colección

Con Postman CLI instalado:

```bash
postman collection lint postman/collections/actividad-4.2-api-v1
postman collection run postman/collections/actividad-4.2-api-v1
```

Resultado obtenido en la verificación:

```text
requests:          21 ejecutados / 0 fallidos
test-scripts:      21 ejecutados / 0 fallidos
assertions:        84 ejecutadas / 0 fallidas
lint:              No issues found (21 requests)
```

### 6.3 Pruebas manuales mínimas

#### Consultar la colección de categorías

```http
GET http://127.0.0.1:8000/api/v1/categorias?por_pagina=10
Accept: application/json
```

Respuesta esperada: `200 OK` con `success: true` y `data.data` como arreglo.

#### Consultar una categoría existente

```http
GET http://127.0.0.1:8000/api/v1/categorias/1
Accept: application/json
```

#### Consultar una categoría inexistente

```http
GET http://127.0.0.1:8000/api/v1/categorias/999999
Accept: application/json
```

Respuesta:

```json
{
  "success": false,
  "message": "La categoría solicitada no existe",
  "data": null,
  "errors": null
}
```

Código: `404 Not Found`.

#### Crear una categoría válida

```http
POST http://127.0.0.1:8000/api/v1/categorias
Content-Type: application/json
Accept: application/json
```

```json
{
  "nombre": "Categoría de prueba",
  "descripcion": "Creada desde Postman",
  "estado": 1
}
```

Respuesta: `201 Created` con el registro creado en `data`.

#### Crear una categoría inválida

Enviar `nombre` vacío o `estado` con texto. Respuesta: `422 Unprocessable Content`.

```json
{
  "success": false,
  "message": "Los datos enviados no son válidos",
  "data": null,
  "errors": {
    "nombre": ["El nombre de la categoría es obligatorio."],
    "estado": ["El estado de la categoría debe ser verdadero o falso."]
  }
}
```

#### Actualizar con PUT

```http
PUT http://127.0.0.1:8000/api/v1/categorias/{id}
```

Enviar `nombre`, `descripcion` y `estado`. Respuesta: `200 OK`.

#### Actualizar con PATCH

```http
PATCH http://127.0.0.1:8000/api/v1/categorias/{id}
```

Enviar solo un campo, por ejemplo `descripcion`. Los demás conservan su valor. Respuesta: `200 OK`.

#### Eliminar una categoría

```http
DELETE http://127.0.0.1:8000/api/v1/categorias/{id}
```

Respuesta: `200 OK` con `data: null`.

#### Eliminar una categoría inexistente

Repetir el DELETE con el mismo id. Respuesta: `404 Not Found`.

#### Crear un alimento con imagen

En Postman, pestaña **Body → form-data**:

| Key | Tipo | Valor |
| --- | --- | --- |
| `id_categoria` | Text | Id de una categoría existente |
| `nombre` | Text | Alimento de prueba |
| `descripcion` | Text | Creado desde Postman |
| `estado` | Text | `1` |
| `imagen` | File | Una imagen jpg, jpeg, png o webp menor a 2 MB |

```http
POST http://127.0.0.1:8000/api/v1/alimentos
Accept: application/json
```

Respuesta: `201 Created`.

#### Consultar el alimento con su imagen

```http
GET http://127.0.0.1:8000/api/v1/alimentos/{id}
```

La respuesta incluye:

```json
{
  "imagen": "alimentos/nombre-aleatorio.png",
  "imagen_url": "http://127.0.0.1:8000/storage/alimentos/nombre-aleatorio.png"
}
```

#### Reemplazar la imagen

```http
POST http://127.0.0.1:8000/api/v1/alimentos/{id}/imagen
Body: form-data, key imagen, tipo File
```

Respuesta: `200 OK`. El archivo anterior se elimina del disco.

#### Enviar un archivo que no es imagen

Repetir la petición anterior con un archivo `.txt`. Respuesta: `422` con `errors.imagen`.

#### Eliminar la imagen

```http
DELETE http://127.0.0.1:8000/api/v1/alimentos/{id}/imagen
```

Respuesta: `200 OK`. Si el alimento no tiene imagen: `404`.

#### Eliminar el alimento

```http
DELETE http://127.0.0.1:8000/api/v1/alimentos/{id}
```

Respuesta: `200 OK`. El registro queda con borrado lógico y su imagen se elimina del disco.

#### Método no permitido

```http
DELETE http://127.0.0.1:8000/api/v1/categorias
```

Respuesta: `405 Method Not Allowed` con el JSON estándar.

#### Archivo demasiado grande

Subir un archivo mayor al límite de `post_max_size` de PHP. Respuesta: `413` con el JSON estándar.

El aviso de PHP que aparece antes del JSON solo se muestra si `display_errors` está encendido en el entorno local; en producción debe estar apagado.

---

## 7. Códigos HTTP cubiertos

| Código | Situación |
| --- | --- |
| 200 | Consulta, actualización o eliminación correcta |
| 201 | Recurso creado |
| 400 | Solicitud incorrecta |
| 401 | Falta autenticación (reservado para la Actividad 4.1) |
| 403 | Sin autorización (reservado para la Actividad 4.1) |
| 404 | Recurso inexistente o ruta no encontrada |
| 405 | Método no permitido |
| 413 | Archivo o solicitud demasiado grande |
| 415 | Tipo de contenido no admitido |
| 422 | Datos inválidos |
| 429 | Demasiadas solicitudes |
| 500 | Error interno, sin exponer detalles |

Los códigos 401 y 403 ya tienen respuesta JSON preparada en el manejador, aunque esta actividad no emite tokens ni restringe permisos.

---

## 8. Commits realizados

| Commit | Mensaje |
| --- | --- |
| `22a238d` | `feat: estandarizar errores json de la api v1` |
| `2899fde` | `feat: agregar url publica de imagen y tipos a los modelos` |
| `e5d26f3` | `feat: crear controladores de categorias y alimentos de la api v1` |
| `a472823` | `feat: registrar rutas de la api v1` |
| `e6ec51f` | `test: agregar coleccion postman de la api v1` |
| `64f1d3b` | `fix: cubrir la raiz de la api v1 en el manejo json` |
| Este commit | `docs: agregar guia de cambios de la actividad 4.2` |

---

## 9. Pendientes para completar la Actividad 4.1

1. Implementar `POST /api/v1/admin/login` y `POST /api/v1/cliente/login`.
2. Configurar Sanctum para los modelos `Administrador` y `Usuario`.
3. Crear los grupos `/api/v1/admin` y `/api/v1/cliente` con su middleware.
4. Definir Gates o Policies para las operaciones administrativas.
5. Restringir las solicitudes y donaciones de cada cliente a su propia identidad.
6. Documentar los permisos por rol en la guía de la Actividad 4.1.
7. Agregar pruebas de los escenarios 401, 403 y acceso a registros de otros clientes.

---

## 10. Notas y solución de problemas

- **La imagen no abre:** ejecutar `php artisan storage:link` y verificar que la URL use el mismo host y puerto del servidor.
- **Error 419 o redirección al login:** se está usando una ruta web o falta el encabezado `Accept: application/json`. Las rutas de esta actividad no usan sesión.
- **Error 405 en la colección:** ejecutar los requests en el orden definido; los de actualización y borrado dependen de los ids creados antes.
- **Error de archivo no encontrado en Postman:** ejecutar la colección desde la raíz del repositorio, porque los archivos de prueba se referencian con ruta relativa.
- **Cambios de rutas no reflejados:** ejecutar `php artisan route:clear`.
- **Imagen mayor a 2 MB:** Laravel responde 422 por la regla `max:2048`. Si el archivo supera `post_max_size` o `upload_max_filesize` de PHP, la respuesta es 413 antes del controlador.
- **Reemplazo de imagen con PUT:** PHP no procesa archivos en solicitudes PUT. Para reemplazar una imagen usar siempre `POST /api/v1/alimentos/{id}/imagen`.
