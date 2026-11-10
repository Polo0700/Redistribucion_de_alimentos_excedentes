<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdministradorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [        
            'id' => $this->id,
            'nombre_completo' => $this->nombre_completo,
            'username' => $this->username,
            'nivel' => $this->nivel,
            'foto' => $this->foto,
            'activo' => (bool) $this->activo,
            'created_at' => $this->created_at,
            'updated_at'=> $this->updated_at,
        ];
    }
}
