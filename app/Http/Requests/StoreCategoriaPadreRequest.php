<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCategoriaPadreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        // Si no es Admin, no puede guardar una categoria padre a la BD.
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
            'nombre' => 'required|string|max:255|unique:categoria_padres,nombre',
            'descripcion' => 'nullable|string',
        ];
    }
}
