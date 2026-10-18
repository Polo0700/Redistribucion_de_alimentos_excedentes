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

El seed crea 2 cuentas de prueba:

- `admin` / `admin123` (activa, puede entrar)
- `inactivo` / `admin123` (deshabilitada, no puede entrar)

### 5. Arrancar el proyecto

```
php artisan serve
```

### 6. Entrar al area administrativa

Abrir `http://127.0.0.1:8000/admin/login` y usar las cuentas del paso 4.

---

<sub>© 2026 - Creacion del repositorio: 29 de agosto de 2026</sub>
