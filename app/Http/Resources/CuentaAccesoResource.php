<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CuentaAccesoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_cuenta' => $this->id_cuenta,
            'id_usuario' => $this->id_usuario,
            'proveedor' => $this->proveedor,
            'identificador_externo' => $this->identificador_externo,
            'fecha_ultimo_acceso' => $this->fecha_ultimo_acceso,
            'usuario' => $this->whenLoaded('usuario', fn () => new UsuarioResource($this->usuario)),
        ];
    }
}
