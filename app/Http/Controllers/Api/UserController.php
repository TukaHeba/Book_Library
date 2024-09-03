<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * The user service instance.
     * @var UserService
     */
    protected $userService;

    /**
     * UserController constructor.
     * 
     * @param UserService $userService The user service instance.
     * Admins can access all methods
     * Clients can access all methods except 'index'
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        $this->middleware('admin');
    }

    /**
     * Display a listing of users (only accessible to admin).
     * 
     * Fetch users from the service
     * Handle the exception from the service
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $users = $this->userService->getAllUsers();

            return ApiResponseService::paginated($users, UserResource::class, 'Users retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created user in the database (only accessible to admin).
     * 
     * Validate and create user data
     * Call the service to store the user including the role
     * Handle any errors from the service

     * @param StoreUserRequest $request The validated request data.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = $this->userService->createUser($validated);

        if ($user) {
            return ApiResponseService::success(new UserResource($user), 'User created successfully', 201);
        } else {
            return ApiResponseService::error('User creation failed', 400);
        }
    }

    /**
     * Display the specified user.
     * 
     * Fetch users from the service
     * Handle the exception from the service
     * 
     * @param User $user The user to display.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = $this->userService->showUser($id);
            return ApiResponseService::success(new UserResource($user), 'User retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 404);
        }
    }

    /**
     * Update the specified user in the database.
     * 
     * Validate and update user data
     * Call the service to update the user including the role
     * Handle any errors from the service
     * 
     * @param UpdateUserRequest $request The validated request data.
     * @param User $user The user to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $user = $this->userService->updateUser($id, $validated);

            return ApiResponseService::success(new UserResource($user), 'User updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified user from the database.
     * 
     * Delete the user then return a success response
     * Handle any errors from the service
     * 
     * @param User $user The user to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);

            return ApiResponseService::success(null, 'User deleted successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }
}
