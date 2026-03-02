<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProveedorRequest extends FormRequest
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
        $proveedorId = $this->route('proveedor')->id;

        return [
            'nombre' => 'sometimes|required|string|max:255',
            'cif' => [
                'sometimes',
                'required',
                'string',
                'regex:/^[A-Za-z]\d{7}[A-Za-z0-9]$/',
                'unique:proveedors,cif,' . $proveedorId
            ],
            'email' => 'sometimes|required|email|unique:proveedors,email,' . $proveedorId,
            'telefono' => 'sometimes|required|string|max:20',
            'direccion' => 'nullable|string',
        ];
    }
}
