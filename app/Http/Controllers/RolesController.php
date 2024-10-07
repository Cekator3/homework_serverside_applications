<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\DTO\Roles\RoleDTO;
use Illuminate\Http\Response;
use App\DTO\Roles\RoleListDTO;
use App\Models\RolePermission;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Roles\CreateRequest;
use App\Http\Requests\Roles\UpdateRequest;
use App\DTO\RolePermissions\RolePermissionDTO;
use App\Http\Requests\RolePermissions\AddRequest;
use App\DTO\RolePermissions\RolePermissionListDTO;


class RolesController
{
    /**
     * Returns all roles
     */
    function getRoles()
    {
        $roles = Role::all();
        return new JsonResponse(RoleListDTO::fromOrm($roles));
    }

    function getRole(mixed $roleId)
    {
        $role = Role::findOrFail($roleId);
        return new JsonResponse(RoleDTO::fromOrm($role));
    }

    function createRole(CreateRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $role = Role::create($data);
        return new JsonResponse(RoleDTO::fromOrm($role));
    }

    function updateRole(UpdateRequest $request, mixed $roleId)
    {
        $data = $request->validated();

        $role = Role::findOrFail($roleId);
        $role->fill($data);
        $role->save();

        return new JsonResponse(RoleDTO::fromOrm($role));
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
        $rolePermissions = RolePermission::where('role_id', '=', $roleId)->get();
        return new JsonResponse(RolePermissionListDTO::fromOrm($rolePermissions));
    }

    function addRolePermission(AddRequest $request, mixed $roleId, mixed $permissionId)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        if (RolePermission::where(['permission_id' => $permissionId, 'role_id' => $roleId])->exists())
            abort(Response::HTTP_BAD_REQUEST, "This permission has already been assigned to this role");

        $rolePermission = RolePermission::create($data);
        return new JsonResponse(RolePermissionDTO::fromOrm($rolePermission));
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
}
