<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cualquiera puede intentar registrarse
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $reglas = [
            "nombre" => "required|string|max:255",
            "email" => "required|email|unique:users",
            "telefono" => "nullable|string|max:255",
            "password" => "required|string|min:6"
        ];

        // Solo si el que hace la petición tiene sesión iniciada y es Admin, le puede poner un rol y por tanto se valida el rol
        if ($this->user() && $this->user()->hasRole('admin')) {
            $reglas['rol'] = "sometimes|required|string|exists:roles,name";
        }

        return $reglas;
    }

    /**
     * Mensajes personalizados de error en español.
     */
    public function messages(): array
    {
        return [
            // Errores del Nombre
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',

            // Errores del Email
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debes introducir un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado en nuestro sistema.', // <-- El que tú decías

            // Errores del Teléfono
            'telefono.max' => 'El teléfono no puede superar los 255 caracteres.',

            // Errores de la Contraseña
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }
}
