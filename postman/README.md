# Guía: colección de pruebas de la API

Colección de Postman para los 11 recursos del proyecto. Vive en el repo
(`postman/collections/`) para que viaje con el código y se revise en los PRs.

| Qué | Dónde |
| --- | --- |
| 110 requests en 11 carpetas | `collections/redistribucion-alimentos-api/` |
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

Si usas XAMPP, arrántalo desde su panel de control antes de esto.

---

## 3. Arrancar el backend

Revisa que `.env` tenga esto (no está versionado, cada quien lo tiene local):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=redistribucion_alimentos
DB_USERNAME=root
DB_PASSWORD=""
```

Luego:

```bash
php artisan serve --port=8000
```

Comprueba que responda:

```bash
postman request GET http://127.0.0.1:8000/api/roles -H "Accept:application/json"
```

Deberías ver un `200 OK` con `{"roles": {...}}`.

---

## 4. CLI de Postman

```bash
npm install -g postman-cli
postman --version
postman login
```

`postman login` abre el navegador. Si prefieres con API key:

```bash
postman login --with-api-key "$POSTMAN_API_KEY"
```

---

## 5. Correr las pruebas

**Lecturas** (55 requests, no tocan datos):

```bash
postman collection run postman/collections/redistribucion-alimentos-api \
  -e postman/environments/local.environment.yaml \
  -i "Roles/Listar roles" \
  -i "Roles/Obtener rol por id" \
  -i "Usuarios/Listar usuarios" \
  -i "Usuarios/Obtener usuario por id"
  # ... repite por recurso
```

**Colección completa** (110 requests):

```bash
postman collection run postman/collections/redistribucion-alimentos-api \
  -e postman/environments/local.environment.yaml
```

> **Qué toca la BD al correr todo:** solo los 11 requests **"Crear"** (store).
> Cada uno inserta un registro de prueba. Los de actualizar, borrar y restaurar
> apuntan a `{{idPrueba}}`, que está vacío a propósito: no hacen nada hasta que
> pongas ahí un id real.

---

## 6. Verlo en la app de Postman

Los archivos locales no aparecen solos en la interfaz. Elige una opción:

- **Abrir el enlace de la colección** (lo pide quien la creó) → menú ⋯ →
  guardarla en tu workspace.
- **Abrir la carpeta del repo como proyecto local** dentro de la app
  (integración Git nativa), que sí lee `postman/collections/`.

---

## 7. Configura tu ambiente

En la app: selecciona el ambiente **Local** arriba a la derecha.

| Variable | Para qué | Valor por defecto |
| --- | --- | --- |
| `baseUrl` | URL del API | `http://127.0.0.1:8000/api` |
| `idPrueba` | Id desechable para escrituras | vacío |

Cambia `baseUrl` si tu servidor corre en otro puerto. El resto de la máquina
(otra PC, otro compañero) necesita su propio Laravel + MySQL: la colección no
trae datos, solo las peticiones.

---

## Dos detalles que parecen bugs y no lo son

1. **Falta el header `Accept: application/json`.** Sin él, Laravel devuelve los
   errores (404, 422, 500) como **HTML** en vez de JSON. La colección lo añade
   automáticamente con un script a nivel colección; si copias un request suelto
   a otra colección, agrégalo a mano.
2. **404 en recursos con la tabla vacía.** `show` y `edit` usan ids de ejemplo.
   Si la tabla no tiene registros, responder 404 con `{"message": ...}` es
   correcto. Las aserciones aceptan ambos casos.

---

## Solución de problemas

| Síntoma | Causa probable |
| --- | --- |
| `ECONNREFUSED` en todos los requests | No está corriendo `php artisan serve` |
| `SQLSTATE[HY000] ... refused` | MySQL apagado o en otro puerto que 3306 |
| Respuestas en HTML en vez de JSON | Falta el header `Accept: application/json` |
| `422` en un Crear | Body mal formado o faltan campos obligatorios |
| `409` en un Eliminar definitivamente | El registro aún no tiene borrado lógico aplicado |
| `The following items were not found` | El `-i` debe ir con el **nombre visible** del request, no el del archivo |

---

## Si vas a editar y subir cambios

`.postman/resources.yaml` apunta al workspace `Polo0700/Redistribucion_de_alimentos_excedentes`.
Subir con `postman workspace push` **no te da acceso automáticamente**: hay que
invitarte a ese workspace primero.

Antes de subir:

```bash
postman collection lint postman/collections/redistribucion-alimentos-api
postman workspace diff --push-strategy default   # vista previa, no borra nada
postman workspace push
```
