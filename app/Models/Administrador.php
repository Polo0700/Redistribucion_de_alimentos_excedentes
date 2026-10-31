<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrador extends Authenticatable
{
    use HasFactory, Notifiable;

   

    protected $table = 'administradores';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'nombre_completo',
        'username',
        'password',
        'activo',
    ];

    protected $hidden = [
        'password',
    ];
}
