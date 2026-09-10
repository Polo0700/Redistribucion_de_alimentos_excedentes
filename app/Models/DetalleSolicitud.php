<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleSolicitud extends Model
{
    protected $table = 'detalle_solicitud';

    protected $primaryKey = 'id_detalle_solicitud';

    public $timestamps = false;

    protected $fillable = [
        'id_solicitud',
        'id_alimento',
        'cantidad',
        'estado',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud');
    }

    public function alimento()
    {
        return $this->belongsTo(Alimento::class, 'id_alimento');
    }
}