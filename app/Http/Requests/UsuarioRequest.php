<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_rol' => 'required|exists:roles,id',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|email|max:150',
            'telefono' => 'required|string|max:100',
            'direccion' => 'required|string|max:200',
            'fecha_registro' => 'required|date',
            'estado' => 'required|boolean',
        ];
    }
}
