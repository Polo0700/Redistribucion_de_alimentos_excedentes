<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrador extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Niveles de administrador permitidos en el sistema.
     * 1 = solo consulta, 2 = consulta + crear + editar, 3 = acceso total.
     */
    public const NIVELES_PERMITIDOS = [1, 2, 3];

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
        'nivel',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'nivel' => 'integer',
    ];

    /**
     * Comprueba si el administrador tiene un nivel específico.
     */
    public function esNivel(int $nivel): bool
    {
        return (int) $this->nivel === $nivel;
    }

    protected $hidden = [
        'password',
    ];
}
