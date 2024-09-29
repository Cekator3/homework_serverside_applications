<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionsController
{
    function getPermissions()
    {
        return Permission::all();
    }

    function getPermission(mixed $permissionId)
    {
        return Permission::findOrFail($permissionId);
    }

    function createPermission(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:permissions',
            'description' => 'required|string|max:255',
            'code' => 'nullable|string|unique:permissions',
        ]);
        $validatedData['created_by'] = $request->user()->id;

        return Permission::create($validatedData);
    }

    function updatePermission(Request $request, mixed $permissionId)
    {
        $role = Permission::findOrFail($permissionId);

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

        $role->fill($data);
        $role->save();
        return $role;
    }

    function hardDeletePermission(mixed $permissionId)
    {
        $role = Permission::withTrashed()->findOrFail($permissionId);
        $role->forceDelete();
    }

    function softDeletePermission(mixed $permissionId)
    {
        $role = Permission::findOrFail($permissionId);
        $role->delete();
    }

    function restoreSoftDeletedPermission(mixed $permissionId)
    {
        Permission::withTrashed()->find($permissionId)->restore();
    }
}
