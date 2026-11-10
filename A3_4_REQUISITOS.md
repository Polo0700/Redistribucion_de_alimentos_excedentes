# A3_4 — Requisitos y estado completo

**Actividad:** Diseño y Configuración de una API RESTful y sus endpoints para las áreas administrativa y cliente en Laravel
**Entrega:** PDF `EquipoN_A3_4_API_RESTful`
**Proyecto:** Redistribución de alimentos excedentes (Laravel 12)

---

## 1. Identificación de la actividad

| Campo | Valor |
|---|---|
| Tipo | Actividad colaborativa en equipo |
| Ponderación | Diseño/catálogo **40 pts** · Implementación/auth/autorización **40 pts** · Entrega **20 pts** |
| Continúa | Actividades anteriores (4.2: JSON estandarizado, Validator, find(), imágenes — ya hecha) |

---

## 2. Recursos principales del proyecto (mínimo 3 requeridos)

### 2.1 Alimento
- **Tabla:** `alimentos` · **PK:** `id_alimento` · SoftDeletes
- **Atributos:** `id_categoria`, `nombre`, `descripcion`, `estado`, `imagen`
- **Relaciones:** `categoria()` → CategoriaAlimento (belongsTo), `detallesDonacion()` → DetalleDonacion (hasMany)
- **Operaciones:** consulta (colección/individual), creación, actualización, eliminación, eliminación de imágenes

### 2.2 Solicitud (cliente pide alimentos)
- **Tabla:** `solicitudes` · **PK:** `id_solicitud` · SoftDeletes
- **Atributos:** `id_usuario`, `fecha_solicitud`, `estado`, `direccion_entrega`, `observaciones`
- **Relaciones:** `usuario()` → Usuario, `detalles()` → DetalleSolicitud (hasMany), `entrega()` → Entrega (hasOne)
- **Operaciones:** el cliente crea/consulta/cancela las suyas; el admin gestiona todas

### 2.3 Donación (cliente/empresa dona alimentos)
- **Tabla:** `donaciones` · **PK:** `id_donacion` · SoftDeletes
- **Atributos:** `id_usuario`, `fecha_donacion`, `fecha_limite`, `ubicacion`, `estado`, `observaciones`
- **Relaciones:** `usuario()` → Usuario, `detalles()` → DetalleDonacion (hasMany)
- **Operaciones:** el cliente crea/consulta/cancela las suyas; el admin gestiona todas

### 2.4 Recursos de apoyo
| Reccurso | Tabla/PK | Atributos clave | Relaciones |
|---|---|---|---|
| Categoría Alimento | `categorias_alimentos` / `id_categoria` | nombre, descripcion, estado | hasMany alimentos |
| Usuario (cliente) | `usuarios` / `id` | id_rol, nombre, apellido, correo, telefono, direccion, fecha_registro, estado | rol(), cuentasAcceso(), donaciones(), solicitudes |
| CuentaAcceso | `cuentas_acceso` / `id_cuenta` | id_usuario, proveedor, identificador_externo, contrasena_hash, fecha_ultimo_acceso | usuario() |
| Entrega | `entregas` | estado, fechas | solicitud() |
| Administrador | `administradores` / `id_admin` | username, password, nivel (1-3), estado | HasApiTokens |
| Rol | `roles` / `id` | nombre, descripcion | — |

---

## 3. Convención REST (verificada en `routes/api.php`)

- ✅ URLs en plural, sustantivos: `/api/v1/admin/alimentos`
- ✅ Versionado: `v1`
- ✅ Separación de áreas por prefijo: `v1/admin` (implementado), `v1/cliente` (propuesto)
- ✅ Métodos: GET consulta, POST crea, PUT reemplaza, PATCH actualiza, DELETE elimina
- ✅ Rutas API separadas de rutas web (`routes/api.php` vs `routes/web.php`)
- ⚠️ Quedan rutas heredadas de la era web dentro del grupo API: `GET /login`, `GET {recurso}/create`, `GET {recurso}/{id}/edit`, `GET /dashboard` (Route::view) — **no son REST**; decidir si se documentan como excepción o se retiran

---

## 4. Estado del área ADMINISTRATIVA

### 4.1 Autenticación ✅ implementada y probada (9/9 en vivo)
| Endpoint | Método | Estado |
|---|---|---|
| `/api/v1/admin/login` | POST | ✅ 200 + token Bearer · 401 credenciales malas · 403 inactiva · 422 vacío · 405 en GET |
| `/api/v1/admin/logout` | POST | ✅ 200 + revocación de token (el viejo da 401) |

