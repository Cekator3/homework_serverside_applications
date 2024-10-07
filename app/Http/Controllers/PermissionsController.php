<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use App\DTO\Permissions\PermissionDTO;
use App\DTO\Permissions\PermissionListDTO;
use App\Http\Requests\Permissions\CreateRequest;
use App\Http\Requests\Permissions\UpdateRequest;

class PermissionsController
{
    function getPermissions()
    {
        $permissions = Permission::all();
        return new JsonResponse(PermissionListDTO::fromOrm($permissions));
    }

    function getPermission(mixed $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        return new JsonResponse(PermissionDTO::fromOrm($permission));
    }

    function createPermission(CreateRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $result = Permission::create($data);
        return new JsonResponse(PermissionDTO::fromOrm($result));
    }

    function updatePermission(UpdateRequest $request, mixed $permissionId)
    {
        $data = $request->validated();
        $permission = Permission::findOrFail($permissionId);
        $permission->fill($data);
        $permission->save();
        return new JsonResponse(PermissionDTO::fromOrm($permission));
    }

    function hardDeletePermission(mixed $permissionId)
    {
        $permission = Permission::withTrashed()->findOrFail($permissionId);
        $permission->forceDelete();
    }

    function softDeletePermission(mixed $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $permission->delete();
    }

    function restoreSoftDeletedPermission(mixed $permissionId)
    {
        Permission::withTrashed()->find($permissionId)->restore();
    }
}
