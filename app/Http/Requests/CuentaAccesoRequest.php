<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuentaAccesoRequest extends FormRequest
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
            'proveedor' => 'required|string|max:30',
            'identificador_externo' => 'required|string|max:150',
            'contrasena' => ($esCreacion ? 'required' : 'nullable').'|string|min:6|max:255',
            'fecha_ultimo_acceso' => 'required|date',
        ];
    }
}
