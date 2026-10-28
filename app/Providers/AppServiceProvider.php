<?php

namespace App\Providers;

use App\Models\Administrador;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Centralización de reglas de autorización mediante Gates (Niveles de Administrador).
     */
    public function boot(): void
    {
        // Superadmin: El Administrador de Nivel 3 activo tiene autorización total
        Gate::before(function ($user, $ability) {
            if ($user instanceof Administrador && $user->activo && $user->nivel === 3) {
                return true;
            }
            return null; // Continuar evaluando los Gates específicos
        });

        // Regla 1: Consultar información (Nivel 1, 2 y 3)
        Gate::define('consultar', function ($user) {
            return $user instanceof Administrador && $user->activo && $user->nivel >= 1;
        });

        // Regla 2: Crear registros (Nivel 2 y 3)
        Gate::define('crear', function ($user) {
            return $user instanceof Administrador && $user->activo && $user->nivel >= 2;
        });

        // Regla 3: Editar registros (Nivel 2 y 3)
        Gate::define('editar', function ($user) {
            return $user instanceof Administrador && $user->activo && $user->nivel >= 2;
        });

        // Regla 4: Eliminar registros (Exclusivo Nivel 3)
        Gate::define('eliminar', function ($user) {
            return $user instanceof Administrador && $user->activo && $user->nivel === 3;
        });

        // Regla 5: Administrar cuentas de administradores (Exclusivo Nivel 3)
        Gate::define('administrar-administradores', function ($user) {
            return $user instanceof Administrador && $user->activo && $user->nivel === 3;
        });
    }
}
