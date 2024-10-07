<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Auth\UserValidationRules;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => UserValidationRules::NAME,
            'email' => UserValidationRules::EMAIL,
            'birthday' => UserValidationRules::BIRTHDAY,
            'password' => UserValidationRules::PASSWORD(with_confirmation: true)
        ];
    }
}
