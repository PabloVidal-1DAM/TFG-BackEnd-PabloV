<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriaRequest extends FormRequest
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
        // se rescata el ID que se pone en la URL
        $categoriaId = $this->route('categoria')->id;

        return [
            'categoria_padre_id' => 'sometimes|required|uuid|exists:categoria_padres,id',
            'nombre' => 'sometimes|required|string|max:255|unique:categorias,nombre,' . $categoriaId,
            'descripcion' => 'nullable|string',
        ];
    }
}
