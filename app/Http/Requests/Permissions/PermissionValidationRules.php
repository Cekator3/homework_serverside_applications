<?php

namespace App\Http\Requests\Permissions;

use Illuminate\Validation\Rule;

class PermissionValidationRules
{
    public static function DESCRIPTION(bool $is_required): array|string
    {
        $result = 'string|max:255';
        if ($is_required)
            $result .= '|required';
        return $result;
    }

    /**
     * Generate validation rules for a permission's name.
     *
     * If a permission ID is provided, it adds a rule to ignore the specified permission ID
     * when checking for uniqueness, which is useful for updating existing permissions.
     */
    public static function NAME(int|null $permissionId = null): array|string
    {
        $unique_rule = Rule::unique('permissions');
        if ($permissionId != null)
            $unique_rule->ignore($permissionId);

        return [
            'required',
            'string',
            $unique_rule
        ];
    }

    /**
     * Generate validation rules for a permission's code.
     *
     * If a permission ID is provided, it adds a rule to ignore the specified permission ID
     * when checking for uniqueness, which is useful for updating existing permissions.
     */
    public static function CODE(int|null $permissionId = null): array|string
    {
        $unique_rule = Rule::unique('permissions');
        if ($permissionId != null)
            $unique_rule->ignore($permissionId);

        return [
            'required',
            'string',
            $unique_rule
        ];
    }
}
