<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlimentoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_alimento' => $this->id_alimento,
            'id_categoria' => $this->id_categoria,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'estado' => (bool) $this->estado,
            'imagen_url' => $this->imagen ? asset('storage/'.$this->imagen) : null,
            'categoria' => $this->whenLoaded('categoria', fn () => new CategoriaAlimentoResource($this->categoria)),
        ];
    }
}
