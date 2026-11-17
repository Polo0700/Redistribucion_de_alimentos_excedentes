# Cambios 4.4 — Módulo del Cliente y Autorización de Datos Propios (Client API)

Documento de replicación de la Actividad 4.4: indica **qué archivo se creó, qué
archivo se modificó y en qué parte**, sobre el estado que dejó la Actividad 4.3.
No es un resumen: con este documento y los commits listados se puede reconstruir
la actividad completa.

---

## 0. Datos de la implementación

| Dato | Valor |
| --- | --- |
| Estado inicial | Commit `85e5003` (fin de la 4.3: módulo admin con Sanctum) |
| Rama de trabajo | `sanctum` |
| Framework | Laravel 12 (PHP 8.2+) |
| Autenticación | Laravel Sanctum (Bearer Tokens), modelo `CuentaAcceso` para clientes |
| Autorización | Policies de propiedad de datos (`SolicitudPolicy`, `DonacionPolicy`) + Gate `gestionar-registro-propio` |
| Prefijo de la API | `/api/v1/cliente` |
| Resultado de pruebas | 55 tests pasando (44 previos + 11 nuevos), 202 aserciones |

### 0.1 Commits en orden de aplicación

| # | Commit | Contenido |
| --- | --- | --- |
| 1 | `6d3cb2b` | Grupo único `auth:sanctum` con logins públicos en `routes/api.php` |
| 2 | `20d7c26` | Registro de clientes con rol automático y token Sanctum |
| 3 | `4bc6a30` | Policies de propiedad de datos para solicitudes y donaciones |
| 4 | `693a6dc` | FormRequests y Resources de datos propios del cliente |
| 5 | `629aa14` | Endpoints de registros propios, catálogo público y Resources |
| 6 | `8be477c` | Pruebas automáticas de auth y propiedad de datos |
| 7 | `407c36e` | Carpeta Postman `Cliente` con los escenarios manuales (401/403/422) |

---

## 1. Mapa exacto de cambios

| # | Acción | Archivo | Qué se hizo |
| --- | --- | --- | --- |
| 1 | MODIFICAR | `routes/api.php` | Reorganización completa del routing (ver §2) |
| 2 | CREAR | `app/Http/Requests/ClienteRegistroRequest.php` | Validación del registro público |
| 3 | CREAR | `app/Http/Requests/ClienteSolicitudRequest.php` | Validación de creación de solicitudes propias |
| 4 | CREAR | `app/Http/Requests/ClienteDonacionRequest.php` | Validación de creación de donaciones propias |
| 5 | CREAR | `app/Http/Requests/ClientePerfilRequest.php` | Validación de actualización de perfil |
| 6 | CREAR | `app/Policies/SolicitudPolicy.php` | Propiedad de datos sobre solicitudes |
| 7 | CREAR | `app/Policies/DonacionPolicy.php` | Propiedad de datos sobre donaciones |
| 8 | MODIFICAR | `app/Providers/AppServiceProvider.php` | Registro de policies con `Gate::policy` + Gate global |
| 9 | MODIFICAR | `app/Http/Controllers/Controller.php` | Trait `AuthorizesRequests` para `$this->authorize()` |
| 10 | CREAR | `app/Http/Resources/DetalleSolicitudResource.php` | Resource de los detalles de solicitud |
| 11 | CREAR | `app/Http/Resources/DetalleDonacionResource.php` | Resource de los detalles de donación |
| 12 | MODIFICAR | `app/Http/Resources/SolicitudResource.php` | Agrega clave `detalles` |
| 13 | MODIFICAR | `app/Http/Resources/DonacionResource.php` | Agrega clave `detalles` |
| 14 | MODIFICAR | `app/Http/Controllers/AuthClienteController.php` | Registro + perfil con FormRequest/Resource + token `client-token` |
| 15 | MODIFICAR | `app/Http/Controllers/ClienteSolicitudController.php` | CRUD de registros propios con Policy |
| 16 | MODIFICAR | `app/Http/Controllers/ClienteDonacionController.php` | Donaciones propias con FormRequest/Resource |
| 17 | CREAR | `app/Http/Controllers/ClienteCatalogoController.php` | Catálogo público sin token |
| 18 | CREAR | `tests/Feature/ClientePropiedadDatosTest.php` | 11 pruebas automáticas (ver §5) |
| 19 | CREAR | `postman/collections/redistribucion-alimentos-api/Cliente/` | 19 requests Postman (ver §6) |
| 20 | MODIFICAR | `postman/collections/redistribucion-alimentos-api/.resources/definition.yaml` | Variables `tokenClienteA/B`, `idUsuarioA`, `idSolicitudA`, `idAlimentoCatalogo`, `baseUrlCliente` |
| 21 | MODIFICAR | `postman/README.md` | Nueva sección §11 con el flujo de la carpeta `Cliente` |
| 22 | CREAR | `CAMBIOS_4.4.md` | Este documento |

