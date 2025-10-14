<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnforceApiMode
{
    /**
     * Handle an incoming request.
     *
     * When CLAUDE_URL is set (API mode), this middleware prevents
     * any direct database access and forces all operations through API.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $claudeUrl = config('claude.url');
        $apiModeEnabled = config('claude.enabled');

        // If API mode is enabled (CLAUDE_URL is set)
        if ($apiModeEnabled && !empty($claudeUrl)) {
            // Disable all database connections to prevent local DB access
            // This forces the application to use API calls only

            // Store the original connection
            config(['database.connections.mysql.driver' => 'null']);

            // Prevent any accidental database queries
            DB::listen(function ($query) {
                throw new \Exception(
                    'Database access is disabled in API mode. ' .
                    'Please ensure CLAUDE_URL is configured correctly and the server is reachable. ' .
                    'Current CLAUDE_URL: ' . config('claude.url')
                );
            });
        }

        return $next($request);
    }
}
