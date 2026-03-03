<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
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
        return [
            // Se exige que se envíe un array 'items' con al menos 1 producto
            'items' => 'required|array|min:1',

            // Se valida cada elemento dentro del array para:
            // Ver que existe el id del producto metido dentro.
            // Ver que cantidad esta puesta y con valor mínimo de 1.
            'items.*.producto_id' => 'required|uuid|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ];
    }
}
