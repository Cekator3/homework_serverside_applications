<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Role;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\RolePermission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class RolesController
{
    /**
     * Returns all roles
     */
    function getRoles()
    {
        return Role::all();
    }

    function getRole(mixed $roleId)
    {
        return Role::findOrFail($roleId);
    }

    function createRole(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles',
            'description' => 'required|string|max:255',
            'code' => 'nullable|string|unique:roles',
        ]);
        $validatedData['created_by'] = $request->user()->id;

        return Role::create($validatedData);
    }

    function updateRole(Request $request, mixed $roleId)
    {
        $role = Role::findOrFail($roleId);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('roles')->ignore($role)
            ],
            'description' => 'string',
            'code' => [
                'required',
                'string',
                Rule::unique('roles')->ignore($role)
            ],
        ]);

        DB::transaction(function () use ($role, $data) {
            $role->fill($data);
            ChangeLog::log_entity_changes($role);
            $role->save();
        });

        return $role;
    }

    function hardDeleteRole(mixed $roleId)
    {
        $role = Role::withTrashed()->findOrFail($roleId);
        $role->forceDelete();
    }

    function softDeleteRole(mixed $roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->delete();
    }

    function restoreSoftDeletedRole(mixed $roleId)
    {
        Role::withTrashed()->find($roleId)->restore();
    }

    function getRolePermissions(mixed $roleId)
    {
        return RolePermission::where('role_id', '=', $roleId)->get();
    }

    function addRolePermission(Request $request, mixed $roleId, mixed $permissionId)
    {
        $data = Validator::make(
            data: [
                'role_id' => $roleId,
                'permission_id' => $permissionId,
                'created_by' => $request->user()->id,
            ],
            rules: [
                'role_id' => 'required|exists:roles,id',
                'permission_id' => "required|exists:permissions,id",
                'created_by' => 'required'
            ]
        )->validate();

        if (RolePermission::where(['permission_id' => $permissionId, 'role_id' => $roleId])->exists())
            abort(Response::HTTP_BAD_REQUEST, "This permission has already been assigned to this role");

        return RolePermission::create($data);
    }

    function hardDeleteRolePermission(mixed $roleId, mixed $permissionId)
    {
        $role_permission = RolePermission::withTrashed()->where([
            ['role_id', '=', $roleId],
            ['permission_id', '=', $permissionId]
        ])->firstOrFail();
        $role_permission->forceDelete();
    }

    function softDeleteRolePermission(mixed $roleId, mixed $permissionId)
    {
        $role_permission = RolePermission::where([
            ['role_id', '=', $roleId],
            ['permission_id', '=', $permissionId]
        ])->firstOrFail();
        $role_permission->delete();
    }

    function restoreSoftDeletedRolePermission(mixed $roleId, mixed $permissionId)
    {
        $role_permission = RolePermission::withTrashed()->where([
            ['role_id', '=', $roleId],
            ['permission_id', '=', $permissionId]
        ])->firstOrFail();
        $role_permission->restore();
    }

    /**
     * Returns role's change logs
     */
    function getRoleChangeLogs(mixed $roleId)
    {
        return ChangeLog::where([
            ['entity_name', '=', Role::class],
            ['entity_id', '=', $roleId]
        ])->get();
    }
}