- Mecanismo: **Sanctum tokens personales** (`Authorization: Bearer {token}`)
- Modelo: `Administrador` con `HasApiTokens`, guard `admin`
- Contraseñas con hash (nunca en texto plano ni en respuestas)

### 4.2 CRUD implementado bajo `auth:sanctum` (10 recursos)
`administradores`, `roles`, `usuarios`, `cuentas-acceso`, `categorias`, `alimentos`, `donaciones`, `solicitudes`, `entregas`, `acciones-importantes`
Cada uno con: index, store, show, update, destroy + variantes `eliminados`, `restore`, `force`

### 4.3 Pendientes del área admin
- ❌ **Autorización por niveles**: existen niveles 1-2-3 en BD (`NIVELES_PERMITIDOS = [1,2,3]`) pero **no se usa en ninguna ruta**. Definir qué nivel puede crear/editar/borrar.
- ⚠️ **Tipo de usuario en rutas admin**: hoy cualquier token Sanctum pasa `auth:sanctum`. Falta middleware que exija que el token sea de un `Administrador` (no de un cliente).
- ❌ Gates/Policies para operaciones admin.

---

## 5. Estado del área CLIENTE

**No existe nada.** Todo lo siguiente está pendiente:

### 5.1 Autenticación cliente (propuesto)
| Endpoint | Método | Estado |
|---|---|---|
| `/api/v1/cliente/registro` | POST | ❌ pendiente (si el sistema permite autorregistro) |
| `/api/v1/cliente/login` | POST | ❌ pendiente |
| `/api/v1/cliente/logout` | POST | ❌ pendiente |
| `/api/v1/cliente/perfil` | GET | ❌ pendiente |
| `/api/v1/cliente/perfil` | PATCH | ❌ pendiente |

- Identidad candidata: `Usuario` + `CuentaAcceso` (tiene `contrasena_hash` y `getAuthPasswordName()`) — decisión pendiente.
- Sanctum tokens personales, independientes de los admin.

### 5.2 Recursos propios del cliente (propuesto)
| Endpoint | Regla de propiedad |
|---|---|
| `GET /api/v1/cliente/solicitudes` | solo las del `id_usuario` autenticado |
| `GET /api/v1/cliente/solicitudes/{id}` | 404/403 si no es suya |
| `POST /api/v1/cliente/solicitudes` | `id_usuario` se toma del token, **nunca del body** |
| `DELETE /api/v1/cliente/solicitudes/{id}` | solo propia y en estado cancelable |
| `GET /api/v1/cliente/donaciones` | solo las suyas |
| `POST /api/v1/cliente/donaciones` | solo suya |
| `GET /api/v1/cliente/alimentos` (catálogo) | consulta pública/autenticada, solo lectura |

### 5.3 Pendientes de autorización cliente
- ❌ Ownership verificado en servidor (`find` + `where id_usuario = auth id`)
- ❌ Un cliente NO debe poder entrar a `/api/v1/admin/*`
- ❌ Un cliente NO debe ver/modificar datos de otro cliente

---

## 6. Reglas de autorización (a implementar con Gates/Policies/middleware)

| Regla | Mecanismo propuesto | Estado |
|---|---|---|
| Solo administradores autenticados en `/v1/admin` | middleware `auth:sanctum` + verificación de tipo de modelo | ⚠️ parcial |
| Nivel 3: CRUD completo | Gate/Policy por `nivel` | ❌ |
| Nivel 2: solo lectura + edición sin borrar | Gate/Policy | ❌ |
| Nivel 1: solo lectura | Gate/Policy | ❌ |
| Cliente: solo registros propios | Policy (ownership) | ❌ |
| Cliente: sin acceso a rutas admin | middleware separado por guard | ❌ |
| **Nunca confiar en rol/nivel/id enviado desde el cliente** | tomar identidad del token | ✅ en login admin; aplicar en todo |

---

## 7. Validación y respuestas JSON

- ✅ Validación en servidor con `validate()` → 422 con `errors` por campo
- ✅ Recurso inexistente → 404 (`findOrFail`)
- ✅ Códigos usados: 200, 201, 401, 403, 404, 405, 422
- ⚠️ 204 y 400 no se usan aún (204 opcional para DELETE; 400 si aplica)
- ⚠️ 500: verificar que errores internos respondan JSON (no HTML)
- ✅ Paginación en colecciones (`paginate(5)` con enlaces)
- ⚠️ Filtros/ordenamiento: no implementados (opcional)

