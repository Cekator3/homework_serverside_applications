<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class UsersController
{
    function getUsers()
    {
        return User::all();
    }

    function getUserRoles(mixed $userId)
    {
        return UserRole::where('user_id', '=', $userId)->get();
    }

    function addUserRole(Request $request, mixed $userId, mixed $roleId)
    {
        $data = Validator::make(
            data: [
                'user_id' => $userId,
                'role_id' => $roleId,
                'created_by' => $request->user()->id,
            ],
            rules: [
                'user_id' => "required|exists:users,id",
                'role_id' => 'required|exists:roles,id',
                'created_by' => 'required'
            ]
        )->validate();

        if (UserRole::where(['user_id' => $userId, 'role_id' => $roleId])->exists())
            abort(Response::HTTP_BAD_REQUEST, "User already have this role");

        return UserRole::create($data);
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
