<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriaPadreRequest extends FormRequest
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
        // Se saca el ID de la categoria padre que se pasa desde la URL.
        $categoriaPadreId = $this->route('categoriaPadre')->id;

        return [
            'nombre' => 'sometimes|required|string|max:255|unique:categoria_padres,nombre,' . $categoriaPadreId,
            'descripcion' => 'nullable|string',
        ];
    }
}
