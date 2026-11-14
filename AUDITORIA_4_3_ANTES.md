# Auditoría Actividad 4.3 — Estado ANTES de la fusión

**Fecha:** 2026-11-13
**Rama:** `sanctum` @ `5662b79` (más 5 archivos sin commitear)
**Carpeta:** `C:\Users\Usuario1\apiv2\Redistribucion_de_alimentos_excedentes`

## Requisitos del enunciado (Moodle Actividad 4.3)

| # | Requisito | Estado | Evidencia |
|---|---|---|---|
| 1 | Prefijo `/api/v1/admin/` | ✅ | `Route::prefix('v1/admin')` en `routes/api.php:25` |
| 1 | `POST /api/v1/admin/login` | ✅ | `AuthAdminController::in()` |
| 1 | `createToken('admin-token')` | ✅ | `AuthAdminController.php:37` |
| 1 | `POST /api/v1/admin/logout` | ✅ | `AuthAdminController::out()` |
| 1 | Revocar `currentAccessToken()->delete()` | ✅ | `AuthAdminController.php:51` |
| 1 | Middleware `auth:sanctum` en grupo admin | ✅ | `routes/api.php:33` |
| 2 | Mecanismo de autorización (1 de 3) | ✅ **Opción C** | `EnsureEsAdmin` middleware → 403 |
| 3 | `GET /api/v1/admin/recursos` (listado) | ✅ | `AdministradorController::index()` (paginate 5) |
| 3 | `GET /api/v1/admin/recursos/{id}` | ✅ | `show()` |
| 3 | `POST /api/v1/admin/recursos` | ✅ | `store()` |
| 3 | `PUT/PATCH /api/v1/admin/recursos/{id}` | ✅ | `update()` |
| 3 | `DELETE /api/v1/admin/recursos/{id}` | ✅ | `destroy()` + `forceDestroy()` |
| 3 | `GET /api/v1/admin/usuarios` | ✅ | `routes/api.php:59` |
| 3 | `PATCH /api/v1/admin/usuarios/{id}/estado` | ❌ **NO EXISTE** | — |
| 4 | JsonResource para respuestas | ⚠️ 1 solo | `AdministradorResource` |
| 4 | Códigos HTTP 200/201/401/403/404/422 | ✅ | `setStatusCode(201)`, 403 en EnsureEsAdmin |
| 5 | FormRequest para validaciones | ⚠️ 1 solo | `AdminResourceRequest` (store/update) |
| 6 | Colección Postman | ✅ | `postman/collections/redistribucion-alimentos-api/` |

## Pendiente para API pura
- Métodos `create()` y `edit()` en controladores (devuelven datos de formulario) → eliminar
- Rutas `GET .../create` y `GET .../{id}/edit` → eliminar
- Mantener intacta la **4.4 (cliente)**: `AuthClienteController`, `EnsureEsCliente`,
  `ClienteDonacionController`, `ClienteSolicitudController`, vistas `resources/views/cliente/*`

## Contadores
- FormRequests: **1** (`AdminResourceRequest`)
- JsonResources: **1** (`AdministradorResource`)
- Middlewares de autorización: **1** (`EnsureEsAdmin`)
- Tests de admin: `AdministradorCrudTest`, `AdminAuthTest`
