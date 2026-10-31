<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CuentaAcceso extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'cuentas_acceso';

    protected $primaryKey = 'id_cuenta';

    public $timestamps = false;

    protected $hidden = [
        'contrasena_hash',
    ];

    protected $fillable = [
        'id_usuario',
        'proveedor',
        'identificador_externo',
        'contrasena_hash',
        'fecha_ultimo_acceso',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // aqui va la contraseña hasheada
    public function getAuthPasswordName(): string
    {
        return 'contrasena_hash';
    }
}
