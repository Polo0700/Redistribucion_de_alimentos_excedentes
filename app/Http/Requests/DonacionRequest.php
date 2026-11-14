<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $esCreacion = $this->isMethod('post');

        return [
            'id_usuario' => 'required|exists:usuarios,id',
            'fecha_donacion' => 'required|date',
            'fecha_limite' => 'required|date|after:fecha_donacion',
            'ubicacion' => 'required|string|max:200',
            'estado' => 'required|string|max:30',
            'observaciones' => ($esCreacion ? 'required' : 'nullable').'|string|max:250',
        ];
    }
}
