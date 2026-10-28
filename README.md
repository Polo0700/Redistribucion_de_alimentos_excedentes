# Redistribución de Alimentos Excedentes

## **ODS 2: Hambre cero**

## Integrantes del repositorio

- Rito Aguíla
- Jorge Moreno
- Cristobál Morales

---

# Objetivos

## Objetivo principal

- Diseñar una base de datos relacional para la administracion y organizacion en el registro, disponibilidad, donación, solicitud y entrega de alimentos excedentes, alineados con la ODS 2 (Hambre Cero) facilitando su redistribución

## Finalidad del proyecto

- La finalidad de este proyecto es poder desarrollar en equipo una plataforma que nos permita tener un control de los alimentos que están en buen estado para el consumo, y son excedentes de establecimientos, organizaciones o vendedores, y asi poderle dar un uso correcto

---

- El sistema mediante una base de datos permitirá tener el control de los alimentos disponibles, asi como la distribución y las solicitudes adecuadas, asi como también la administración de donaciones y realizar solicitudes gratuitas, esto con la finalidad de poder distribuir y darle un buen uso a los alimentos, esto sin ningún fin monetario, no se tendrán transacciones

---

# Instalación

## Requisitos

- PHP 8.2 o superior
- Composer
- MySQL (por ejemplo XAMPP)

## Pasos

### 1. Clonar el repositorio

```
git clone https://github.com/Polo0700/Redistribucion_de_alimentos_excedentes.git
```

### 2. Instalar dependencias

```
composer install
```

### 3. Configurar el entorno

```
copy .env.example .env
php artisan key:generate
```

Abrir el `.env` y poner los datos de la base de datos (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

### 4. Crear la base de datos y las tablas

```
php artisan migrate
php artisan db:seed --class=AdministradorSeeder
```

El seed crea estas cuentas de prueba (contraseña `admin123` para todas):

| Username   | Nivel | Capacidades                        |
|------------|-------|------------------------------------|
| `admin`    | 3     | Acceso total (superadmin)          |
| `admin1`   | 1     | Solo consulta                      |
| `admin2`   | 2     | Consulta, crear y editar           |
| `admin3`   | 3     | Acceso total (superadmin)          |
| `inactivo` | 1     | Deshabilitada, no puede entrar     |

---

# Autorización por niveles de administrador

El sistema define **tres niveles de administrador** en el campo `nivel` de la tabla `administradores` (migración `add_nivel_to_administradores_table`). La autorización usa los mecanismos nativos de Laravel (Gates, middleware `can:` y directivas Blade `@can`/`@cannot`), sin paquetes externos.

| Operación                       | Nivel 1 | Nivel 2 | Nivel 3 |
|---------------------------------|---------|---------|---------|
| Consultar información           | ✅      | ✅      | ✅      |
| Crear registros                 | ❌      | ✅      | ✅      |
| Editar registros                | ❌      | ✅      | ✅      |
| Eliminar registros              | ❌      | ❌      | ✅      |
| Administrar cuentas de admins   | ❌      | ❌      | ✅      |

Cómo está implementado:

- **Gates centralizados** en `app/Providers/AppServiceProvider.php` (`boot()`): `consultar`, `crear`, `editar`, `eliminar` y `administrar-administradores`. Todos verifican `$user instanceof Administrador`, que esté activo y su `nivel` (leído de la BD, nunca del formulario).
- **Protección en rutas**: cada ruta del panel está bajo `auth:admin` y añade `can:<habilidad>` (ver `routes/web.php`). Las operaciones restringidas devuelven **403** (página `resources/views/errors/403.blade.php`) aunque se llamen directamente por URL.
- **Vistas Blade**: los botones de crear, editar y eliminar se muestran u ocultan con `@can`/`@cannot` según el Gate, para que la UI siempre coincida con las reglas del servidor.
- **CRUD de administradores** (`/administradores`, exclusivo nivel 3): el campo `nivel` se valida en el servidor con `in:1,2,3`; la contraseña se guarda hasheada; un administrador no puede eliminarse ni desactivarse a sí mismo.
- **Futura API**: los mismos Gates son reutilizables con `Gate::allows('eliminar')` tras autenticar la API (p. ej. con Sanctum); `@can` sigue siendo solo para vistas web.

### Pruebas

```bash
php artisan test
```

Incluye `AutorizacionNivelesTest` (permisos por nivel, invitados y administradores inactivos) y `AdministradorCrudTest` (CRUD de administradores).

### 5. Arrancar el proyecto

```
php artisan serve
```

### 6. Entrar al area administrativa

Abrir `http://127.0.0.1:8000/admin/login` y usar las cuentas del paso 4.

---

<sub>© 2026 - Creacion del repositorio: 29 de agosto de 2026</sub>
