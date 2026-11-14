<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $esCreacion = $this->isMethod('post');

        return [
            'nombre' => 'required|string|max:50',
            'descripcion' => ($esCreacion ? 'required' : 'nullable').'|string|max:200',
            'estado' => 'required|boolean',
        ];
    }
}
