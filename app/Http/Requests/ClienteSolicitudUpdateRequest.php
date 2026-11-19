<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación de modificación de registros propios (solicitudes) del cliente.
 * Solo campos editables de la solicitud: el id_usuario NUNCA se valida aquí
 * (la propiedad la garantiza la SolicitudPolicy antes de llegar a esto) y
 * el estado solo lo cambia la cancelación.
 */
class ClienteSolicitudUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // la autorización la aplica EnsureEsCliente + Policy update
    }

    public function rules(): array
    {
        return [
            'direccion_entrega' => ['required', 'string', 'max:200'],
            'observaciones' => ['nullable', 'string', 'max:250'],
        ];
    }
}
