<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // <-- 1. IMPORTANTE: No olvides importar esta clase

class UpdateProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        // Si no es Admin, no puede modificar un producto de la BD.
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
        // Se saca el ID del producto que se pasa desde la URL.
        // OJO: Si usas Route Model Binding, esto devuelve el objeto y le sacas el ->id.
        // Si no, devuelve directamente el string (UUID). Asumimos que lo tienes bien como objeto:
        $producto = $this->route('producto');
        $productoId = is_object($producto) ? $producto->id : $producto;

        return [
            "proveedor_id" => "sometimes|required|exists:proveedors,id",

            // 2. CORRECCIÓN: Cambiamos a formato array para poder usar Rule::unique()->ignore()
            "nombre" => [
                "sometimes",
                "required",
                "string",
                "max:255",
                Rule::unique('productos', 'nombre')->ignore($productoId)
            ],

            "descripcion" => "nullable|string",
            "precio" => "sometimes|required|numeric|min:0",
            "stock" => "sometimes|required|integer|min:0",
            "imagen" => "nullable|image|mimes:jpeg,png,jpg,webp|max:2048",

            "categorias" => "sometimes|required|array|min:1",
            "categorias.*" => "uuid|exists:categorias,id"
        ];
    }
}
