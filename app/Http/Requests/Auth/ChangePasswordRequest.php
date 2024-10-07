<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Auth\UserValidationRules;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'old_password' => UserValidationRules::PASSWORD(with_confirmation:false),
            'new_password' => UserValidationRules::PASSWORD(with_confirmation:false),
        ];
    }
}
