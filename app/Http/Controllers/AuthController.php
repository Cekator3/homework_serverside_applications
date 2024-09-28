<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;


class AuthController
{
    /**
     * Tries to login a user
     */
    function login(Request $request)
    {
        $request->validate([
            'name' => 'required|alpha|max:255|min:7',
            'password' => [
                'required',
                Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
            ],
        ]);
        $user = User::where('name', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password))
            return new JsonResponse(['message' => "Неверный логин или пароль"], Response::HTTP_BAD_REQUEST);

        $user->tokens()->delete();
        $token = $user->createToken($user->name);

        return ['token' => $token->plainTextToken];
    }

    /**
     * Tries to register a user
     */
    function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|alpha|max:255|min:7|unique:users',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
            ],
            'birthday' => 'required|date_format:Y-m-d'
        ]);

        $user = User::create($data);
        $token = $user->createToken($user->name);

        return new JsonResponse(['token' => $token->plainTextToken], Response::HTTP_CREATED);
    }

    /**
     * Tries to change the authenticated user's password
     */
    function changePassword(Request $request)
    {
        $validation_rules = [
            'required',
            Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
        ];
        $request->validate([
            'oldPassword' => $validation_rules,
            'newPassword' => $validation_rules
        ]);

        $user = $request->user();

        if (!Hash::check($request->oldPassword, $user->password))
            return new JsonResponse(['message' => "Указан неверный старый пароль"], Response::HTTP_BAD_REQUEST);

        $user->password = Hash::make($request->newPassword);
        $user->save();
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
        return ["user" => $request->user()];
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
