<?php

namespace App\Http\Requests;

use App\Models\Administrador;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // store() es POST, update() es PUT → así distingue si la password es obligatoria
        $esEdicion = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'nombre_completo' => 'required|string|max:50',
            // ignore($this->route('id')): en POST no hay {id} (ignore(null) = unique normal)
            // en PUT ignora el propio registro para que su username no cuente como repetido
            'username' => ['required', 'string', 'max:30', Rule::unique('administradores', 'username')->ignore($this->route('id'))],
            'password' => $esEdicion ? 'nullable|string|min:8|max:255' : 'required|string|min:8|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'activo' => 'nullable|boolean',
            'nivel' => 'required|integer|in:' . implode(',', Administrador::NIVELES_PERMITIDOS),
        ];
    }

    public function messages(): array
    {
        return [
            'nivel.in' => 'El nivel debe ser 1, 2 o 3.',
            'username.unique' => 'Ese username ya está en uso.',
            'password.min' => 'La password debe tener al menos 8 caracteres.',
        ];
    }
}
