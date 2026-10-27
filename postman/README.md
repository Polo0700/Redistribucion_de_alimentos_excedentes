# Guía: colección de pruebas de la API

Colección de Postman para los 9 recursos vigentes del proyecto. Vive en el repo
(`postman/collections/`) para que viaje con el código y se revise en los PRs.

| Qué | Dónde |
| --- | --- |
| 90 requests en 9 carpetas | `collections/redistribucion-alimentos-api/` |
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

Debe decir `No issues found` (90 requests escaneados).

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

Quedan fuera de la API, por decisión del dominio actual: carritos, listas de
deseos y sus detalles. Siguen existiendo como tablas, pero no tienen rutas,
controladores ni requests.
