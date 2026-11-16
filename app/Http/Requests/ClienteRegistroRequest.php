<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        // es un endpoint público de autenticación: cualquier visitante puede registrarse
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'correo' => ['required', 'email', 'max:150', 'unique:usuarios,correo'],
            'telefono' => ['required', 'string', 'max:100'],
            'direccion' => ['required', 'string', 'max:200'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'correo.unique' => 'Ya existe una cuenta registrada con ese correo.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
