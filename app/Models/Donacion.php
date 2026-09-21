<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donacion extends Model
{
    use SoftDeletes;
    protected $table = 'donaciones';

    protected $primaryKey = 'id_donacion';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'fecha_donacion',
        'fecha_limite',
        'ubicacion',
        'estado',
        'observaciones',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            Usuario::class,
            'id_usuario',
            'id'
        );
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(
            DetalleDonacion::class,
            'id_donacion',
            'id_donacion'
        );
    }
}