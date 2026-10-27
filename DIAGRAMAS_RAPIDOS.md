# ⚡ Diagramas de Referencia Rápida

## 🗄️ ER Diagram (Mermaid Live Editor compatible)

```mermaid
erDiagram
    ADMINISTRADORES {
        bigint id PK
        string nombre_completo
        string username UK
        string password
        boolean activo
    }
    
    ROLES {
        bigint id PK
        string nombre
        string descripcion
        boolean estado
    }
    
    USUARIOS {
        bigint id PK
        bigint id_rol FK
        string nombre
        string apellido
        string correo
        string telefono
        string direccion
        datetime fecha_registro
        boolean estado
    }
    
    CUENTAS_ACCESO {
        bigint id PK
        bigint id_usuario FK
        string username
        string password
        boolean estado
    }
    
    CATEGORIAS_ALIMENTO {
        bigint id_categoria PK
        string nombre
        string descripcion
        boolean estado
    }
    
    ALIMENTOS {
        bigint id_alimento PK
        bigint id_categoria FK
        string nombre
        string descripcion
        boolean estado
        string imagen
    }
    
    DONACIONES {
        bigint id_donacion PK
        bigint id_usuario FK
        datetime fecha_donacion
        date fecha_limite
        string ubicacion
        string estado
        string observaciones
    }
    
    DETALLE_DONACION {
        bigint id_detalle_donacion PK
        bigint id_donacion FK
        bigint id_alimento FK
        decimal cantidad
        string observaciones
    }
    
    SOLICITUDES {
        bigint id_solicitud PK
        bigint id_usuario FK
        string direccion_entrega
        string observaciones
        timestamp fecha_solicitud
        string estado
    }
    
    DETALLE_SOLICITUD {
        bigint id_detalle_solicitud PK
        bigint id_solicitud FK
        bigint id_alimento FK
        decimal cantidad
        string estado
    }
    
    ENTREGAS {
        bigint id_entrega PK
        bigint id_solicitud FK
        datetime fecha_entrega
        string responsable
        string estado
        string observaciones
    }
    
    ACCIONES_IMPORTANTES {
        bigint id PK
        bigint id_usuario FK
        string titulo
        string descripcion
        datetime fecha
        boolean estado
    }
    
    ROLES ||--o{ USUARIOS : ""
    USUARIOS ||--o{ CUENTAS_ACCESO : ""
    USUARIOS ||--o{ DONACIONES : ""
    USUARIOS ||--o{ SOLICITUDES : ""
    USUARIOS ||--o{ ACCIONES_IMPORTANTES : ""
    CATEGORIAS_ALIMENTO ||--o{ ALIMENTOS : ""
    ALIMENTOS ||--o{ DETALLE_DONACION : ""
    ALIMENTOS ||--o{ DETALLE_SOLICITUD : ""
    DONACIONES ||--o{ DETALLE_DONACION : ""
    SOLICITUDES ||--o{ DETALLE_SOLICITUD : ""
    SOLICITUDES ||--|| ENTREGAS : ""
```

---

## 🔗 Relaciones Eloquent (Resumen)

```mermaid
classDiagram
    direction LR
    
    Rol "1" -- "*" Usuario : id_rol
    Usuario "1" -- "*" CuentaAcceso : id_usuario
    Usuario "1" -- "*" Donacion : id_usuario
    Usuario "1" -- "*" Solicitud : id_usuario
    Usuario "1" -- "*" AccionImportante : id_usuario
    CategoriaAlimento "1" -- "*" Alimento : id_categoria
    Alimento "1" -- "*" DetalleDonacion : id_alimento
    Alimento "1" -- "*" DetalleSolicitud : id_alimento
    Donacion "1" -- "*" DetalleDonacion : id_donacion
    Solicitud "1" -- "*" DetalleSolicitud : id_solicitud
    Solicitud "1" -- "1" Entrega : id_solicitud
```

---

## 🛣️ Rutas por Módulo (Patrón Unificado)

