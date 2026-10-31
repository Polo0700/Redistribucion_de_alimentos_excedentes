<?php

namespace App\Providers;

use App\Models\Administrador;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user instanceof Administrador && $user->activo && $user->nivel === 3) {
                return true;
            }
            return null;
        });

        // consultar (nivel 1, 2 y 3)
        Gate::define('consultar', function ($user) {
            return $user instanceof Administrador && $user->activo && $user->nivel >= 1;
        });

        // crear registros (nivel 2 y 3)
        Gate::define('crear', function ($user) {
            return $user instanceof Administrador && $user->activo && $user->nivel >= 2;
        });

        // editar registros (nivel 2 y 3)
        Gate::define('editar', function ($user) {
            return $user instanceof Administrador && $user->activo && $user->nivel >= 2;
        });

        // eliminar registros (solo nivel 3)
        Gate::define('eliminar', function ($user) {
            return $user instanceof Administrador && $user->activo && $user->nivel === 3;
        });

        // administrar administradores (solo nivel 3)
        Gate::define('administrar-administradores', function ($user) {
            return $user instanceof Administrador && $user->activo && $user->nivel === 3;
        });
    }
}
