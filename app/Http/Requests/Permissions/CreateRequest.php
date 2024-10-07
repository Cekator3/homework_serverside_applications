<?php

namespace App\Http\Requests\Permissions;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => PermissionValidationRules::NAME(),
            'description' => PermissionValidationRules::DESCRIPTION(is_required: true),
            'code' => PermissionValidationRules::CODE()
        ];
    }
}
