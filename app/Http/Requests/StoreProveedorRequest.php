<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProveedorRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            // Para el cif se pasa a array para poder meter la expresión regular sin problemas.
            'cif' => [
                'required',
                'string',
                // Misma lógica que en el factory: 1 letra + 7 números y terminar en 1 número o letra.
                'regex:/^[A-Za-z]\d{7}[A-Za-z0-9]$/',
                'unique:proveedors,cif'
            ],
            'email' => 'required|email|unique:proveedors,email',
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string',
        ];
    }
}
