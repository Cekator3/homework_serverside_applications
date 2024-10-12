<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\DTO\Auth\UserDTO;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\ChangePasswordRequest;


class AuthController
{
    /**
     * Tries to login a user
     */
    function login(LoginRequest $request)
    {
        $user = User::where('name', $request->name)->first();

        if (! $user || ! Hash::check($request->password, $user->password))
            return new JsonResponse(['message' => "Неверный логин или пароль"], Response::HTTP_BAD_REQUEST);

        DB::beginTransaction();
        $token = $user->createToken($user->name);

        $tokensAmount = $user->tokens()->count();
        if ($tokensAmount > config('sanctum.max_tokens')) {
            $tokensToDelete = $tokensAmount - config('sanctum.max_tokens');
            $user->tokens()->oldest()->limit($tokensToDelete)->delete();
        }
        DB::commit();

        return ['token' => $token->plainTextToken];
    }

    /**
     * Tries to register a user
     */
    function register(RegisterRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        $user = User::create($data);
        $token = $user->createToken($user->name);
        $guest_role_id = Role::where('code', '=', config('auth.default_user_role_code'))
                             ->first('id')->id;
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $guest_role_id,
            'created_by' => $user->id
        ]);
        DB::commit();

        return new JsonResponse(['token' => $token->plainTextToken], Response::HTTP_CREATED);
    }

    /**
     * Tries to change the authenticated user's password
     */
    function changePassword(ChangePasswordRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();

        if (! Hash::check($data['old_password'], $user->password))
            return new JsonResponse(['message' => "Указан неверный старый пароль"], Response::HTTP_BAD_REQUEST);

        DB::beginTransaction();
        $user->password = Hash::make($data['new_password']);
        ChangeLog::log_entity_changes($user);
        $user->save();
        DB::commit();
    }

    /**
     * Logouts a user
     */
    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    /**
     * Returns authenticated user's info
     */
    function getUserInfo(Request $request)
    {
        return ["user" => UserDTO::fromOrm($request->user())];
    }

    /**
     * Returns all of the authenticated user's tokens
     */
    function getUserTokens(Request $request)
    {
        return $request->user()->tokens()->get()->pluck('token');
    }

    /**
     * Revokes all authenticated user's tokens
     */
    function expireAllUserTokens(Request $request)
    {
        $request->user()->tokens()->delete();
    }
}
