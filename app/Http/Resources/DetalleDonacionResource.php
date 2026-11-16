<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetalleDonacionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_detalle_donacion' => $this->id_detalle_donacion,
            'id_donacion' => $this->id_donacion,
            'id_alimento' => $this->id_alimento,
            'cantidad' => $this->cantidad,
            'observaciones' => $this->observaciones,
            'alimento' => $this->whenLoaded('alimento', fn () => new AlimentoResource($this->alimento)),
        ];
    }
}
