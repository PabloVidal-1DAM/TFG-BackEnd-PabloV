<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShowUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Si el usuario es administrador, o tiene el permiso para ver a un usuario
        // Y su id coincide con el que nos pasa en las rutas, puede hacer la acción.
        $user = Auth::user();
        if($user->hasRole('admin')) {
            return true;
        }else{
            if($user->hasPermissionTo('ver-usuario') && $user->id == $this->route('user')->id) {
                return true;
            }else{
                return false;
            }
        }
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
