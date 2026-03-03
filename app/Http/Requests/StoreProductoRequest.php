<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        // Si no es Admin, no puede guardar un producto nuevo a la BD.
        if ($user->hasRole('admin')) {
            return true;
        }

        // Si tiene el permiso "gestionar-catalogo" también puede hacer esta acción.
        return $user->hasPermissionTo('gestionar-catalogo');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Se valida que el id del proveedor está puesto y que existe de verdad.
            "proveedor_id" => "required|exists:proveedors,id",

            "nombre" => "required|string|max:255|unique:productos,nombre",
            "descripcion" => "nullable|string",
            "precio" => "required|numeric|min:0",
            "stock" => "required|integer|min:0",
            "imagen_url" => "nullable|string",

            // Validación de las categorías atribuidas al producto, se envían como un array de Uuids.
            "categorias" => "required|array|min:1",
            "categorias.*" => "uuid|exists:categorias,id"
        ];
    }
}
