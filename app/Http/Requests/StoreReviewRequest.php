<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            // Validamos que exista y que sea ÚNICO para este usuario en concreto
            'producto_id' => [
                'required',
                'uuid',
                'exists:productos,id',
                Rule::unique('reviews')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id);
                })
            ],
            'valoracion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'producto_id.unique' => 'Ya has publicado una reseña para este producto. Si deseas cambiarla, edita la que ya tienes.',
        ];
    }
}
