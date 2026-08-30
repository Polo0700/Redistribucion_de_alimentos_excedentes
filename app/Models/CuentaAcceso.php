<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuentaAcceso extends Model
{
    protected $table = 'cuentas_acceso';

    protected $primaryKey = 'id_cuenta';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'proveedor',
        'identificador_externo',
        'contrasena_hash',
        'fecha_ultimo_acceso',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
    }
}