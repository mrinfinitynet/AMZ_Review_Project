<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        // Get the expected token from config
        $expectedToken = config('claude.api_token');

        // Check if token is provided
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'API token is required. Please provide a valid bearer token.',
            ], 401);
        }

        // Validate token
        if ($token !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API token provided.',
            ], 403);
        }

        return $next($request);
    }
}
