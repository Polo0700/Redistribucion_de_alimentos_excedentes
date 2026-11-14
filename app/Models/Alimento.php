<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alimento extends Model
{
    use SoftDeletes;

    protected $table = 'alimentos';

    protected $primaryKey = 'id_alimento';

    public $timestamps = false;

    protected $fillable = [
        'id_categoria',
        'nombre',
        'descripcion',
        'estado',
        'imagen',
    ];

    protected $appends = [
        'imagen_url',
    ];

    protected $casts = [
        'id_categoria' => 'integer',
        'estado' => 'boolean',
    ];

    public function getImagenUrlAttribute(): ?string
    {
        return $this->imagen ? asset('storage/'.$this->imagen) : null;
    }

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
}
