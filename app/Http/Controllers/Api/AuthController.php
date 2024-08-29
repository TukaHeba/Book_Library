<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    /**
     * AuthService instance.
     * @var AuthService
     */
    protected $authService;

    /**
     * Inject AuthService dependency into the controller.
     * @param AuthService $movieService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handles user login requests.
     *
     * @param LoginRequest $request 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        return $this->authService->login($credentials);
    }

    /**
     * Handles user registration requests.
     *
     * @param RegisterRequest $request 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        return $this->authService->register($data);
    }

    /**
     * Handles user logout requests.
     *
     * @return \Illuminate\Http\JsonResponse 
     */
    public function logout()
    {
        return $this->authService->logout();
    }

    /**
     * Handles token refresh requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function refresh()
    // {
    //     return $this->authService->refresh();
    // }
}
