<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $esCreacion = $this->isMethod('post');

        return [
            'id_categoria' => 'required|exists:categorias_alimento,id_categoria',
            'nombre' => 'required|string|max:80',
            'descripcion' => ($esCreacion ? 'required' : 'nullable').'|string|max:200',
            'estado' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }
}
