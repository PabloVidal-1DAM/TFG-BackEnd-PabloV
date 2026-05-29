<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cualquier persona (incluso sin sesión) puede intentar loguearse
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email" => "required|email|exists:users,email", // Añado ',email' por seguridad
            "password" => "required|string|min:6"
        ];
    }

    /**
     * Custom messages para los errores de validación en español.
     */
    public function messages(): array
    {
        return [
            // Errores del Email
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debes introducir un correo electrónico válido.',
            'email.exists' => 'Esta cuenta no existe. Por favor, regístrate.',

            // Errores de la Contraseña
            'password.required' => 'La contraseña es obligatoria para iniciar sesión.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }
}
