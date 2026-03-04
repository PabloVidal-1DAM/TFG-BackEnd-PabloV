<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeletePedidoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        // O eres admin, o eres alguien con el permiso explícito de gestionar pedidos,
        // pero un cliente NUNCA podrá borrar pedidos.
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasPermissionTo('gestionar-pedido');
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
