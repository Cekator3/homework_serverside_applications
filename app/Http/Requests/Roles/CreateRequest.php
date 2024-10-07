<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Roles\RoleValidationRules;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => RoleValidationRules::NAME(),
            'description' => RoleValidationRules::DESCRIPTION(is_required: true),
            'code' => RoleValidationRules::CODE()
        ];
    }
}