---

## 8. Los 11 escenarios de prueba requeridos

| # | Escenario | Estado |
|---|---|---|
| 1 | No autenticado accede a endpoint protegido | ✅ 401 (en vivo + test) |
| 2 | Admin inicia sesión y accede a operación autorizada | ✅ 200 + token |
| 3 | Admin ejecuta operación que excede sus permisos | ❌ sin niveles no hay exceso que probar |
| 4 | Cliente inicia sesión y consulta su perfil | ❌ área cliente no existe |
| 5 | Cliente consulta sus propios registros | ❌ |
| 6 | Cliente accede a registro de otro cliente | ❌ |
| 7 | Cliente ejecuta operación exclusiva de admin | ❌ |
| 8 | Solicitud con datos inválidos/incompletos | ✅ 422 |
| 9 | Recurso inexistente | ✅ 404 |
| 10 | Métodos HTTP correctos | ✅ 405 en GET login |
| 11 | JSON y códigos HTTP coherentes | ✅ verificado en vivo |

**Cubiertos: 7/11** — faltan 3, 4, 5, 6, 7 (los del área cliente y niveles admin).
Nota: cuando existan niveles admin, probar **cada uno** con cuentas de prueba.

---

## 9. Catálogo de endpoints (12 campos por operación)

Campos requeridos por endpoint:
1. Nombre/identificador · 2. Área · 3. Método + URL · 4. Descripción · 5. Autenticación · 6. Autorización (rol/nivel) · 7. Parámetros (ruta/consulta) · 8. Encabezados · 9. Body · 10. Ejemplo de éxito · 11. Errores y códigos · 12. Reglas de validación

- ⚠️ `CATALOGO_ENDPOINTS.md`: plantilla + 1 ejemplo (`POST /api/v1/admin/login`)
- ❌ Falta documentar los ~10 recursos admin (~60+ operaciones)
- ❌ Falta el área cliente completa

---

## 10. Publicación (GitHub)

- ❌ Commits pendientes: 6 archivos modificados sin commitear (`routes/api.php`, `AuthAdminController`, `Administrador`, tests, trait)
- ❌ Verificar `.env.example` sin credenciales reales
- ❌ Instrucciones de instalación/configuración (`README` o sección nueva)
- ⚠️ Cuentas de prueba documentadas (admin/admin123, inactiva) — deben ser cuentas de prueba
- ⚠️ 18 tests fallidos preexistentes (aserciones HTML sobre respuestas JSON) — no regresión nuestra, pero conviene arreglarlos

---

## 11. Entrega (PDF + video + evidencias)

- ❌ **PDF** `EquipoN_A3_4_API_RESTful` con:
  - Portada (asignatura, actividad, integrantes en orden alfabético por primer apellido)
  - Diseño y catálogo (recursos + endpoints ambas áreas + ejemplos JSON)
  - Enlaces (GitHub + video público)
  - Anexo de evidencias
- ❌ **Video** ≤ 5 min: consumo de la API, operaciones de ambas áreas, auth, pruebas de acceso permitido/denegado
- ❌ **Evidencias**: capturas de consulta, creación, actualización, eliminación + acceso no autenticado + no autorizado + datos de otro cliente. **Fecha y hora visibles en la barra de tareas**

---

## 12. Resumen de avance por criterio

| Criterio | Pts | Avance estimado |
|---|---|---|
| Diseño RESTful y catálogo | 40 | ~40% (REST ✅, recursos sin documentar, catálogo casi vacío) |
| Implementación, auth y autorización | 40 | ~50% (admin ✅, falta cliente + niveles + policies) |
| Entrega en tiempo y forma | 20 | ~5% (nada entregado aún) |
| **Total** | **100** | **~35-40%** |

---

## 13. Plan de trabajo (orden por puntos en juego)

1. **Área cliente** completa: registro/login/logout/perfil + solicitudes/donaciones propias con ownership
2. **Autorización**: middleware tipo-de-usuario en `/admin`, Gates por nivel admin, Policy de ownership
3. **Tests**: escenarios 3-7 + cuenta de cada nivel admin
4. **Catálogo completo** (12 campos × ambas áreas) → base del PDF
5. **Arreglar 18 tests preexistentes** (HTML → JSON)
6. **Publicación**: commits, .env.example, instrucciones
7. **Evidencias** (capturas con fecha/hora) + **video** ≤5 min + **PDF**
