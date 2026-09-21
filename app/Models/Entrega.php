<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrega extends Model
{
    use SoftDeletes;
    protected $table = 'entregas';

    protected $primaryKey = 'id_entrega';

    public $timestamps = false;

    protected $fillable = [
        'id_solicitud',
        'fecha_entrega',
        'responsable',
        'estado',
        'observaciones',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud');
    }
}