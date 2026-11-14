<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccionImportanteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_accion' => $this->id_accion,
            'id_usuario' => $this->id_usuario,
            'accion' => $this->accion,
            'tabla_afectada' => $this->tabla_afectada,
            'descripcion' => $this->descripcion,
            'fecha_hora' => $this->fecha_hora,
            'ip_origen' => $this->ip_origen,
            'usuario' => $this->whenLoaded('usuario', fn () => new UsuarioResource($this->usuario)),
        ];
    }
}
