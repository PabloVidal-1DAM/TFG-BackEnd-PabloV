<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
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
        // Se saca el ID del producto que se pasa desde la URL.
        $productoId = $this->route('producto')->id;
        return [
            // Funciona casi igual que Store, pero con sometimes se indica de que no siempre recibirá esos datos, y así está bien.
            "proveedor_id" => "sometimes|required|exists:proveedors,id",

            "nombre" => "sometimes|required|string|max:255|unique:productos,nombre",
            "descripcion" => "nullable|string",
            "precio" => "sometimes|required|numeric|min:0",
            "stock" => "sometimes|required|integer|min:0",
            "imagen_url" => "nullable|string",

            // Validación de las categorías atribuidas al producto, se envían como un array de Uuids.
            "categorias" => "sometimes|required|array|min:1",
            "categorias.*" => "uuid|exists:categorias,id"
        ];
    }
}
