<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_solicitud' => $this->id_solicitud,
            'id_usuario' => $this->id_usuario,
            'fecha_solicitud' => $this->fecha_solicitud,
            'estado' => $this->estado,
            'direccion_entrega' => $this->direccion_entrega,
            'observaciones' => $this->observaciones,
            'usuario' => $this->whenLoaded('usuario', fn () => new UsuarioResource($this->usuario)),
        ];
    }
}
