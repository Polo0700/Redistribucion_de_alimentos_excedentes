<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeseoDetalle extends Model
{
    protected $table = 'deseos_detalle';

    protected $primaryKey = 'id_deseo';

    public $timestamps = false;

    protected $fillable = [
        'id_lista',
        'id_alimento',
        'fecha_agregado',
    ];

    public function lista(): BelongsTo
    {
        return $this->belongsTo(
            ListaDeseo::class,
            'id_lista',
            'id_lista'
        );
    }

    public function alimento(): BelongsTo
    {
        return $this->belongsTo(
            Alimento::class,
            'id_alimento',
            'id_alimento'
        );
    }
}