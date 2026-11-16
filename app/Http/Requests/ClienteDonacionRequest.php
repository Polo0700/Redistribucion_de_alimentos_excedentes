<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación de creación de donaciones del cliente.
 * El id_usuario NUNCA se valida aquí: se asigna en el backend con
 * la identidad del token (auth()->id()), impidiendo su manipulación.
 */
class ClienteDonacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // la autorización la aplica EnsureEsCliente + Policy
    }

    public function rules(): array
    {
        return [
            'fecha_limite' => ['required', 'date', 'after_or_equal:today'],
            'ubicacion' => ['required', 'string', 'max:200'],
            'observaciones' => ['required', 'string', 'max:200'],
            'id_alimento' => ['required', 'integer', 'exists:alimentos,id_alimento'],
            'cantidad' => ['required', 'numeric', 'min:0.01', 'max:99999999'],
        ];
    }

    public function messages(): array
    {
        return [
            'cantidad.min' => 'La cantidad debe ser un monto positivo.',
            'fecha_limite.after_or_equal' => 'La fecha límite no puede ser anterior a hoy.',
        ];
    }
}
