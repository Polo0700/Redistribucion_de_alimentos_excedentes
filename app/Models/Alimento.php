<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alimento extends Model
{
    protected $table = 'alimentos';

    protected $primaryKey = 'id_alimento';

    public $timestamps = false;

    protected $fillable = [
        'id_categoria',
        'nombre',
        'descripcion',
        'estado',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(
            CategoriaAlimento::class,
            'id_categoria',
            'id_categoria'
        );
    }

    public function detallesDonacion(): HasMany
    {
        return $this->hasMany(
            DetalleDonacion::class,
            'id_alimento',
            'id_alimento'
        );
    }

    public function detallesCarrito(): HasMany
    {
        return $this->hasMany(
            CarritoDetalle::class,
            'id_alimento',
            'id_alimento'
        );
    }

    public function deseosDetalle(): HasMany
    {
        return $this->hasMany(
            DeseoDetalle::class,
            'id_alimento',
            'id_alimento'
        );
    }
}
