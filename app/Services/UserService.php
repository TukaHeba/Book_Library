<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Retrieve all users with pagination.
     * 
     * Fetch paginated users
     * Log the exception and throw it
     * @return LengthAwarePaginator
     */
    public function getAllUsers()
    {
        try {
            return User::paginate(10);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve users: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve users');
        }
    }

    /**
     * Create a new user with a hashed password and assign a role.
     * 
     * @param array $data The validated user data, including 'password' and 'role'.
     * @return User|null The newly created user or null if creation fails.
     */
    public function createUser(array $data): ?User
    {
        try {
            $data['password'] = Hash::make($data['password']);

            $role = Role::where('name', $data['role'])->first();

            if (!$role) {
                throw new \Exception('Role not found');
            }

            $user = User::create($data);
            $user->assignRole($role->name);

            return $user;
        } catch (\Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Show the details of a specific user by their ID.
     * 
     * Attempt to retrieve the user by ID
     * Log the error and rethrow the exception for the controller to handle
     * 
     * @param mixed $id The ID of the user to retrieve.
     * @throws \Exception If the user is not found.
     * @return User The retrieved user.
     */
    public function showUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return $user;
        } catch (\Exception $e) {
            Log::error('User retrieval failed: ' . $e->getMessage());
            throw new \Exception('User not found');
        }
    }

    /**
     * Update the given user with the provided data.
     * 
     * Find the user by ID
     * Update the user's data
     * Handle role update if provided (remove the previous one and assign the new)
     * Log the error and rethrow the exception
     * 
     * @param User $user The user to update.
     * @param array $data The validated user data.
     * @return User The updated user.
     */
    public function updateUser($id, array $data)
    {
        try {
            $user = User::findOrFail($id);

            $user->update($data);

            if (isset($data['role'])) {
                $role = Role::where('name', $data['role'])->first();

                if (!$role) {
                    throw new \Exception('Role not found');
                }

                $user->syncRoles([$role->name]);
            }

            return $user;
        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            throw new \Exception('Failed to update user');
        }
    }

    /**
     * Delete the given user from the database.
     * 
     * Find the user by ID and delete them
     * Log the error and rethrow the exception
     * @param int $id The ID of the user to delete.
     * @return bool True if deletion is successful, false otherwise.
     */
    public function deleteUser($id): bool
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage());
            throw new \Exception('Failed to delete user');
        }
    }
}
