<?php

namespace App\Http\Requests\Permissions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $permissionId = $this->route('id');
        return [
            'name' => PermissionValidationRules::NAME($permissionId),
            'description' => PermissionValidationRules::DESCRIPTION(is_required: true),
            'code' => PermissionValidationRules::CODE($permissionId)
        ];
    }
}