### 1.1 Lo que NO se tocó

- Módulo administrativo (controladores, rutas y tests de la 4.3): intacto.
- Los 18 requests obsoletos `create`/`edit` de la colección Postman: intactos
  (documentado en `postman/README.md` §10 y `AGENTS.md`).
- Vistas Blade `resources/views/cliente/login.blade.php` y `panel.blade.php`:
  intactas (los tests previos siguen pasando).
- Tests preexistentes (`ClienteAuthTest`, `ClientePedidosTest`, `AdminAuthTest`,
  `ApiRestTest`, `AdministradorCrudTest`): intactos, siguen en verde.
- Migraciones: **no se creó ninguna**; la 4.4 usa las tablas existentes
  (`usuarios`, `cuentas_acceso`, `solicitudes`, `detalle_solicitud`,
  `donaciones`, `detalle_donacion`, `personal_access_tokens`).

---

## 2. Reorganización del routing (`routes/api.php`)

Antes de la 4.4 había dos grupos independientes, cada uno con su propio
`auth:sanctum` repartido a nivel de subgrupo y las rutas de login sueltas dentro
del grupo `v1/admin`. Ahora la estructura es:

```php
// BLOQUE PÚBLICO (sin token): SOLO autenticación
Route::prefix('v1')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/login', ...);   // 405 informativo
        Route::post('/login', ...);  // emite admin-token
    });
    Route::prefix('cliente')->group(function () {
        Route::get('/login', ...);       // 405 informativo
        Route::post('/login', ...);      // emite client-token
        Route::post('/register', ...);   // NUEVO: registro público
        Route::get('/catalogo', ...);    // NUEVO: catálogo público
        Route::get('/catalogo/{id}', ...);
    });
});

// GRUPO ÚNICO PROTEGIDO: auth:sanctum
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::prefix('admin')->middleware(EnsureEsAdmin::class)->group(function () {
        Route::post('/logout', ...);
        // ... todas las rutas CRUD del área administrativa (9 recursos)
    });
    Route::prefix('cliente')->middleware(EnsureEsCliente::class)->group(function () {
        Route::post('/logout', ...);
        Route::get('/perfil', ...);
        Route::patch('/perfil', ...);
        Route::get('/alimentos', ...);
        Route::get('/alimentos/{id}', ...);
        Route::get('/solicitudes', ...);
        Route::post('/solicitudes', ...);
        Route::get('/donaciones', ...);
        Route::post('/donaciones', ...);
        Route::get('/mis-registros', ...);        // NUEVO
        Route::post('/mis-registros', ...);       // NUEVO
        Route::get('/mis-registros/{id}', ...);   // NUEVO (Policy view)
        Route::delete('/mis-registros/{id}', ...);// NUEVO (Policy cancelar)
    });
});
```

Sin token válido, **todas** las rutas del segundo bloque responden `401`.
Con token de cliente, `EnsureEsAdmin` responde `403` en el área admin y viceversa.

---

## 3. Autenticación de clientes (Sanctum)

### 3.1 Registro — `POST /api/v1/cliente/register` (público)

Nuevo método `AuthClienteController::registrar()` con `ClienteRegistroRequest`:

- Valida `nombre`, `apellido`, `correo` (único en `usuarios`), `telefono`,
  `direccion` y `password` (mínimo 8).
- Asigna el rol **Cliente** en el backend con `Rol::firstOrCreate(['nombre' => 'Cliente'])`;
  nunca se acepta un rol desde el payload.
- Crea `Usuario` + `CuentaAcceso` (proveedor `Correo`, `contrasena_hash` con `Hash::make`).
- Emite el token con `createToken('client-token')` y responde `201` con
  `{message, usuario{id, nombre, apellido, correo, rol}, token}`.

### 3.2 Login y logout

- `POST /api/v1/cliente/login`: existente; se cambió el nombre del token de
  `api-cliente` a **`client-token`** (el nombre que pide el enunciado).
- `POST /api/v1/cliente/logout`: existente; revoca con
  `currentAccessToken()->delete()`.

### 3.3 Modelo User y `HasApiTokens`

Ya estaba resuelto desde la 4.3 (el compañero lo dejó hecho) y **no requirió
cambios**: `App\Models\User` importa y usa `Laravel\Sanctum\HasApiTokens`.
En este proyecto los clientes autentican contra el modelo `CuentaAcceso`,
que también usa `HasApiTokens` (línea 12 de `app/Models/CuentaAcceso.php`).

---

## 4. Autorización: propiedad de datos (Policies)

