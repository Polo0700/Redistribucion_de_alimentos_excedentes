<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'direccion',
        'fecha_registro',
        'estado',
    ];

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id');
    }

    public function cuentasAcceso(): HasMany
    {
        return $this->hasMany(CuentaAcceso::class, 'id_usuario', 'id');
    }

    public function donaciones(): HasMany
    {
        return $this->hasMany(Donacion::class, 'id_usuario', 'id');
    }

    public function carritos(): HasMany
    {
        return $this->hasMany(Carrito::class, 'id_usuario', 'id');
    }

    public function listasDeseos(): HasMany
    {
        return $this->hasMany(ListaDeseo::class, 'id_usuario', 'id');
    }

    public function accionesImportantes(): HasMany
    {
        return $this->hasMany(AccionImportante::class, 'id_usuario', 'id');
    }
}