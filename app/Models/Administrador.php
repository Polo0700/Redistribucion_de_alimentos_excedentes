<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrador extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public const NIVELES_PERMITIDOS = [1, 2, 3];

    protected $table = 'administradores';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'nombre_completo',
        'foto',
        'username',
        'password',
        'activo',
        'nivel',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'nivel' => 'integer',
    ];

    protected $hidden = [
        'password',
    ];
}
