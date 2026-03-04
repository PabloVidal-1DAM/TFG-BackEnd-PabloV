<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowPedidosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        // Si el usuario es admin, puede hacer la acción.
        if ($user->hasRole('admin')) {
            return true;
        }

        // Si es Cliente, debe tener el permiso de ver sus pedidos.
        return $user->hasPermissionTo('ver-mis-pedidos');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
