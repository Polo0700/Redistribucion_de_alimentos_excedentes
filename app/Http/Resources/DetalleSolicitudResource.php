<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetalleSolicitudResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_detalle_solicitud' => $this->id_detalle_solicitud,
            'id_solicitud' => $this->id_solicitud,
            'id_alimento' => $this->id_alimento,
            'cantidad' => $this->cantidad,
            'estado' => $this->estado,
            'alimento' => $this->whenLoaded('alimento', fn () => new AlimentoResource($this->alimento)),
        ];
    }
}
