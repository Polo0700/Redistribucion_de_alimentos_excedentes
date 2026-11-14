<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdministradorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // el username no puede repetirse, pero se ignora el propio al actualizar
        // (el parámetro de la ruta apiResource se genera como "administradore")
        $id = $this->route('administradore') ?? $this->route('administrador') ?? $this->route('id');

        $esCreacion = $this->isMethod('post');

        $reglaUsername = \Illuminate\Validation\Rule::unique('administradores', 'username');

        if ($id) {
            $reglaUsername->ignore($id);
        }

        return [
            'nombre_completo' => 'required|string|max:50',
            'username' => ['required', 'string', 'max:30', $reglaUsername],
            'password' => ($esCreacion ? 'required' : 'nullable').'|string|min:8|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'activo' => 'nullable|boolean',
            'nivel' => 'required|integer|in:1,2,3',
        ];
    }

    public function messages(): array
    {
        return [
            'nivel.in' => 'El nivel debe ser 1, 2 o 3.',
        ];
    }
}
