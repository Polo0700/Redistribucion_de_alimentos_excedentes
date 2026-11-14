<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonacionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_donacion' => $this->id_donacion,
            'id_usuario' => $this->id_usuario,
            'fecha_donacion' => $this->fecha_donacion,
            'fecha_limite' => $this->fecha_limite,
            'ubicacion' => $this->ubicacion,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'usuario' => $this->whenLoaded('usuario', fn () => new UsuarioResource($this->usuario)),
        ];
    }
}
