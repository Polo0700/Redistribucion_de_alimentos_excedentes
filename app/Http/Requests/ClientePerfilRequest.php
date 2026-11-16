<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación de actualización del perfil del cliente autenticado.
 * Solo se permiten los campos propios del perfil: el correo y el
 * rol no se pueden modificar desde la API del cliente.
 */
class ClientePerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // la autorización la aplica EnsureEsCliente
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'max:100'],
            'direccion' => ['required', 'string', 'max:200'],
        ];
    }
}
