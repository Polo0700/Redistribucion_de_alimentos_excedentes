<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccionImportante extends Model
{
    protected $table = 'acciones_importantes';

    protected $primaryKey = 'id_accion';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'accion',
        'tabla_afectada',
        'descripcion',
        'fecha_hora',
        'ip_origen',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}