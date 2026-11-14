<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntregaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_solicitud' => 'required|exists:solicitudes,id_solicitud',
            'fecha_entrega' => 'required|date',
            'responsable' => 'required|string|max:100',
            'estado' => 'required|string|max:30',
            'observaciones' => 'nullable|string|max:250',
        ];
    }
}
