<?php

namespace App\Http\Requests\Roles;

use Illuminate\Validation\Rule;

class RoleValidationRules
{
    public static function DESCRIPTION(bool $is_required): array|string
    {
        $result = 'string|max:255';
        if ($is_required)
            $result .= '|required';
        return $result;
    }

    /**
     * Get validation rules for a role's name.
     *
     * If a role ID is provided, it adds a rule to ignore the specified role ID
     * when checking for uniqueness, which is useful for updating existing roles.
     */
    public static function NAME(int|null $roleId = null): array|string
    {
        $unique_rule = Rule::unique('roles');
        if ($roleId != null)
            $unique_rule->ignore($roleId);

        return [
            'required',
            'string',
            $unique_rule
        ];
    }

    /**
     * Get validation rules for a role's code.
     *
     * If a role ID is provided, it adds a rule to ignore the specified role ID
     * when checking for uniqueness, which is useful for updating existing roles.
     */
    public static function CODE(int|null $roleId = null): array|string
    {
        $unique_rule = Rule::unique('roles');
        if ($roleId != null)
            $unique_rule->ignore($roleId);

        return [
            'required',
            'string',
            $unique_rule
        ];
    }
}
