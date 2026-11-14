<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_usuario' => 'required|exists:usuarios,id',
            'fecha_solicitud' => 'required|date',
            'estado' => 'required|string|max:30',
            'direccion_entrega' => 'required|string|max:200',
            'observaciones' => 'nullable|string|max:250',
        ];
    }
}