Creadas `app/Policies/SolicitudPolicy.php` y `app/Policies/DonacionPolicy.php`,
registradas en `AppServiceProvider::boot()`:

```php
Gate::policy(Solicitud::class, SolicitudPolicy::class);
Gate::policy(Donacion::class, DonacionPolicy::class);
Gate::define('gestionar-registro-propio', function ($cuenta, $recurso) {
    return $cuenta->id_usuario === $recurso->id_usuario;
});
```

Reglas de cada policy (el autenticado es `CuentaAcceso`, que expone `id_usuario`):

| Método | Regla | Uso |
| --- | --- | --- |
| `view` | `$recurso->id_usuario === $cuenta->id_usuario` | `GET /mis-registros/{id}` |
| `update` | ídem | actualizaciones futuras del recurso |
| `cancelar` | dueño **y** estado cancelable (`pendiente` en Solicitud / `Disponible` en Donacion) | `DELETE /mis-registros/{id}` |
| `delete` | alias de `cancelar` | usos genéricos de borrado |

Cuando la policy falla, Laravel lanza `AuthorizationException` y el renderer de
`bootstrap/app.php` (existente desde la 4.3) la convierte en **403 JSON**
estandarizado. Con `findOrFail` inexistente responde **404**.

Además, `Controller.php` ahora usa `Illuminate\Foundation\Auth\Access\AuthorizesRequests`
para habilitar `$this->authorize('view', $solicitud)`.

### Doble capa anti-manipulación del `id_usuario`

1. **Asignación automática**: los `store` de solicitudes/donaciones ignoran el
   payload y usan `$cuenta->id_usuario` del token (equivalente a `auth()->id()`
   en este esquema de cuentas). El cliente no puede crear registros "a nombre
   de otro".
2. **Filtro en la consulta**: todos los `index` filtran en la base de datos con
   `where('id_usuario', $cuenta->id_usuario)`, nunca en PHP.

---

## 5. Endpoints nuevos y respuestas estandarizadas

| Endpoint | Auth | Controller | Respuestas |
| --- | --- | --- | --- |
| `POST /api/v1/cliente/register` | Pública | `AuthClienteController@registrar` | `201`, `422` |
| `GET /api/v1/cliente/catalogo` | Pública | `ClienteCatalogoController@index` | `200` (solo alimentos `estado = 1`, paginado) |
| `GET /api/v1/cliente/catalogo/{id}` | Pública | `ClienteCatalogoController@show` | `200`, `404` |
| `GET /api/v1/cliente/mis-registros` | `auth:sanctum` | `ClienteSolicitudController@index` | `200` (solo propios) |
| `POST /api/v1/cliente/mis-registros` | `auth:sanctum` | `ClienteSolicitudController@store` | `201`, `422` |
| `GET /api/v1/cliente/mis-registros/{id}` | `auth:sanctum` | `ClienteSolicitudController@show` | `200`, `403`, `404` |
| `DELETE /api/v1/cliente/mis-registros/{id}` | `auth:sanctum` | `ClienteSolicitudController@destroy` | `200`, `403`, `404` |

Cambios en endpoints existentes (misma ruta, mejor formato):

- `GET/PATCH /perfil`: ahora usan `ClientePerfilRequest` y devuelven
  `UsuarioResource` en vez del modelo crudo (oculta campos internos; el correo
  no es actualizable desde la API del cliente).
- `GET /solicitudes` y `GET /donaciones`: ahora devuelven
  `SolicitudResource::collection(...)` / `DonacionResource::collection(...)`
  con `detalles.alimento` incluidos.
- `POST /solicitudes` y `POST /donaciones`: migraron de `$request->validate()`
  inline a los FormRequests `ClienteSolicitudRequest` / `ClienteDonacionRequest`
  y devuelven el Resource correspondiente.
- Token de sesión renombrado a `client-token` en el login.

Códigos HTTP cubiertos: `200`/`201`, `401` (sin/expirado), `403` (policy),
`404` (`findOrFail`), `422` (FormRequests).

---

## 6. Pruebas automáticas (`tests/Feature/ClientePropiedadDatosTest.php`)

11 pruebas, todas contra SQLite en memoria (`RefreshDatabase`):

