<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntregaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_entrega' => $this->id_entrega,
            'id_solicitud' => $this->id_solicitud,
            'fecha_entrega' => $this->fecha_entrega,
            'responsable' => $this->responsable,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'solicitud' => $this->whenLoaded('solicitud', fn () => new SolicitudResource($this->solicitud)),
        ];
    }
}
