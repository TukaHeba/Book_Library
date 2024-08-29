<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\ApiResponseService;

class AuthService
{
    /**
     * Attempt to log in a user with the given credentials.
     *
     * @param array $credentials The user's login credentials.
     * @return \Illuminate\Http\JsonResponse An array containing the login response.
     */
    public function login(array $credentials)
    {
        if (!$token = Auth::attempt($credentials)) {
            return ApiResponseService::error(null, 'Unauthorized', 401);
        }

        return ApiResponseService::success([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ], 'Login Successful', 200);
    }

    /**
     * Register a new user with the given data.
     *
     * @param array $data The user's registration data.
     * @return \Illuminate\Http\JsonResponse An array containing the registration response.
     */
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // $user->assignRole('client');
        // $token = Auth::login($user);

        return ApiResponseService::success([
            'user' => $user,
            'authorisation' => [
                // 'token' => $token,
                'type' => 'bearer',
            ],
        ], 'User created successfully', 201);
    }

    /**
     * Logout the current user.
     *
     * @return \Illuminate\Http\JsonResponse An array containing the logout response.
     */
    public function logout()
    {
        Auth::logout();

        return ApiResponseService::success(null, 'Logged out successfully', 200);
    }

    /**
     * Refresh the user's authentication token.
     *
     * @return \Illuminate\Http\JsonResponse An array containing the refreshed token.
     */
    // public function refresh()
    // {
    //     return ApiResponseService::success([
    //         'user' => Auth::user(),
    //         'authorisation' => [
    //             'token' => Auth::refresh(),
    //             'type' => 'bearer',
    //         ],
    //     ], 'Token refreshed successfully', 200);
    // }
}