| # | Prueba | Valida |
| --- | --- | --- |
| 1 | `registro_publico_crea_cliente_con_rol_y_token` | 201, rol `Cliente`, token funcional sobre ruta protegida |
| 2 | `login_devuelve_un_token_de_sanctum_valido` | 200, formato `id|hash` con regex, 1 fila en `personal_access_tokens` |
| 3 | `rutas_protegidas_devuelven_401_sin_token` | 401 en perfil (GET/PATCH), mis-registros (GET/POST/{id}/DELETE), solicitudes, donaciones y logout |
| 4 | `rutas_protegidas_con_token_devuelven_200` | 200/201 con Bearer en perfil, listado, creación y donaciones |
| 5 | `catalogo_publico_no_requiere_token` | 200 en catálogo y detalle sin token |
| 6 | `cliente_b_no_puede_ver_el_registro_de_cliente_a` | **403** (Policy `view`) |
| 7 | `cliente_b_no_puede_cancelar_el_registro_de_cliente_a` | **403** (Policy `cancelar`), el registro queda `pendiente` |
| 8 | `cliente_a_puede_ver_y_cancelar_su_registro` | 200 en detalle y cancelación; estado final `cancelada` |
| 9 | `no_se_puede_cancelar_un_registro_no_cancelable` | 403 cuando el estado ya no es `pendiente` |
| 10 | `la_lista_mis_registros_solo_muestra_lo_propio` | el listado de B está vacío; el de A trae exactamente su registro |
| 11 | `logout_revoca_el_token_y_deja_de_autorizar` | 200 al cerrar sesión y **401** al reintentar con el token revocado |

Ejecución:

```bash
php artisan test                                  # suite completa: 55 passed
php artisan test tests/Feature/ClientePropiedadDatosTest.php   # solo la 4.4
```

---

## 7. Colección Postman (`postman/...`)

Carpeta nueva `postman/collections/redistribucion-alimentos-api/Cliente/` con
19 requests que replican los escenarios manuales del enunciado, en orden de
ejecución:

| # | Request | Esperado |
| --- | --- | --- |
| 1-2 | `Registro cliente A` / `Registro cliente B` | `201`, guardan `tokenClienteA`/`tokenClienteB` |
| 3-4 | `Login cliente A` / `Login cliente B` | `200`, guardan tokens (alternativa si ya existen) |
| 5-6 | `Catalogo publico` / `Detalle del catalogo` | `200` sin token; guarda `idAlimentoCatalogo` |
| 7-8 | `Perfil del cliente A` / `Actualizar perfil` | `200`; verifica que no se exponga `contrasena_hash` |
| 9 | `Crear registro propio (cliente A)` | `201`, guarda `idSolicitudA` |
| 10-11 | `Mis registros` / `Mis donaciones` | `200`; verifica que cada fila pertenece a `idUsuarioA` |
| 12 | `Detalle de registro propio` | `200` |
| 13-14 | `Registro de A con token de B` / `Cancelar registro de A con token de B` | **`403` Forbidden (Policy)** |
| 15 | `Crear registro sin datos` | `422` con `errors` por campo |
| 16 | `Cancelar registro propio` | `200` con estado `cancelada` |
| 17 | `Re-cancelar registro ya cancelado` | `403` (estado no cancelable) |
| 18 | `Ruta protegida SIN token` | `401` |
| 19 | `Logout cliente A` | `200` y reintento con el token revocado → `401` |

Variables nuevas en la definición de la colección: `baseUrlCliente`,
`tokenClienteA`, `tokenClienteB`, `idUsuarioA`, `idSolicitudA`,
`idAlimentoCatalogo`. La carpeta hereda Bearer `tokenClienteA`; los requests
públicos usan `noauth` y los de intrusión sobreescriben con `tokenClienteB`.

Lint verificado: `postman collection lint postman/collections/redistribucion-alimentos-api`
→ `No issues found` (114 elementos escaneados). Documentación del flujo añadida
como sección §11 de `postman/README.md`.

---

## 8. Notas de diseño

1. **"Mis registros" = solicitudes.** En este dominio el registro que un cliente
   crea es una solicitud de alimento (tabla `solicitudes`). El endpoint
   `mis-registros` opera sobre ella; las donaciones propias se consultan en
   `/donaciones` y comparten las mismas garantías de propiedad.
2. **Cancelación lógica, no borrado físico.** `DELETE /mis-registros/{id}` cambia
   el estado a `cancelada` (y cancela sus detalles) en lugar de eliminar la fila:
   conserva la trazabilidad y hace posible demostrar el 403 de estado no
   cancelable. El enunciado admite "cancela o elimina".
3. **Estados cancelables por recurso**: `pendiente` para solicitudes y
   `Disponible` para donaciones (valor con el que se crean).
4. **`CuentaAcceso` como identidad del cliente.** El guard de clientes usa el
   modelo `CuentaAcceso` (tabla `cuentas_acceso`), que delega en `Usuario` los
   datos personales. Por eso la comparación de propiedad es
   `$cuenta->id_usuario === $recurso->id_usuario` y no `$user->id` directo.
5. **Token `client-token`** unificado en registro y login (antes el login usaba
   `api-cliente`).
