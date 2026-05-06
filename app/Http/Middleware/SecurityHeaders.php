<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking attacks
        $response->header('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->header('X-Content-Type-Options', 'nosniff');

        // Referrer policy
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy (formerly Feature-Policy)
        $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Cache control for authenticated pages
        if (auth()->check()) {
            $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->header('Pragma', 'no-cache');
        }

        return $response;
    }
}
