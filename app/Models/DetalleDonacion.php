<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleDonacion extends Model
{
    protected $table = 'detalle_donacion';

    protected $primaryKey = 'id_detalle_donacion';

    public $timestamps = false;

    protected $fillable = [
        'id_donacion',
        'id_alimento',
        'cantidad',
        'observaciones',
    ];

    public function donacion(): BelongsTo
    {
        return $this->belongsTo(
            Donacion::class,
            'id_donacion',
            'id_donacion'
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