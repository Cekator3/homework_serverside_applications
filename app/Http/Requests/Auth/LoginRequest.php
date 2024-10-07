<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Auth\UserValidationRules;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => UserValidationRules::NAME,
            'password' => UserValidationRules::PASSWORD(with_confirmation: false)
        ];
    }
}
