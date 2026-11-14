<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_rol' => $this->id_rol,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'fecha_registro' => $this->fecha_registro,
            'estado' => (bool) $this->estado,
            'rol' => $this->whenLoaded('rol', fn () => new RolResource($this->rol)),
        ];
    }
}
