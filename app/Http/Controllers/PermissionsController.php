<?php

namespace App\Http\Controllers;

use DB;
use App\Models\ChangeLog;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use App\Exports\PermissionsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\DTO\Permissions\PermissionDTO;
use App\DTO\Permissions\PermissionListDTO;
use App\Http\Requests\Users\ExcelFileRequest;
use App\Http\Requests\Permissions\CreateRequest;
use App\Http\Requests\Permissions\UpdateRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        DB::beginTransaction();
        $permission->fill($data);
        ChangeLog::log_entity_changes($permission);
        $permission->save();
        DB::commit();

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

    /**
     * Returns permission's change logs
     */
    function getPermissionChangeLogs(mixed $permissionId)
    {
        return ChangeLog::where([
            ['entity_name', '=', Permission::class],
            ['entity_id', '=', $permissionId]
        ])->get();
    }

    function import(ExcelFileRequest $request): JsonResponse
    {
    }

    function export(): BinaryFileResponse
    {
        return Excel::download(new PermissionsExport, 'permissions.xlsx');
    }
}
