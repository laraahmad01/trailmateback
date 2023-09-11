<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Define the allowed origins (domains) that can access your server.
        $allowedOrigins = [
            'http://example.com',
            'https://example.com',
            // Add more origins as needed.
        ];

        // Define the allowed HTTP methods (e.g., 'GET', 'POST', 'PUT', 'DELETE').
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];

        // Define the allowed headers (e.g., 'Content-Type', 'Authorization').
        $allowedHeaders = ['Content-Type', 'Authorization'];

        // Check if the incoming request's origin is in the list of allowed origins.
        $origin = $request->header('Origin');
        if (in_array($origin, $allowedOrigins)) {
            // Set the CORS headers to allow the specified origin, methods, and headers.
            return $next($request)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', implode(', ', $allowedMethods))
                ->header('Access-Control-Allow-Headers', implode(', ', $allowedHeaders));
        }

        return $next($request);
    }
}
