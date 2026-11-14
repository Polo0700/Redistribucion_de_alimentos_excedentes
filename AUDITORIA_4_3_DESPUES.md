# Auditoría Actividad 4.3 — Estado DESPUÉS de la fusión

**Fecha:** 2026-11-13
**Rama:** `sanctum` @ `5662b79` + fusión selectiva de `bfaa316`
**Carpeta:** `C:\Users\Usuario1\apiv2\Redistribucion_de_alimentos_excedentes`

## Requisitos del enunciado (Moodle Actividad 4.3)

| # | Requisito | ANTES | DESPUÉS | Cambio |
|---|---|---|---|---|
| 1 | Prefijo `/api/v1/admin/` | ✅ | ✅ | — |
| 1 | `POST /api/v1/admin/login` + `createToken('admin-token')` | ✅ | ✅ | — |
| 1 | `POST /api/v1/admin/logout` + `currentAccessToken()->delete()` | ✅ | ✅ | — |
| 1 | Middleware `auth:sanctum` en grupo admin | ✅ | ✅ | — |
| 2 | Mecanismo de autorización (Opción C) | ✅ `EnsureEsAdmin` | ✅ `EnsureEsAdmin` | — |
| 3 | `GET/POST/PUT/DELETE /api/v1/admin/recursos` | ✅ | ✅ | — |
| 3 | `GET /api/v1/admin/usuarios` | ✅ | ✅ | — |
| 3 | **`PATCH /api/v1/admin/usuarios/{id}/estado`** | ❌ | ✅ | **NUEVO** `UsuarioController@cambiarEstado` |
| 4 | **JsonResource** | ⚠️ 1 | ✅ **9** | +8 del compañero |
| 4 | Códigos HTTP 200/201/401/403/404/422 | ✅ | ✅ | — |
| 5 | **FormRequest** | ⚠️ 1 | ✅ **10** | +9 del compañero |
| 6 | Colección Postman | ✅ | ✅ | — |

## API pura (lo que traía tu compañero)

| Cambio | Estado |
|---|---|
| Métodos `create()`/`edit()` eliminados de controladores admin | ✅ (9 controladores reemplazados) |
| Rutas `GET .../create` y `GET .../{id}/edit` eliminadas | ✅ (verificado con `route:list`) |
| Rutas compactas por recurso | ✅ manteniendo tu prefijo `v1/admin` |

## ✅ Conservado de ti (4.3)

- Prefijo `Route::prefix('v1/admin')` — tu amigo NO lo tiene
- `AuthAdminController` con `createToken('admin-token')` — tu amigo usa `api-token`
- Middleware **`EnsureEsAdmin`** (mecanismo de autorización, 40 pts de la rúbrica) — tu amigo NO tiene ninguno
- Grupo `Route::view('/dashboard', ...)`

## ✅ Intacto de tu compañero (4.4) — NO tocado

- 11 rutas `api/v1/cliente/...` verificadas con `route:list`
- `AuthClienteController`, `ClienteDonacionController`, `ClienteSolicitudController`
- `EnsureEsCliente`
- Vistas `resources/views/cliente/login.blade.php` y `panel.blade.php`

## Pruebas

| | ANTES | DESPUÉS |
|---|---|---|
| **Passed** | 42 | **42** |
| **Failed** | 18 | **18** |

- Ningún test nuevo roto. Se corrigió 1 que esperaba la estructura vieja
  (`['administradores' => ['data']]` → `['data']`, forma correcta de JsonResource).
- `AdminAuthTest` (login/logout admin): **PASA** ✅
- `AdministradorCrudTest` (CRUD admin): **PASA** ✅
- Los 18 failures son **preexistentes** y son de capa web: `ReplicaCrudTest` (13),
  `AlimentoCrudTest` (4), `ExampleTest` (1) — esperan vistas HTML/redirect.

## Pendiente de decisión

- Esos 18 tests de capa web esperan vistas. Tu compañero los **borró** en su refactor
  ("eliminacion de pruebas obsoletas de la capa web"). ¿Los borramos también o los
  adaptamos a JSON?
