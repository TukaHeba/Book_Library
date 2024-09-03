<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Ensure the user is authenticated
        if (!Auth::check()) {
            return ApiResponseService::error(null, 'Unauthorized', 401);
        }

        // Check if the user is an admin
        if (!Auth::user()->is_admin) {
            return ApiResponseService::error(null, 'Forbidden: Admins only', 403);
        }

        // If the user is an admin, continue the request
        return $next($request);
    }
}
