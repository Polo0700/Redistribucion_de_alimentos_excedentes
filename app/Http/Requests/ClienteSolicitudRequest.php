<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación de creación de registros propios (solicitudes) del cliente.
 * El id_usuario NUNCA se valida aquí: se asigna en el backend con
 * la identidad del token (auth()->id()), impidiendo su manipulación.
 */
class ClienteSolicitudRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // la autorización la aplica EnsureEsCliente + Policy
    }

    public function rules(): array
    {
        return [
            'direccion_entrega' => ['required', 'string', 'max:200'],
            'observaciones' => ['nullable', 'string', 'max:250'],
            'id_alimento' => ['required', 'integer', 'exists:alimentos,id_alimento'],
            'cantidad' => ['required', 'numeric', 'min:0.01', 'max:99999999'],
        ];
    }

    public function messages(): array
    {
        return [
            'cantidad.min' => 'La cantidad debe ser un monto positivo.',
        ];
    }
}
