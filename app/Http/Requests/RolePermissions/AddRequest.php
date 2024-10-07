<?php

namespace App\Http\Requests\RolePermissions;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'role_id' => $this->route('id'),
            'permission_id' => $this->route('permission_id')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'role_id' => 'required|exists:roles,id',
            'permission_id' => "required|exists:permissions,id",
        ];
    }
}
