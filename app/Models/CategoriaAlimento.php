<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaAlimento extends Model
{
    protected $table = 'categorias_alimento';

    protected $primaryKey = 'id_categoria';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function alimentos(): HasMany
    {
        return $this->hasMany(
            Alimento::class,
            'id_categoria',
            'id_categoria'
        );
    }
}