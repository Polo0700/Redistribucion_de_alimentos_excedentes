<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaAlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $esCreacion = $this->isMethod('post');

        return [
            'nombre' => 'required|string|max:80',
            'descripcion' => ($esCreacion ? 'required' : 'nullable').'|string|max:200',
            'estado' => 'required|boolean',
        ];
    }
}
