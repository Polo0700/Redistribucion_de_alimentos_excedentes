<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccionImportanteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $esCreacion = $this->isMethod('post');

        return [
            'id_usuario' => ($esCreacion ? 'nullable' : 'required').'|exists:usuarios,id',
            'accion' => 'required|string|max:100',
            'tabla_afectada' => 'required|string|max:80',
            'descripcion' => 'nullable|string|max:250',
            'fecha_hora' => 'required|date',
            'ip_origen' => ($esCreacion ? 'required|ip|max:45' : 'nullable|ip'),
        ];
    }
}
