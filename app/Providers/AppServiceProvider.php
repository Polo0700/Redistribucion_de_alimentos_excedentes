<?php

namespace App\Providers;

use App\Models\Donacion;
use App\Models\Solicitud;
use App\Policies\DonacionPolicy;
use App\Policies\SolicitudPolicy;
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
     */
    public function boot(): void
    {
        // Policies de propiedad de datos (Actividad 4.4): comparan el
        // id del dueño del registro contra el usuario autenticado.
        Gate::policy(Solicitud::class, SolicitudPolicy::class);
        Gate::policy(Donacion::class, DonacionPolicy::class);

        // Gate de apoyo: define la regla global de gestionar registros
        // propios (usada como alternativa a las Policy por método).
        Gate::define('gestionar-registro-propio', function ($cuenta, $recurso) {
            return $cuenta->id_usuario === $recurso->id_usuario;
        });
    }
}
