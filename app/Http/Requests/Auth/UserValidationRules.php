<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules\Password;

class UserValidationRules
{
    public const NAME = 'required|alpha|max:255|min:7';
    public const EMAIL = 'required|email|unique:users';
    public const BIRTHDAY = 'required|date_format:Y-m-d';
    public static function PASSWORD(bool $with_confirmation) : array
    {
        $rules = [
            'required',
            Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
        ];
        if ($with_confirmation)
            $rules []= 'confirmed';
        return $rules;
    }
}
