<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('crear-review');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // El usuario debe indicar a qué producto le está haciendo la review, para evitar reviews fantasmas.
            'producto_id' => 'required|uuid|exists:productos,id',
            'valoracion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string',
        ];
    }
}
