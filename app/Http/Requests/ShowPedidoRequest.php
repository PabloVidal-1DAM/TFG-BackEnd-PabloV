<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowPedidoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $pedido = $this->route('pedido'); // Se obtiene de la BD el pedido que se pide desde la URL

        // El Admin lo ve todo
        if ($user->hasRole('admin')) {
            return true;
        }

        // El Cliente solo ve SU propio pedido
        // Para ello Debe tener permiso 'ver-mis-pedidos' Y ser el dueño del pedido.
        if ($user->hasPermissionTo('ver-mis-pedidos') && $pedido->user_id === $user->id) {
            return true;
        }

        return false;
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
