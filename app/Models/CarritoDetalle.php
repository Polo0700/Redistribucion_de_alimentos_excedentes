<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarritoDetalle extends Model
{
    protected $table = 'carrito_detalle';

    protected $primaryKey = 'id_detalle_carrito';

    public $timestamps = false;

    protected $fillable = [
        'id_carrito',
        'id_alimento',
        'cantidad',
    ];

    public function carrito(): BelongsTo
    {
        return $this->belongsTo(
            Carrito::class,
            'id_carrito',
            'id_carrito'
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