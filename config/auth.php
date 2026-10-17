<?php

use App\Models\Administrador;
use App\Models\User;

// configuracion de autenticacion del proyecto
// aqui se define quien vigila cada area y de donde saca los datos

return [

    // valores por defecto
    // 'web' es el guard de fabrica, nosotros usamos 'admin'

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    // guards = vigilantes de sesion
    // el guard 'admin' es el nuestro: cuida el area administrativa

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'administradores',
        ],
    ],

    // providers = de donde saca los usuarios para verificarlos
    // 'administradores' usa el modelo Administrador -> tabla administradores

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', User::class),
        ],
        'administradores' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_ADMIN_MODEL', Administrador::class),
        ],
    ],

    // resetear contraseñas: tabla y duracion de los tokens (no lo usamos por ahora)

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    // tiempo de espera para confirmar contraseña (3 horas)

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
