# Plan: CRUD de Administradores + @can en vistas + limpieza spatie

- [x] 1. Quitar spatie/laravel-permission de vendor, composer.lock, installed.json y caché de paquetes + dump-autoload
- [x] 2. Migración add_deleted_at_to_administradores_table (papelera como los demás módulos)
- [x] 3. AdministradorController (index/create/store/show/edit/update/destroy/trashed/restore/forceDestroy)
      - validar nivel in:1,2,3 (Administrador::NIVELES_PERMITIDOS), username único, password hash
      - protecciones: no eliminarse/desactivarse/degradarse a sí mismo
- [x] 4. Vistas administradores/{index,create,edit,show,trashed,niveles}.blade.php con @can/@cannot
      - vista demo previa (administradores.blade.php) movida a administradores/niveles.blade.php
- [x] 5. Rutas CRUD con can:administrar-administradores + can:consultar/crear/editar/eliminar
      - whereNumber('id') en TODAS las rutas {id} (evita que /create cayera en {id} → 404)
- [x] 6. Menú actualizado: route('administradores') → administradores.index
- [x] 7. @can/@cannot en index de: cuentas-acceso, categorias, donaciones, solicitudes, entregas, acciones-importantes (subagente)
- [x] 8. Tests: AutorizacionNivelesTest + AdministradorCrudTest
      - AutenticaComoAdmin ahora autentica un Administrador nivel 3 (antes autenticaba CuentaAcceso y rompía los Gates)
- [x] 9. Verificado: 56 tests pasan (255 assertions)
- [x] 10. README actualizado (tabla de niveles, cuentas del seeder, cómo funciona la autorización)
