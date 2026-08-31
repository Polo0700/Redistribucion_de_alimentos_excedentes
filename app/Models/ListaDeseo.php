<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListaDeseo extends Model
{
    protected $table = 'listas_deseos';

    protected $primaryKey = 'id_lista';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'fecha_creacion',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            Usuario::class,
            'id_usuario',
            'id'
        );
    }

    public function deseos(): HasMany
    {
        return $this->hasMany(
            DeseoDetalle::class,
            'id_lista',
            'id_lista'
        );
    }
}