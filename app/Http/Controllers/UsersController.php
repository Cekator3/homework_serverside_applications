<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use App\Models\ChangeLog;
use App\DTO\Auth\UserListDTO;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\DTO\UserRoles\UserRoleDTO;
use App\DTO\UserRoles\UserRoleListDTO;
use App\Http\Requests\Users\AddUserRoleRequest;


class UsersController
{
    function getUsers()
    {
        $users = User::all();
        return new JsonResponse(UserListDTO::fromOrm($users));
    }

    function getUserRoles(mixed $userId)
    {
        $userRoles = UserRole::where('user_id', '=', $userId)->get();
        return new JsonResponse(UserRoleListDTO::fromOrm($userRoles));
    }

    function addUserRole(AddUserRoleRequest $request, mixed $userId, mixed $roleId)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        if (UserRole::where(['user_id' => $userId, 'role_id' => $roleId])->exists())
            abort(Response::HTTP_BAD_REQUEST, "User already have this role");

        $userRole = UserRole::create($data);
        return new JsonResponse(UserRoleDTO::fromOrm($userRole));
    }

    function hardDeleteUserRole(mixed $userId, mixed $roleId)
    {
        $role_permission = UserRole::withTrashed()->where([
            ['user_id', '=', $userId],
            ['role_id', '=', $roleId]
        ])->firstOrFail();
        $role_permission->forceDelete();
    }

    function softDeleteUserRole(mixed $userId, mixed $roleId)
    {
        $role_permission = UserRole::where([
            ['user_id', '=', $userId],
            ['role_id', '=', $roleId]
        ])->firstOrFail();
        $role_permission->delete();
    }

    function restoreSoftDeletedUserRole(mixed $userId, mixed $roleId)
    {
        $role_permission = UserRole::withTrashed()->where([
            ['user_id', '=', $userId],
            ['role_id', '=', $roleId],
        ])->firstOrFail();
        $role_permission->restore();
    }

    /**
     * Returns user's change logs
     */
    function getUserChangeLogs(mixed $userId)
    {
        return ChangeLog::where([
            ['entity_name', '=', User::class],
            ['entity_id', '=', $userId]
        ])->get();
    }
}
