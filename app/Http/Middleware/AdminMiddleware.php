<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(auth()->user()->role);
        // Log::info('AdminMiddleware called');

        if (!auth()->check()) {
            Log::info('User NOT authenticated');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Log::info('User IS authenticated. User ID: ' . auth()->id());
        // Log::info('User Role: ' . auth()->user()->role); // Log the user's role

        if (auth()->user()->role !== 'admin') { // Check if the role is 'admin'
            // Log::info('User is NOT an admin. Role: ' . auth()->user()->role);
            return response()->json(['message' => 'Forbidden - Admin access only'], 403);
        }
        // dd('hello');

        Log::info('User IS an admin, proceeding');
        return $next($request);
    }
}
