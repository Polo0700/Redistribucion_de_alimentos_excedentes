<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrador extends Authenticatable
{
    use HasFactory, Notifiable;

    // modelo administrador
    // leer en /log/10-17-2026/funcion_modelo_admin.md
    // propiedades de la tabla administradores

    protected $table = 'administradores';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'nombre_completo',
        'username',
        'password',
        'foto',
        'activo',
    ];

    protected $hidden = [
        'password',
    ];
}
