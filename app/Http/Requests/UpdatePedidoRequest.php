<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePedidoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user->hasRole('admin')) { // solo el usuario puede editar los pedidos ya creados.
            return true;
        }

        // Si existiera un rol futuro que tenga este permiso, también podría hacer esta acción.
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
            'estado' => 'sometimes|string|in:pendiente,enviado,entregado'
        ];
    }
}
