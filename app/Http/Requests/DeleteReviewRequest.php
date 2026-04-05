<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        // Se obtiene de la BD la review que se pasa a borrar.
        $review = $this->route('review');

        // El Admin es moderador, puede borrar cualquier review que desee.
        if ($user->hasRole('admin')) {
            return true;
        }

        // El Cliente solo puede borrar SU propia review
        if ($user->hasPermissionTo('administrar-review') && $review->user_id === $user->id) {
            return true;
        }

        // Si no es admin y la review no es suya, no deja hacer la acción
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