| Módulo | Prefijo | Rutas (9 c/u) |
|--------|---------|---------------|
| **Roles** | `/roles` | index, create, store, edit, update, show, trashed, destroy, restore, forceDestroy |
| **Usuarios** | `/usuarios` | index, create, store, edit, update, show, trashed, destroy, restore, forceDestroy |
| **Cuentas Acceso** | `/cuentas-acceso` | index, create, store, edit, update, show, trashed, destroy, restore, forceDestroy |
| **Categorías** | `/categorias` | index, create, store, edit, update, show, trashed, destroy, restore, forceDestroy |
| **Alimentos** | `/alimentos` | index, create, store, edit, update, show, trashed, destroy, restore, forceDestroy |
| **Donaciones** | `/donaciones` | index, create, store, edit, update, show, trashed, destroy, restore, forceDestroy |
| **Solicitudes** | `/solicitudes` | index, create, store, edit, update, show, trashed, destroy, restore, forceDestroy |
| **Entregas** | `/entregas` | index, create, store, edit, update, show, trashed, destroy, restore, forceDestroy |
| **Acciones Importantes** | `/acciones-importantes` | index, create, store, edit, update, show, trashed, destroy, restore, forceDestroy |

**Total: ~85 rutas** (todas protegidas con `auth:admin` excepto login)

---

## 📦 Estructura de Carpetas Clave

```
app/
├── Http/Controllers/     # 11 Controladores
│   ├── AuthAdminController.php
│   ├── RolController.php
│   ├── UsuarioController.php
│   ├── CuentaAccesoController.php
│   ├── CategoriaAlimentoController.php
│   ├── AlimentoController.php
│   ├── DonacionController.php
│   ├── SolicitudController.php
│   ├── EntregaController.php
│   └── AccionImportanteController.php
├── Models/               # 13 Modelos Eloquent
│   ├── Administrador.php
│   ├── Rol.php
│   ├── Usuario.php
│   ├── CuentaAcceso.php
│   ├── CategoriaAlimento.php
│   ├── Alimento.php
│   ├── Donacion.php
│   ├── DetalleDonacion.php
│   ├── Solicitud.php
│   ├── DetalleSolicitud.php
│   ├── Entrega.php
│   ├── AccionImportante.php
│   └── User.php
└── Providers/

database/migrations/      # 18 Migraciones
routes/
├── web.php              # 283 líneas - Todas las rutas web
└── console.php
```

---

## 🔄 Flujo de Datos Principal

```mermaid
flowchart TD
    A[Admin Login] --> B[Dashboard]
    B --> C1[Gestión Roles]
    B --> C2[Gestión Usuarios]
    B --> C3[Gestión Categorías]
    B --> C4[Gestión Alimentos]
    
    C4 --> D1[Donante crea Donación]
    D1 --> D2[Agrega Detalles Alimentos]
    
    C4 --> E1[Receptor crea Solicitud]
    E1 --> E2[Agrega Items Solicitados]
    
    D2 --> F[Admin gestiona Solicitudes]
    E2 --> F
    F --> G[Aprobar/Rechazar]
    G --> H[Crear Entrega]
    H --> I[Confirmar Entrega]
    I --> J[Actualizar Estados]
```

---

## 🎯 Comandos Esenciales

```bash
# Desarrollo
php artisan serve                    # Servidor local
php artisan migrate                  # Ejecutar migraciones
php artisan migrate:fresh --seed     # Reset BD + seeders
php artisan route:list               # Ver todas las rutas
php artisan route:list --name=donaciones  # Filtrar rutas

# Código
./vendor/bin/pint                    # Formatear código (Pint)
php artisan test                     # Tests
php artisan make:model Nombre -mcr   # Modelo + Migración + Controller

# Cache
php artisan optimize:clear           # Limpiar todo cache
```

---

## 📊 Estados Principales

| Entidad | Estados |
|---------|---------|
| **Solicitud** | `pendiente` → `aprobada`/`rechazada` → `en_preparacion` → `en_ruta` → `entregada` |
| **Donación** | `disponible` → `reservada` → `entregada` / `expirada` |
| **Soft Delete** | `activo` → `eliminado` (trashed) → `restaurado` / `forzado` |

---

> **Tip:** Copia cualquier diagrama Mermaid y pégalo en [Mermaid Live Editor](https://mermaid.live/) para visualizarlo interactivamente.